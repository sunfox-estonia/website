<?php 
// composer autoloader for required packages and dependencies
require_once('lib/autoload.php');
require_once('config.php');

/** @var \Base $f3 */
$f3 = \Base::instance();
//$f3->set('CACHE','folder=tmp/');
$f3->set('CACHE',FALSE);
$f3->set('DEBUG',3);
// F3 autoloader for application business code & templates
$f3->set('AUTOLOAD', 'app/controllers/');
$f3->set('UI','app/templates/');
$f3->set('LOCALES','app/locales/');
$f3->set('DB',new DB\SQL('mysql:host=localhost;port=3306;dbname='.HUGINN_DBNAME,HUGINN_DBUSER,HUGINN_DBPASS));

$f3->set('gcaptcha_siteKey', GCAPTCHA_KEY);
$f3->set('gcaptcha_secret', GCAPTCHA_SECRET);  

$f3->set('ONERROR',function($f3){
    $f3->set('LANGUAGE',$f3->get('SESSION.native'));
    $user_lang = $f3->get('SESSION.native');
    $f3->set('FALLBACK','ru');
    echo Template::instance()->render('error.htm');
});

$f3->route('GET /',
    function($f3) {
        // Set language
        $native = $f3->get('SESSION.native');
        if (!isset($native)) {
            $recognize_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);                
            $user_lang = ($recognize_lang=="ru"||$recognize_lang=="et") ? $recognize_lang : "ru";
            $f3->set('SESSION.native', $user_lang);
        }
        $f3->set('LANGUAGE',$f3->get('SESSION.native'));
        $f3->set('FALLBACK','ru');        
        
        // Get Discrod data and list rating
        $drd_conn = curl_init();
        
        curl_setopt_array($drd_conn, array(
            CURLOPT_URL            => 'https://discordapp.com/api/v7/guilds/'.HUGINN_GUILDID.'/members?limit=100',
            CURLOPT_HTTPHEADER     => array('Authorization: Bot '.HUGINN_BOTTOKEN),
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_VERBOSE        => 1
        ));
        $discord_guild = curl_exec($drd_conn);      
        $db_members = $f3->get('DB')->exec('SELECT `uid`, `level`, `count`, `coins` FROM `drd_users` LEFT JOIN (SELECT `user_id`, COUNT(*) AS `count` FROM `drd_usr_ach` GROUP BY `user_id`) `achievements` ON `drd_users`.`uid`=`achievements`.`user_id` ORDER BY `count` DESC LIMIT 10'); // Rows counter
        $f3->get('DB')->exec('SELECT * FROM `drd_achievements` WHERE `community` = "viruviking"');
        $ach_count = $f3->get('DB')->count();
            
        $cr=0;
        foreach($db_members as $m){
            if(in_array_r($m['uid'],$discord_guild)){
                if($cr==5) break; // Rows counter
                curl_setopt_array($drd_conn, array(
                    CURLOPT_URL            => 'https://discordapp.com/api/v7/users/'.$m['uid'],
                    CURLOPT_HTTPHEADER     => array('Authorization: Bot '.HUGINN_BOTTOKEN),
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_FOLLOWLOCATION => 1,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ));
                $drd_response = curl_exec($drd_conn);       
                $discord_user = json_decode($drd_response, true);
                $discord_user['avatar'] = (empty($discord_user['avatar'])) ? 'false' : $discord_user['avatar'] ;


                $members4web[$cr] = array(
                    'id' => $m['uid'],
                    'avatar' => $discord_user['avatar'],
                    'nickname' => $discord_user['username'],
                    'level' => $m['level'],
                    'a_count' => $m['count'],
                    'a_sum' => $ach_count,
                    'coins' => $m['coins']
                );
                $cr++;
            }
        }
        curl_close($drd_conn); 

        // Get Instagram posts but from VK.com
        $vk_query = file_get_contents("https://api.vk.com/method/photos.get?owner_id=-85213325&album_id=276383442&extended=1&need_covers=1&photo_sizes=1&access_token=" . VKAPP_TOKEN . "&v=5.126");
        $vk_result = json_decode($vk_query,false);

        $ig_logo = $vk_result->response->items{0}->sizes{0}->url;
        $ig_posts = array();

        for ($i = 0; $i <= 9; $i++) {
            $cntr = $i+1;
            $photo_img_sm = $vk_result->response->items{$cntr}->sizes{6}->url;
            $photo_img_lg = $vk_result->response->items{$cntr}->sizes{7}->url;
            $photo_txt = $vk_result->response->items{$cntr}->text;
            $photo_date = $vk_result->response->items{$cntr}->date;
            $photo_likes = $vk_result->response->items{$cntr}->likes->count;

            $ig_posts[$i] = array('img_sm'=>$photo_img_sm,'img_lg'=>$photo_img_lg, 'txt'=>$photo_txt,'date'=> date('d.m.Y', $photo_date),'likes'=>$photo_likes);
        }
        $f3->set('members', $members4web);  
        $f3->set('ig_logo', $ig_logo); 
        $f3->set('ig_photos', $ig_posts);    
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

$f3->route('POST /actions/message4us',
    function($f3) {
        // Recieve data
        $msg_from = $f3->get('POST.message_from');
        $message = $f3->get('POST.message_text');
        $msg_captcha = $f3->get('POST.g-recaptcha-response');
        
        $recaptcha = new \ReCaptcha\ReCaptcha($f3->get('gcaptcha_secret'));
        $resp = $recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])->verify($msg_captcha, $_SERVER['REMOTE_ADDR']);
        if ($resp->isSuccess()){   
            $msg_text = ""; 

            $msg_html = '<html>';
            $msg_html .= '<p>Привет!</p><p>Поступило новое сообщение с сайта Sunfox.ee</p>';
            $msg_html .= '<p><u>Отправитель</u>: ' . $msg_from;
            $msg_html .= '<br><u>Текст сообщения</u>:';
            $msg_html .= '<br>' .  $message . '</p>';
            $msg_html .= '<p>Это письмо отправлено ботом Sunfox.ee. Отвечать на него не следует.</p>';
            $msg_html .= '</body></html>';

            $hash=uniqid(NULL,TRUE);
            $smtp = new SMTP (MAIL_SERVER, '465', 'ssl', MAIL_USER, MAIL_PASSWORD );
            $smtp->set('From', '"Sunfox.ee Bot" <muninn@sunfox.ee>');
            $smtp->set('To', '"Sunfox Team" <victor@sunfox.ee>');
            $smtp->set('Reply-To', '"Sunfox.ee user" <'.$msg_from.'>');
            $smtp->set('Content-Type', 'multipart/alternative; boundary="'.$hash.'"');    
            $smtp->set('Subject', 'VV - Сообщение с сайта Sunfox.ee');

            $eol="\r\n";
            $msg_body  = '--'.$hash.$eol;
            $msg_body .= 'Content-Type: text/plain; charset=UTF-8'.$eol;
            $msg_body .= $msg_text.$eol.$eol;
            $msg_body .= '--'.$hash.$eol;
            $msg_body .= 'Content-Type: text/html; charset=UTF-8'.$eol.$eol;
            $msg_body .= $msg_html.$eol;

            $sent = $smtp->send($msg_body, TRUE);
            if(!$sent) {
              $return = "false";
              error_log(date("Y-m-d H:i:s") . ' ' . 'Email sending error (Sunfox.ee -> info@sunfox.ee, Сообщение с сайта Sunfox.ee)', 0);
            } else {
              $return = "true";
            }
        } else {
            $return = "false";
        }
        echo $return;	
});

$f3->run();

function in_array_r($item , $array){
    return preg_match('/"'.preg_quote($item, '/').'"/i' , $array);
}
