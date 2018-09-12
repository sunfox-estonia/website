<?php
require_once('../../lib/autoload.php');

define('BOT_BASE_DIRECTORY', '/var/www');
define('BOT_LOGS_DIRECTORY', BOT_BASE_DIRECTORY.'/logs');
define('BOT_IMAGES_DIRECTORY', BOT_BASE_DIRECTORY.'/static');
define('BOT_AUDIO_DIRECTORY', BOT_BASE_DIRECTORY.'/audio');
define('CALLBACK_API_CONFIRMATION_TOKEN', 'bb112c41'); //Строка для подтверждения адреса сервера из настроек Callback API
define('VK_API_ACCESS_TOKEN', '016cb55230eb83e34f620389s149345d975bc12e2fb6f30e0a833a51d345d0d123c3dc0abc1c864036sdf989fb8345'); //Ключ доступа сообщества
define('YANDEX_API_KEY', '30e3213440-61233-1294-b3415-471212369886'); //Ключ для доступа к Yandex Speech Kit
define('VK_API_VERSION', '5.67'); //Используемая версия API
define('VK_API_ENDPOINT', 'https://api.vk.com/method/');

/*
 * Based on https://github.com/VKCOM/bot-example-php
 */
class Muninn extends vkapi {
    

}

class vkapi{    
    function vkApi_messagesSend($peer_id, $message, $attachments = array()) {
        return _vkApi_call('messages.send', array(
          'peer_id'    => $peer_id,
          'message'    => $message,
          'attachment' => implode(',', $attachments)
        ));
    }
    
    function vkApi_usersGet($user_id) {
        return _vkApi_call('users.get', array(
          'user_id' => $user_id,
        ));
    }
    
    function vkApi_photosGetMessagesUploadServer($peer_id) {
        return _vkApi_call('photos.getMessagesUploadServer', array(
          'peer_id' => $peer_id,
        ));
    }
    
    function vkApi_photosSaveMessagesPhoto($photo, $server, $hash) {
        return _vkApi_call('photos.saveMessagesPhoto', array(
          'photo'  => $photo,
          'server' => $server,
          'hash'   => $hash,
        ));
    }
    
    function vkApi_docsGetMessagesUploadServer($peer_id, $type) {
        return _vkApi_call('docs.getMessagesUploadServer', array(
          'peer_id' => $peer_id,
          'type'    => $type,
        ));
    }
    
    function vkApi_docsSave($file, $title) {
        return _vkApi_call('docs.save', array(
          'file'  => $file,
          'title' => $title,
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
    
    function vkApi_upload($url, $file_name) {
        if (!file_exists($file_name)) {
          throw new Exception('File not found: '.$file_name);
        }
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array('file' => new CURLfile($file_name)));
        $json = curl_exec($curl);
        $error = curl_error($curl);
        if ($error) {
          log_error($error);
          throw new Exception("Failed {$url} request");
        }
        curl_close($curl);
        $response = json_decode($json, true);
        if (!$response) {
          throw new Exception("Invalid response for {$url} request");
        }
        return $response;
    }
}
