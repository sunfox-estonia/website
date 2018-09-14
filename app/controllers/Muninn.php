<?php
/*
 * Based on https://github.com/VKCOM/bot-example-php 
 */
class vkcom {
    // API keys
    const CALLBACK_API_CONFIRMATION_TOKEN = 'a87a1db0'; //Строка для подтверждения адреса сервера из настроек Callback API
    const VK_API_ACCESS_TOKEN = '43c16eee15b83eba742c024c62f55ef752ec428ee09d007b42596d4b0437ceefec1a439f9b0a9a00b77ba'; //Ключ доступа сообщества

    // API settings
    const VK_API_VERSION = '5.67';
    const VK_API_ENDPOINT = 'https://api.vk.com/method/';
    
    // Log settings
    const BOT_LOGS_DIRECTORY = '.'; 
    
    
    protected function _callback_getRequest() {
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
    protected function vkApi_messagesSend($peer_id, $message) {
        return _vkApi_call('messages.send', array(
          'peer_id'    => $peer_id,
          'message'    => $message,
        ));
    }
    
    /* Get iser data by id */
    protected function vkApi_usersGet($user_id) {
        return _vkApi_call('users.get', array(
          'user_id' => $user_id,
        ));
    }
    
    private function _vkApi_call($method, $params = array()) {
        $params['access_token'] = self::VK_API_ACCESS_TOKEN;
        $params['v'] = self::VK_API_VERSION;
        $query = http_build_query($params);
        $url = self::VK_API_ENDPOINT.$method.'?'.$query;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($curl);
        $error = curl_error($curl);
        if ($error) {
          log_error($error);
          throw new Exception("Failed {$method} request");
        }
        curl_close($curl);
        $response = json_decode($json, true);
        if (!$response || !isset($response['response'])) {
          log_error($json);
          throw new Exception("Invalid response for {$method} request");
        }
        return $response['response'];
    }    
    
    protected function log_msg($message) {
        if (is_array($message)) {
          $message = json_encode($message);
        }
        _log_write('[INFO] ' . $message);
    }
    
    protected function log_error($message) {
        if (is_array($message)) {
          $message = json_encode($message);
        }
        _log_write('[ERROR] ' . $message);
    }
    
    protected function _log_write($message) {
        $trace = debug_backtrace();
        $function_name = isset($trace[2]) ? $trace[2]['function'] : '-';
        $mark = date("H:i:s") . ' [' . $function_name . ']';
        $log_name = self::BOT_LOGS_DIRECTORY.'/munin_log.txt';
        file_put_contents($log_name, $mark . " : " . $message . "\n", FILE_APPEND);
    }
}

/*
 * Muninn chatbot main class
 * 
 * Community events: https://vk.com/dev/groups_events
 * Keyboard: https://vk.com/dev/bots_docs_3?f=4.%2B%D0%9A%D0%BB%D0%B0%D0%B2%D0%B8%D0%B0%D1%82%D1%83%D1%80%D1%8B%2B%D0%B4%D0%BB%D1%8F%2B%D0%B1%D0%BE%D1%82%D0%BE%D0%B2
 * Additional docs & examples: https://game-tips.ru/it/kak-sdelat-knopki-byistryih-otvetov-keyboard-dlya-botov-v-vk/#php_code
 */
define('BTN_EVENTS', '[["command" => "show_events"], "Мероприятия", "default"]');

class Muninn extends vkcom {
    
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
                $user_id = $request->object->user_id;                
                $message = "Благодарим за интерес к Викингам Вирумаа! Напиши, если желаешь посетить пробное занятие или задать вопросы :) ";
                $this->vkApi_messagesSend($user_id, $message);
                break;
            //Уход из группы
            case 'group_leave':
                
                break;
            case 'message_new':
                $this->_log_write($request->object);
                //$user_id = $request->object->user_id;                
                //$message = "Благодарим за за сообщение! Мы скоро ответим!";
                //$this->vkApi_messagesSend($user_id, $message);
                
                //$user_id = $request->object->from_id; 
                //$payload = ($request->object->payload) ? json_decode($data->object->payload, true) : null;                           
                //$message = "Данный функционал (" . var_dump($payload)  . "), к сожалению, не доступен.";
                //$this->vkApi_messagesSend($user_id, $message);
                break;
            default:
                $this->_callback_response('Unsupported event');
                break;
            }
        } catch (Exception $e) {
            log_error($e);
        }
        $this->_callback_okResponse();
    }
    
    protected function get_user($object){
        $users_get_response = $this->vkApi_usersGet($object['user_id']);
        $user_data = array_pop($users_get_response);
        return $user_data;
    }   
    
}