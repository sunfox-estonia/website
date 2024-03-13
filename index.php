<?php
// composer autoloader for required packages and dependencies
require_once('lib/autoload.php');
require_once('config.php');

/** @var \Base $f3 */
$f3 = \Base::instance();
//$f3->set('CACHE','folder=tmp/');
$f3->set('CACHE', FALSE);
$f3->set('DEBUG', 3);
// F3 autoloader for application business code & templates
$f3->set('AUTOLOAD', 'app/controllers/');
$f3->set('UI', 'app/templates/');
$f3->set('LOCALES', 'app/locales/');
$f3->set('DB', new DB\SQL('mysql:host=localhost;port=3306;dbname=' . HUGINN_DBNAME, HUGINN_DBUSER, HUGINN_DBPASS));

$f3->set('gcaptcha_siteKey', GCAPTCHA_KEY);
$f3->set('gcaptcha_secret', GCAPTCHA_SECRET);

$f3->set('ONERROR', function ($f3) {
    // $f3->set('LANGUAGE', $f3->get('SESSION.native'));
    // $user_lang = $f3->get('SESSION.native');
    // $f3->set('FALLBACK', 'ru');
    echo $f3->get('ERROR.code') . '<br>';
    echo $f3->get('ERROR.text') . '<br>';
    echo $f3->get('ERROR.trace') . '<br>';
    // echo Template::instance()->render('error.htm');
});

$f3->route(
    'GET /',
    function ($f3) {
        // Set language
        $native = $f3->get('SESSION.native');
        if (!isset($native)) {
            $recognize_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            $user_lang = ($recognize_lang == "ru" || $recognize_lang == "et") ? $recognize_lang : "ru";
            $f3->set('SESSION.native', $user_lang);
        }
        $f3->set('LANGUAGE', $f3->get('SESSION.native'));
        $f3->set('FALLBACK', 'ru');

        // Get Discrod data and list rating
        $drd_conn = curl_init();

        curl_setopt_array($drd_conn, array(
            CURLOPT_URL            => 'https://discordapp.com/api/v7/guilds/' . HUGINN_GUILDID . '/members?limit=100',
            CURLOPT_HTTPHEADER     => array('Authorization: Bot ' . HUGINN_BOTTOKEN),
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_VERBOSE        => 1
        ));
        $discord_guild = curl_exec($drd_conn);
        $db_members = $f3->get('DB')->exec('SELECT `uid`, `drd_users`.`level`, IFNULL(`lvl_count`,0) as `lvl_count`, `lvl_sum`, `coins` 
        FROM `drd_users` 
            LEFT JOIN (
                SELECT `user_id`, `sub_achievements`.`level`, COUNT(*) AS `lvl_count`
                FROM `drd_usr_ach`
                LEFT JOIN (SELECT * FROM `drd_achievements`) `sub_achievements` ON `sub_achievements`.`code`=`drd_usr_ach`.`ach_id`
                GROUP BY `user_id`, `sub_achievements`.`level`
                ) 
            `achievements` ON `drd_users`.`uid`=`achievements`.`user_id` AND `drd_users`.`level`=`achievements`.`level`
            LEFT JOIN (
                SELECT `level`, COUNT(*) AS `lvl_sum`
                FROM `drd_achievements`	
                GROUP BY `level`) 
            `grades` ON `drd_users`.`level`=`grades`.`level`
        ORDER BY `level` DESC, lvl_count DESC, coins DESC LIMIT 15'); // Rows counter
        $f3->get('DB')->exec('SELECT * FROM `drd_achievements` WHERE `community` = "viruviking"');

        $cr = 0;
        foreach ($db_members as $m) {
            if (in_array_r($m['uid'], $discord_guild)) {
                if ($cr == 5) break; // Rows counter
                curl_setopt_array($drd_conn, array(
                    CURLOPT_URL            => 'https://discordapp.com/api/v7/users/' . $m['uid'],
                    CURLOPT_HTTPHEADER     => array('Authorization: Bot ' . HUGINN_BOTTOKEN),
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_FOLLOWLOCATION => 1,
                    CURLOPT_SSL_VERIFYPEER => 0,
                ));
                $drd_response = curl_exec($drd_conn);
                $discord_user = json_decode($drd_response, true);
                $discord_user['avatar'] = (empty($discord_user['avatar'])) ? 'false' : $discord_user['avatar'];


                $members4web[$cr] = array(
                    'id' => $m['uid'],
                    'avatar' => $discord_user['avatar'],
                    'nickname' => $discord_user['username'],
                    'level' => $m['level'],
                    'a_count' => $m['lvl_count'],
                    'a_sum' => $m['lvl_sum'],
                    'coins' => $m['coins']
                );
                $cr++;
            }
        }
        curl_close($drd_conn);

        // Get Youtube videos from Viking Stories playlist
        $yt_playlist_code = 'PLTdE5E2DVy7xgkQvJ4O_j9siSWWhVsluW';
        $api_url = file_get_contents('https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=25&playlistId=' . $yt_playlist_code . '&key=' . YT_API);
        $yt_playlist_prep = json_decode($api_url, false);
        $c = 0;
        foreach ($yt_playlist_prep->items as $item) {
            $yt_playlist[$c] = [
                'video_img' => $item->snippet->thumbnails->maxres->url,
                'video_title' => $item->snippet->title,
                'video_descr' => $item->snippet->description,
                'video_id' => $item->snippet->resourceId->videoId,
                'video_date' => date('d.m.Y', strtotime($item->snippet->publishedAt))
            ];
            $c++;
        }

        $f3->set('members', $members4web);
        $f3->set('yt_playlist', $yt_playlist);
        $f3->set('user_lang', $f3->get('SESSION.native'));
        echo Template::instance()->render('homepage.htm');
    }
);

$f3->route(
    'GET /lang/@language',
    function ($f3, $params) {
        $set_lang = $params['language'];
        $user_lang = ($set_lang == "ru" || $set_lang == "et") ? $set_lang : "ru";
        $f3->set('SESSION.native', $user_lang);
        $f3->reroute('/');
    }
);

$f3->route(
    'POST /actions/message4us',
    function ($f3) {
        // Recieve data
        $msg_from = $f3->get('POST.message_from');
        $message = $f3->get('POST.message_text');
        $msg_captcha = $f3->get('POST.g-recaptcha-response');

        $recaptcha = new \ReCaptcha\ReCaptcha($f3->get('gcaptcha_secret'));
        $resp = $recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])->verify($msg_captcha, $_SERVER['REMOTE_ADDR']);
        if ($resp->isSuccess()) {
            $msg_text = "";

            $msg_html = '<html>';
            $msg_html .= '<p>Привет!</p><p>Поступило новое сообщение с сайта Sunfox.ee</p>';
            $msg_html .= '<p><u>Отправитель</u>: ' . $msg_from;
            $msg_html .= '<br><u>Текст сообщения</u>:';
            $msg_html .= '<br>' .  $message . '</p>';
            $msg_html .= '<p>Это письмо отправлено ботом Sunfox.ee. Отвечать на него не следует.</p>';
            $msg_html .= '</body></html>';

            $hash = uniqid(NULL, TRUE);
            $smtp = new SMTP(MAIL_SERVER, '465', 'ssl', MAIL_USER, MAIL_PASSWORD);
            $smtp->set('From', '"Sunfox.ee Bot" <muninn@sunfox.ee>');
            $smtp->set('To', '"Sunfox Team" <victor@sunfox.ee>');
            $smtp->set('Reply-To', '"Sunfox.ee user" <' . $msg_from . '>');
            $smtp->set('Content-Type', 'multipart/alternative; boundary="' . $hash . '"');
            $smtp->set('Subject', 'VV - Сообщение с сайта Sunfox.ee');

            $eol = "\r\n";
            $msg_body  = '--' . $hash . $eol;
            $msg_body .= 'Content-Type: text/plain; charset=UTF-8' . $eol;
            $msg_body .= $msg_text . $eol . $eol;
            $msg_body .= '--' . $hash . $eol;
            $msg_body .= 'Content-Type: text/html; charset=UTF-8' . $eol . $eol;
            $msg_body .= $msg_html . $eol;

            $sent = $smtp->send($msg_body, TRUE);
            if (!$sent) {
                $return = "false";
                error_log(date("Y-m-d H:i:s") . ' ' . 'Email sending error (Sunfox.ee -> info@sunfox.ee, Сообщение с сайта Sunfox.ee)', 0);
            } else {
                $return = "true";
            }
        } else {
            $return = "false";
        }
        echo $return;
    }
);

/* Profile routes
 * Profile system is based on Discord user profiles.
 *
 */

$f3->route('GET /profile', function ($f3) {
    if ($f3->get('SESSION.discord_token')) {
        $OAuth_UserData = new Web\OAuth2();
        $UserData = $OAuth_UserData->request('https://discord.com/api/users/@me', 'GET', $f3->get('SESSION.discord_token'));
        var_dump($UserData);
        // echo Template::instance()->render('profile/profile.htm');
    } else {
        $f3->reroute('/profile/signin');
    }
});

$f3->route('GET /profile/signin', function ($f3) {
    if ($f3->get('SESSION.discord_token')) {
        $f3->reroute('/profile');
    } else {
        $OAuth = new Web\OAuth2();
        $OAuth->set('client_id', DISCORD_CLIENT_ID);
        $OAuth->set('scope', 'identify');
        $OAuth->set('response_type', 'code');
        $OAuth->set('access_type', 'online');
        $OAuth->set('approval_prompt', 'auto');
        $OAuth->set('redirect_uri', $f3->SCHEME . '://' . $_SERVER['HTTP_HOST'] . '/profile/oauth/discord');

        $f3->set('discord_auth_url', $OAuth->uri('https://discord.com/api/oauth2/authorize', true));
        echo Template::instance()->render('profile/signin.htm');
    }
});

$f3->route('GET /profile/signout', function ($f3) {
    if ($f3->get('SESSION.discord_token')) {
        $f3->reroute('/profile/signin');
    } else {
        $OAuth_Logout = new Web\OAuth2();
        $OAuth_Logout->set('client_id', DISCORD_CLIENT_ID);
        $OAuth_Logout->set('client_secret', DISCORD_CLIENT_SECRET);
        $OAuth_Logout->set('token', $f3->get('SESSION.discord_token'));
        $OAuth_Logout->request('https://discord.com/api/oauth2/token/revoke', 'POST');
        $f3->clear('SESSION.discord_token');
        $f3->reroute('/profile');
    }
});

$f3->route('GET /profile/oauth/discord', function ($f3) {
    if ($f3->get('GET.code')) {
        $OAuth_Token = new Web\OAuth2();
        $OAuth_Token->set('client_id', DISCORD_CLIENT_ID);
        $OAuth_Token->set('client_secret', DISCORD_CLIENT_SECRET);
        $OAuth_Token->set('grant_type', 'authorization_code');
        $OAuth_Token->set('code', $f3->get('GET.code'));
        $OAuth_Token->set('redirect_uri', $f3->SCHEME . '://' . $_SERVER['HTTP_HOST'] . '/profile/oauth/discord');

        $token = $OAuth_Token->request('https://discord.com/api/oauth2/token', 'POST');
        $f3->set('SESSION.discord_token', $token["access_token"]);
        $f3->reroute('/profile');
    } else {
        $f3->reroute('/profile/signin');
    }
});

$f3->route('GET /profile/lang/@language', function ($f3, $params) {
    $set_lang = $params['language'];
    $user_lang = ($set_lang == "ru" || $set_lang == "et" || $set_lang == "en") ? $set_lang : "ru";
    $f3->set('SESSION.native', $user_lang);
    $f3->reroute('/profile');
});

$f3->run();

function in_array_r($item, $array)
{
    return preg_match('/"' . preg_quote($item, '/') . '"/i', $array);
}
