<?php
class Events {
    
    public function listEvents($limit) {
        $xml_raw=$this->getYacalData($records_limit=$limit);
        $events_array=$this->factoryEvents($xml_raw);
        return $events_array;
    }
    
    private function getYacalData($records_limit="2", $token="5261b9692aa4d48ad40f4767a13eb6ec4319a1ba", $timezone="Europe/Tallinn"){
        $yacal_feed = file_get_contents('https://calendar.yandex.ru/export/rss.xml?private_token=' . $token . '&tz_id=' . $timezone. '&limit=' . $records_limit);
        $xml_raw = new SimpleXMLElement($yacal_feed);
        return $xml_raw;        
    }
    
    private function factoryEvents($data_source) {
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

}
