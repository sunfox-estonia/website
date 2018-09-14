<?php
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
    
    // Log settings
    const BOT_LOGS_DIRECTORY = '/var/www/virvik/sunfox.ee/app/controllers'; 
    
    
    protected function _callback_getRequest() {
        error_log(file_get_contents('php://input'), 0);
        return json_decode(file_get_contents('php://input'));
    }
    
    protected function _callback_handleConfirmation() {
        $this->_callback_response(self::CALLBACK_API_CONFIRMATION_TOKEN);
    }

    protected function _callback_okResponse() {
        $this->_callback_response('ok');
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
        error_log("Message sent", 0);
    }
    
    
    public function vkApi_keyboardSend($user_id, $message, $commands=[], $one_time = False) {
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
          'user_id' => $user_id,
        ));
    }
    
    
    private function _vkApi_call($method,$params=array()){
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
        if (isset($response['response']))
            return $response['response'];
        else
            error_log($json, 0);
            throw new Exception("Invalid response for {$method} request");
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
    const BTN_EVENTS = [["command" => 'show_events'], "Смотреть мероприятия", "default"];
    
    public function go(){        
        if (!isset($_REQUEST)) {
          exit;
        }
        $this->callback_handleEvent();
    }
        
    // Chat Requests handler
    public function callback_handleEvent() {
        $request = $this->_callback_getRequest();
        error_log("Payload command recieved", 0);
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
                break;
            //Уход из группы
            case 'group_leave':
                
                break;
            case 'message_new':
                $req_user = $request->object->user_id;  
                $req_peer_id = $request->object->id;  
                $req_payload = ($request->object->payload) ? json_decode($data->object->payload, true) : null;   
                if ($req_payload){
                    
                    break;
                }                    
                $req_msg = mb_strtolower($request->object->body);
                
                if($req_msg == 'начать'|| $req_msg == 'start'){
                    $message = "Рады что ты откликнулся! :) Чтобы познакомиться ближе с Викингами, посети одно из предстоящих наших мероприятий.";
                    $this->vkApi_keyboardSend($req_user, $message, [[self::BTN_EVENTS]], $one_time=TRUE);
                    break;
                }
                
                    
                /* {"type":"message_new","object":
                *    {   "id":430,
                *        "date":1536918642,
                *        "out":0,
                *        "user_id":102501707,
                *        "read_state":0,
                *        "title":"",
                *        "body":"dfg"},
                *   "group_id":85213325,
                *    "secret":"NTuAN9p5bST7pmqW"
                *    }
                */ 
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