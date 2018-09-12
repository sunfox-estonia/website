<?php 
// composer autoloader for required packages and dependencies
require_once('lib/autoload.php');

/** @var \Base $f3 */
$f3 = \Base::instance();
//$f3->set('CACHE','folder=tmp/');
$f3->set('CACHE',FALSE);
$f3->set('DEBUG',3);
// F3 autoloader for application business code & templates
$f3->set('AUTOLOAD', 'app/controllers/');
$f3->set('UI','app/templates/');
$f3->set('LOCALES','app/locales/');

$f3->set('gcaptcha_siteKey', '6LeN-CEUAAAAACwqSSN-UKBVCj-MCqmpTU5OXbg2');
$f3->set('gcaptcha_secret', '6LeN-CEUAAAAAJEKcUXmRWmVEDL6bQvJGmT-9xYa');  

$f3->set('ONERROR',function($f3){
    $user_lang = $f3->get('SESSION.native');
    $f3->set('FALLBACK','ru');
    echo Template::instance()->render('error.htm');
});

$f3->route('GET /',
    function($f3) {
        $native = $f3->get('SESSION.native');
        if (!isset($native)) {
            $recognize_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);                
            $user_lang = ($recognize_lang=="ru"||$recognize_lang=="et") ? $recognize_lang : "ru";
            $f3->set('SESSION.native', $user_lang);
        }
        $f3->set('LANGUAGE',$f3->get('SESSION.native'));
        $f3->set('FALLBACK','ru');        
        
        $events = new \Events;
        $EventsPrepare = $events->listEvents(2);
            
        switch ($f3->get('SESSION.native')) {
          case "et":
            $month4date = array(1 => 'jaanuar', 2 => 'veebruar', 3 => 'märts', 4 => 'aprill', 5 => 'mai', 6 => 'juuni', 7 => 'juuli', 8 => 'august', 9 => 'september', 10 => 'oktoober', 11 => 'november', 12 => 'detsember');
            break;
          default:
            $month4date = array(1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля', 5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа', 9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря');
            break;
        }
        
        if(is_null($EventsPrepare)){
             $f3->set('events', null);
        }else{
            $cr = 0;
            foreach($EventsPrepare as $EventSingle){
                $events4web[$cr] = array (
                    'id' => $cr,
                    'title' => $EventSingle['title'],
                    'time_timeonly' => date("H:i", $EventSingle['time']), // 12:00
                    'time_dayonly' => date("j", $EventSingle['time']), // 1-31
                    'time_monthonly_number' => date("m", $EventSingle['time']), // 01-12
                    'time_monthonly_word' => $month4date[date("n", $EventSingle['time'])], // января
                    'time_daymonth' => date("j", $EventSingle['time']) . "/" . date("m", $EventSingle['time']), // 1/08
                    'location' => $EventSingle['loc']
                );
                $cr++;
            }
        }
        
        $f3->set('events', $events4web);        
        $f3->set('user_lang',$f3->get('SESSION.native'));
        echo Template::instance()->render('homepage.htm');
    }
);

$f3->route('GET /lang/@language',
    function($f3,$params) {
        $set_lang = $params['language'];
        $user_lang = ($set_lang=="ru"||$set_lang=="et") ? $set_lang : "ru";
        $f3->set('SESSION.native', $user_lang);
        $f3->reroute('/');
    }
);

$f3->route('GET /event/@id', 
    function ($f3,$params){
        $f3->set('LANGUAGE',$f3->get('SESSION.native'));
        $events = new \Events;
        $EventsPrepare = $events->listEvents(2);
        
        $event_id = $params['id'];

        $event4web = array(
            'id' => $EventsPrepare[$event_id]['id'],
            'title' => $EventsPrepare[$event_id]['title'],
            'time_timeonly' => date("H:i", $EventsPrepare[$event_id]['time']), // 12:00
            'time_dayonly' => date("j", $EventsPrepare[$event_id]['time']), // 1-31
            'time_monthonly_number' => date("m", $EventsPrepare[$event_id]['time']), // 01-12
            'time_daymonth' => date("j", $EventsPrepare[$event_id]['time']) . "/" . date("m", $EventsPrepare[$event_id]['time']), // 1/08
            'location' => $EventsPrepare[$event_id]['loc']
        );
        $f3->set('event', $event4web);
        echo Template::instance()->render('modals/ModalEventAuth.htm');
    } 
);

$f3->route('GET /actions/register2event/@id',
    function($f3,$params) {
    $events = new \Events;
    $EventsPrepare = $events->listEvents(2);    
    $event_id = $params['id'];

    $event4web = array(
        'id' => $EventsPrepare[$event_id]['id'],
        'title' => $EventsPrepare[$event_id]['title'],
        'time_timeonly' => date("H:i", $EventsPrepare[$event_id]['time']), // 12:00
        'time_dayonly' => date("j", $EventsPrepare[$event_id]['time']), // 1-31
        'time_monthonly_number' => date("m", $EventsPrepare[$event_id]['time']), // 01-12
        'time_daymonth' => date("j", $EventsPrepare[$event_id]['time']) . "/" . date("m", $EventsPrepare[$event_id]['time']), // 1/08
        'location' => $EventsPrepare[$event_id]['loc'],
        'description' => $EventsPrepare[$event_id]['descr'],
    );
        
    $client_firstname = $f3->get('GET.first_name');
    $client_lastname = $f3->get('GET.last_name');
    if($f3->get('GET.uid')){
        $client_profile = 'https://vk.me/' . $f3->get('GET.uid');
    } else {
        $client_profile = 'https://telegram.me/' . $f3->get('GET.username');
    }
        
    $msg_text = ""; 

    $msg_html = '<html>';
    $msg_html .= '<p>Привет!</p><p>Поступила новая заявка на участие в мероприятии <strong>' . $event4web['time_daymonth'] . ' - ' . $event4web['title'] . '</strong></p>';
    $msg_html .= '<p>Заявка отправлена посетителем сайта Sunfox.ee:';
    $msg_html .= '<br>- имя, фамилия: <strong>'.$client_firstname.' '.$client_lastname . '</strong>';
    $msg_html .= '<br>- родной язык: '.$f3->get('SESSION.native');
    $msg_html .= '<br>- профиль: '. $client_profile . '</p>';
    $msg_html .= '<p>Это письмо отправлено ботом Sunfox.ee. Отвечать на него не следует.</p>';
    $msg_html .= '</body></html>';
    
    $hash=uniqid(NULL,TRUE);
    $smtp = new SMTP ('smtp.yandex.ru', '465', 'ssl', 'muninn@sunfox.ee', 'c24BTNv7qMavXc4Q' );
    $smtp->set('From', '"Sunfox.ee Bot" <muninn@sunfox.ee>');
    $smtp->set('To', '"Sunfox Team" <victor@sunfox.ee>');
    $smtp->set('Content-Type', 'multipart/alternative; boundary="'.$hash.'"');    
    $smtp->set('Subject', 'Уведомление с сайта sunfox.ee');
        
    $eol="\r\n";
    $msg_body  = '--'.$hash.$eol;
    $msg_body .= 'Content-Type: text/plain; charset=UTF-8'.$eol;
    $msg_body .= $msg_text.$eol.$eol;
    $msg_body .= '--'.$hash.$eol;
    $msg_body .= 'Content-Type: text/html; charset=UTF-8'.$eol.$eol;
    $msg_body .= $msg_html.$eol;

    $sent = $smtp->send($msg_body, TRUE);
        
    if($sent) {
        $f3->set('event', $event4web);    
        echo Template::instance()->render('joinus/register2event.htm');
    } else {
        $logger = new \Log('/app/log/sfx-app.log');
        $logger->write(date("Y-m-d H:i:s") . ' ' . 'Email sending error (Sunfox.ee -> info@sunfox.ee, Уведомление с сайта sunfox.ee)');
    }
});

$f3->route('POST /actions/message4us',
    function($f3) {
        // Recieve data
        $msg_from = $f3->get('POST.message_from');
        $msg_text = $f3->get('POST.message_text');
        $msg_captcha = $f3->get('POST.g-recaptcha-response');
        
        $recaptcha = new \ReCaptcha\ReCaptcha($f3->get('gcaptcha_secret'));
        $resp = $recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])->verify($msg_captcha, $_SERVER['REMOTE_ADDR']);
        if ($resp->isSuccess()){
            // If the response is a success, that's it!
            $Mail = new PHPMailer\PHPMailer\PHPMailer;
            $Mail->From     = $msg_from;
            $Mail->FromName = 'Посетитель sunfox.ee';
            $Mail->CharSet  = "utf-8";
            $Mail->Subject  = 'Сообщение с sunfox.ee';
            $Mail->Body     = $msg_text;
            $Mail->AddAddress('info@sunfox.ee');
            if(!$Mail->Send()) {
              $return = "false";
            } else {
              $return = "true";
            }
        } else {
            $return = "false";
        }
        echo $return;	
});


$f3->run();