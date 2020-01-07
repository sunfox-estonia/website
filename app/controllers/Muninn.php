<?php
require_once('/var/www/virvik/sunfox.ee/app/controllers/Events.php');
/*
 * Based on https://github.com/VKCOM/bot-example-php 
 */
class vkcom {
    // API keys
    const CALLBACK_API_CONFIRMATION_TOKEN = 'a87a1db0'; //Строка для подтверждения адреса сервера из настроек Callback API
    const VK_API_ACCESS_TOKEN = '43c16eee15b83eba742c024c62f55ef752ec428ee09d007b42596d4b0437ceefec1a439f9b0a9a00b77ba'; //Ключ доступа сообщества

    // API settings
    const VK_API_VERSION = '5.70';
    const VK_API_ENDPOINT = 'https://api.vk.com/method/';
   
    protected function _callback_getRequest() {
        return json_decode(file_get_contents('php://input'));
    }
    
    protected function _callback_handleConfirmation() {
        $this->_callback_response(self::CALLBACK_API_CONFIRMATION_TOKEN);
    }

    protected function _callback_okResponse() {
        $this->_callback_response('ok');
        error_log('Sent OK', 0);
    }

    protected function _callback_response($data) {
        echo $data;
        exit();
    }
  
    /* Send message to user */
    protected function vkApi_messagesSend($user_id, $message) {
        return $this->_vkApi_call('messages.send', array(
          'user_id'    => $user_id,
          'message'    => $message
        ));
    }    
    
    protected function vkApi_keyboardSend($user_id, $message, $commands=[], $one_time = False) {
        $keyboard_prep = [];
        $i = 0;
        foreach ($commands as $button_str) {
            $j = 0;
            foreach ($button_str as $button) {
                $color = $button[2];
                $keyboard_prep[$i][$j]["action"]["type"] = "text";
                if ($button[0] != null)
                    $keyboard_prep[$i][$j]["action"]["payload"] = json_encode($button[0], JSON_UNESCAPED_UNICODE);
                $keyboard_prep[$i][$j]["action"]["label"] = $button[1];
                $keyboard_prep[$i][$j]["color"] = $color;
                $j++;
            }
            $i++;
        }
        $keyboard_fin = array(
            "one_time" => $one_time,
            "buttons" => $keyboard_prep);        
        $keyboard_fin = json_encode($keyboard_fin, JSON_UNESCAPED_UNICODE);
        error_log($keyboard_fin, 0);
        return $this->_vkApi_call('messages.send', array(
          'user_id'    => $user_id,
          'message'    => $message,
          'keyboard'   => $keyboard_fin
        ));
        error_log("Message + keyboard sent", 0);
    }

    
    /* Get iser data by id */
    protected function vkApi_usersGet($user_id) {
        return $this->_vkApi_call('users.get', array(
          'user_ids' => $user_id,
        ));
    }    
    
    protected function _vkApi_call($method,$params=array()){
        $url = 'https://api.vk.com/method/'.$method;
        $params['access_token']=self::VK_API_ACCESS_TOKEN;
        $params['v']=self::VK_API_VERSION;
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type:multipart/form-data"
            ));
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            $json = curl_exec($ch);
            $error = curl_error($ch);
            if ($error) {
              error_log($json, 0);
              throw new Exception("Failed {$method} request");
            }
            curl_close($ch);
        } else {
            $json = file_get_contents($url, true, stream_context_create(array(
                'http' => array(
                    'method'  => 'POST',
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'content' => http_build_query($params)
                )
            )), true);
        }
        $response = json_decode($json, TRUE);        
        if (isset($response['response'])){
            return $response['response'];
        }else{
            error_log($json, 0);
            throw new Exception("Invalid response for {$method} request");
        }
    }
}

/*
 * Muninn chatbot main class
 * 
 * Community events: https://vk.com/dev/groups_events
 * Keyboard: https://vk.com/dev/bots_docs_3?f=4.%2B%D0%9A%D0%BB%D0%B0%D0%B2%D0%B8%D0%B0%D1%82%D1%83%D1%80%D1%8B%2B%D0%B4%D0%BB%D1%8F%2B%D0%B1%D0%BE%D1%82%D0%BE%D0%B2
 * Additional docs & examples: https://game-tips.ru/it/kak-sdelat-knopki-byistryih-otvetov-keyboard-dlya-botov-v-vk/#php_code
 *
 * Check log here: /var/www/logs/php-fpm/error.log
 */
class Muninn extends vkcom {
    const BTN_EVENTS_SHOWALL = [["command" => 'events_show'], "Смотреть мероприятия", "default"];
    const BTN_INFO_START = [["command" => 'info_start'], "Организация", "default"];
    const BTN_INFO_2PAY = [["command" => 'info_2pay'], "Куда оплатить?", "default"];
    const BTN_INFO_2ASK = [["command" => 'info_2ask'], "С кем связаться?", "default"];
    const BTN_INFO_2ORG = [["command" => 'info_2org'], "Реквизиты организации", "default"];
    const BTN_INVITE_START = [["command" => 'invite_start'], "Ввести инвайт", "default"];
    const BTN_HOME = [["command" => 'home'], "Домой", "default"];
    
    public function go(){        
        if (!isset($_REQUEST)) {
          exit;
        }
        $this->callback_handleEvent();
    }
        
    // Chat Requests handler
    public function callback_handleEvent() {
        $request = $this->_callback_getRequest();
        try {
            switch ($request->type) {
            //Подтверждение сервера
            case 'confirmation':
                $this->_callback_handleConfirmation();
                break;
            //Вступление в группу
            case 'group_join':
                $req_user = $request->object->user_id;  
                $req_peer_id = $request->object->id;     
                $message = "Благодарим за интерес к Викингам Вирумаа! Напиши, если желаешь посетить пробное занятие или задать вопросы :) ";
                $this->vkApi_messagesSend($req_user, $message);
                $this->_callback_okResponse();
                break;
            //Уход из группы
            case 'group_leave':
                break;
            case 'message_new':
                $req_user_id = $request->object->user_id;  
                $req_peer_id = $request->object->id;  
                $req_payload = ($request->object->payload) ? json_decode($request->object->payload, true) : null;
                $req_msg = mb_strtolower($request->object->body);              
                if($req_msg == 'начать'|| $req_msg == 'start'){
                    $message = "Рады что ты откликнулся! :) Чтобы познакомиться ближе с Викингами, посети одно из наших мероприятий.";
                    $this->vkApi_keyboardSend($req_user_id, $message, [[self::BTN_EVENTS_SHOWALL],[self::BTN_INFO_START,self::BTN_INVITE_START]], $one_time=TRUE);
                    $this->_callback_okResponse();
                    break;
                }
                if ($req_payload){
                    new MuninnCommands($req_payload['command'], $req_user_id, $req_payload['param']);
                    break;
                }  
                break;
            default:
                $this->_callback_response('Unsupported event');
                break;
            }
        } catch (Exception $e) {
            error_log($e, 0);
        }
        $this->_callback_okResponse();
    }
    
}

class MuninnCommands extends Muninn {
    
    protected $task_name;
    protected $user_id;
    protected $param;

    public function __construct($command, $req_user_id, $param=NULL) {
        $this->task_name = $command;
        $this->user_id = $req_user_id;
        $this->param = $param; 
        $this->{$command}();
    }
    
    public function __destruct() {
        $this->_callback_okResponse();
    }

        private function home(){
        $message = "Пожалуйста, выбери действие или задай вопрос.";
        $this->vkApi_keyboardSend($this->user_id, $message, [[self::BTN_EVENTS_SHOWALL],[self::BTN_INFO_START,self::BTN_INVITE_START]], $one_time=TRUE);
    }
    
    private function invite_start(){
        $message = "Пожалуйста, введи и отправь свой инвайт-код. После его активации мир уже не будет прежним...";
        $this->vkApi_keyboardSend($this->user_id, $message, [[self::BTN_HOME]], $one_time=TRUE);
    }
    
    private function info_start() {
        $message = "Здесь вся важная информация об организации и клубе. Пожалуйста, выбери раздел.";
        $this->vkApi_keyboardSend($this->user_id, $message, [[self::BTN_INFO_2PAY, self::BTN_INFO_2ASK],[self::BTN_INFO_2ORG],[self::BTN_HOME]], $one_time=TRUE);
    }
    
    private function info_2pay() {
        $eol="\r\n";
        $message  = "Оплату наших счетов необходимо выполнять на расчетный счет организации, используя следующие реквизиты:" . $eol;
        $message .= "- получатель: MTÜ Sunfox Team" . $eol . "- расчетный счет: EE057700771002527409" . $eol;
        $message .= "- номер ссылки (viitenumber): личный номер участника сообщества";
        $this->vkApi_keyboardSend($this->user_id, $message, [[self::BTN_HOME]], $one_time=TRUE);
    }

    private function info_2ask() {
        $eol="\r\n";
        $message  = "По вопросам, связанным с участием в деятельности наших сообществ можно всегда обратиться к нашим координаторам." . $eol . $eol;
        $message .= "Виктор Литвинков" . $eol . "(координатор сообщества Викинги Вирумаа)" . $eol . "+372 55593171" . $eol . "victor@sunfox.ee" . $eol . $eol;
        $message .= "Роберт Крузберг" . $eol . "(координатор сообщества Einherjar)" . $eol . "+372 53731824" . $eol . "robert@sunfox.ee" . $eol . $eol;
        $message .= "Также мы следим за сообщениями в этом чате и c удовольствием ответим на твои вопросы здесь!";
        $this->vkApi_keyboardSend($this->user_id, $message, [[self::BTN_HOME]], $one_time=TRUE);
    }
    
    private function info_2org() {
        $eol="\r\n";
        $message  = "Sunfox Team управляет деятельностью сообществ Einherjar и «Викинги Вирумаа». Реквизиты организации:" . $eol . $eol;
        $message .= "MTÜ Sunfox Team" . $eol . "рег. номер: 80415755" . $eol . $eol;
        $message .= "Наш адрес:" . $eol . "Tamme tn. 17, Tammiku alevik" . $eol . "Jõhvi vald, 41542" . $eol . "Ida-Viru maakond" . $eol . $eol;
        $message .= "Контактные данные:" . $eol . "+372 55593171" . $eol . "info@sunfox.ee" . $eol . "sunfox.ee";
        $this->vkApi_keyboardSend($this->user_id, $message, [[self::BTN_HOME]], $one_time=TRUE);
    }

    private function events_show() {        
        $events = new \Events;
        $EventsPrepare = $events->listEvents(3);

        $month4date = array(1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля', 5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа', 9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря');
        $eol="\r\n";
        if(is_null($EventsPrepare)){
             $message = "В данный момент мероприятия не запланированы. Извини!";
             $this->vkApi_messagesSend($this->user_id, $message);
        }else{
            $this->vkApi_messagesSend($this->user_id, "Мероприятия Викингов, которые состоятся в ближайшее время:");
            foreach($EventsPrepare as $EventSingle){                
                $message  = $EventSingle['title'] . $eol;
                $message .= "- дата, время: " . date("j", $EventSingle['time']) . " " . $month4date[date("n", $EventSingle['time'])] . " в " . date("H:i", $EventSingle['time']) . $eol;
                $message .= "- место: " . $EventSingle['loc'];
                $this->vkApi_messagesSend($this->user_id, $message);
                $keyboard_prep .= '{"action": {"type": "text","payload": "{\"command\": \"event_reg\", \"param\": \"'.$EventSingle['id'].'\"}","label": "' . date("j", $EventSingle['time']) . "/" . date("m", $EventSingle['time']) . '"},"color": "primary"},';
            }   
            $keyboard_fin = '{"one_time": true,"buttons": [['. rtrim($keyboard_prep, ",") .'],[{"action": {"type": "text","payload": "{\"command\": \"home\"}","label": "Домой"},"color": "default"}]]}';
            return $this->_vkApi_call('messages.send', array(
                'user_id'    => $this->user_id,
                'message'    => "Чтобы зарегистрироваться на мероприятие, пожалуйста, выбери подходящую дату:",
                'keyboard'   => $keyboard_fin
            ));
        }           
    }
    
    private function event_reg() {        
        // $message = "Сейчас функционал недоступен. Извини!";
        $user_data=$this->vkApi_usersGet($this->user_id);       
        $event=new Events();
        $event->register2Event($this->param, $user_data[0]['first_name'], $user_data[0]['last_name'], "ru", "https://vk.me/".$user_data[0]['id']);
        $message = "Спасибо за регистрацию! Наш координатор свяжется с тобой в ближайшее время чтобы уточнить детали. Пожалуйста, включи возможность писать тебе в ЛС, если она отключена в данный момент!";
        $this->vkApi_keyboardSend($this->user_id, $message, [[self::BTN_HOME]], $one_time=TRUE);
    }

}