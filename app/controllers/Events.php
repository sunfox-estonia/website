<?php
require_once('/var/www/virvik/sunfox.ee/lib/autoload.php');

class Events {    
    public function listEvents($limit) {
        $xml_raw=$this->getYacalData($records_limit=$limit);
        $events_array=$this->prepareYacalEvents($xml_raw);
        return $events_array;
    }
    
    private function getYacalData($records_limit="2", $token="5261b9692aa4d48ad40f4767a13eb6ec4319a1ba", $timezone="Europe/Tallinn"){
        $yacal_feed = file_get_contents('https://calendar.yandex.ru/export/rss.xml?private_token=' . $token . '&tz_id=' . $timezone. '&limit=' . $records_limit);
        $xml_raw = new SimpleXMLElement($yacal_feed);
        return $xml_raw;        
    }
    
    private function prepareYacalEvents($data_source) {
        $cr = 0;
        foreach($data_source->entry as $entry){            
            preg_match_all("'<p xmlns=\"\">(.*?)</p>'si",$entry->content, $content_prep);
            preg_match("'</p>(.*?)<p xmlns=\"\">'si", $entry->content, $content_titledescr_prep);
            $content_titledescr=$content_titledescr_prep[1];
            $content_location=substr($content_prep[1][1],8);
            $date_prepare = date_parse_from_format('Y-n-j H:i:sP', $entry->start);
            
            $calendar_array[$cr] = array (
                'id' => $cr,
                'title' => $entry->title,
                'descr' => $content_titledescr,                
                'time' => mktime($date_prepare['hour'],
                                    $date_prepare['minute'],
                                    $date_prepare['second'],
                                    $date_prepare['month'],
                                    $date_prepare['day'],
                                    $date_prepare['year']),                
                'loc' => $content_location,
            );
            $cr++;
        }   
        return $calendar_array;
    }
    
    public function getEvent($id) {
        $limit = $id+1;
        $events_array=$this->listEvents($limit);
        $response_event = array(
            'id' => $events_array[$id]['id'],
            'title' => $events_array[$id]['title'],
            'time_timeonly' => date("H:i", $events_array[$id]['time']), // 12:00
            'time_dayonly' => date("j", $events_array[$id]['time']), // 1-31
            'time_monthonly_number' => date("m", $events_array[$id]['time']), // 01-12
            'time_daymonth' => date("j", $events_array[$id]['time']) . "/" . date("m", $events_array[$id]['time']), // 1/08
            'location' => $events_array[$id]['loc'],
            'description' => $events_array[$id]['descr'],
        );
        return $response_event;
    }
    
    public function register2Event($id, $usr_fname, $usr_lname, $usr_lang, $usr_profile) {
        $f3 = \Base::instance();
       
        $event = $this->getEvent($id);
        
        $msg_text = ""; 

        $msg_html = '<html>';
        $msg_html .= '<p>Привет!</p><p>Поступила новая заявка на участие в мероприятии <strong>' . $event['time_daymonth'] . ' - ' . $event['title'] . '</strong></p>';
        $msg_html .= '<p>Заявка отправлена посетителем сайта Sunfox.ee:';
        $msg_html .= '<br>- имя, фамилия: <strong>'.$usr_fname.' '.$usr_lname . '</strong>';
        $msg_html .= '<br>- родной язык: '.$usr_lang;
        $msg_html .= '<br>- профиль: '. $usr_profile . '</p>';
        $msg_html .= '<p>Это письмо отправлено ботом Sunfox.ee. Отвечать на него не следует.</p>';
        $msg_html .= '</body></html>';

        $hash=uniqid(NULL,TRUE);
        $smtp = new SMTP ('smtp.yandex.ru', '465', 'ssl', 'muninn@sunfox.ee', 'c24BTNv7qMavXc4Q' );
        $smtp->set('From', '"Sunfox.ee Bot" <muninn@sunfox.ee>');
        $smtp->set('To', '"Sunfox Team" <victor@sunfox.ee>');
        $smtp->set('Content-Type', 'multipart/alternative; boundary="'.$hash.'"');    
        $smtp->set('Subject', 'VV - Регистрация на мероприятие ' . $event['time_daymonth']);

        $eol="\r\n";
        $msg_body  = '--'.$hash.$eol;
        $msg_body .= 'Content-Type: text/plain; charset=UTF-8'.$eol;
        $msg_body .= $msg_text.$eol.$eol;
        $msg_body .= '--'.$hash.$eol;
        $msg_body .= 'Content-Type: text/html; charset=UTF-8'.$eol.$eol;
        $msg_body .= $msg_html.$eol;

        $sent = $smtp->send($msg_body, TRUE);

        if($sent) {
            return TRUE;
        } else {
            error_log(date("Y-m-d H:i:s") . ' ' . 'Email sending error (Sunfox.ee -> info@sunfox.ee, Регистрация на сайте sunfox.ee)', 0);
            return FALSE;
        }
        
    }

}
