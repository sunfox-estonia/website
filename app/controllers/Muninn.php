<?php
// API keys
define('CALLBACK_API_CONFIRMATION_TOKEN', 'a87a1db0'); //Строка для подтверждения адреса сервера из настроек Callback API
define('VK_API_ACCESS_TOKEN', '43c16eee15b83eba742c024c62f55ef752ec428ee09d007b42596d4b0437ceefec1a439f9b0a9a00b77ba'); //Ключ доступа сообщества

// API settings
define('VK_API_VERSION', '5.67');
define('VK_API_ENDPOINT', 'https://api.vk.com/method/');

/*
* Requests types
* Docs: https://vk.com/dev/groups_events
*/
define('CALLBACK_API_EVENT_CONFIRMATION', 'confirmation');
define('CALLBACK_API_EVENT_COMMAND', 'command');
define('CALLBACK_API_EVENT_GROUPLEAVE', 'group_leave');
define('CALLBACK_API_EVENT_GROUPNEW', 'group_join');

/*
* Commands
* Docs by: https://game-tips.ru/it/kak-sdelat-knopki-byistryih-otvetov-keyboard-dlya-botov-v-vk/#php_code
*/
define('BTN_EVENTS', '[["command" => "show_events"], "Мероприятия", "default"]');

/*
 * Based on https://github.com/VKCOM/bot-example-php 
 */
class Muninn {
    
    public function go(){        
        if (!isset($_REQUEST)) {
          exit;
        }
        $this->callback_handleEvent();
    }
    
    // Chat Requests checker
    function callback_handleEvent() {
        $event = $this->_callback_getEvent();
        try {
            switch ($event['type']) {
            //Подтверждение сервера
            case CALLBACK_API_EVENT_CONFIRMATION:
                $this->_callback_handleConfirmation();
                break;
            //Получение нового сообщения
            case CALLBACK_API_EVENT_MESSAGE_NEW:
                $this->_callback_handleMessageNew($event['object']);
                break;
            default:
                $this->_callback_response('Unsupported event');
                break;
            }
        } catch (Exception $e) {
            
        }
        $this->_callback_okResponse();
    }

    function _callback_getEvent() {
        return json_decode(file_get_contents('php://input'), true);
    }

    function _callback_handleConfirmation() {
        $this->_callback_response(CALLBACK_API_CONFIRMATION_TOKEN);
    }

    function _callback_handleMessageNew($data) {
        $user_id = $data['user_id'];
        $this->bot_sendMessage($user_id);
        $this->_callback_okResponse();
    }

    function _callback_okResponse() {
        $this->_callback_response('ok');
    }

    function _callback_response($data) {
        echo $data;
        exit();
    }
    
    function bot_sendMessage($user_id) {
        $users_get_response = $this->vkApi_usersGet($user_id);
        $user = array_pop($users_get_response);
        $msg = "Привет, {$user['first_name']}!";
        $this->vkApi_messagesSend($user_id, $msg);
    }   
    
    function vkApi_messagesSend($peer_id, $message) {
        return _vkApi_call('messages.send', array(
          'peer_id'    => $peer_id,
          'message'    => $message,
        ));
    }
    
    function vkApi_usersGet($user_id) {
        return _vkApi_call('users.get', array(
          'user_id' => $user_id,
        ));
    }
    
    function _vkApi_call($method, $params = array()) {
        $params['access_token'] = VK_API_ACCESS_TOKEN;
        $params['v'] = VK_API_VERSION;
        $query = http_build_query($params);
        $url = VK_API_ENDPOINT.$method.'?'.$query;
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

}
