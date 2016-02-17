<?php
/**
 * GET TRAINING DATES FROM YANDEX CALENDAR
 * Данный код собирает данные из календаря VV - Пробные тренировки в Yandex и подготавливает список мероприятий для формы регистрации на главной странице сайта.
 */
$months_titles_array = array(1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля', 5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа', 9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря');
$yacal_feed_xml = file_get_contents('https://calendar.yandex.ru/export/rss.xml?private_token=c5e4bc75eb27a6d532ce46428a481693f4a0c165&tz_id=Europe/Tallinn&limit=5');
$events_xml=new SimpleXMLElement($yacal_feed_xml);
/*
 * Show next events
 */
echo '<select class="form-control input-xlarge" name="client_training_datetime">';
foreach($events_xml->entry as $entry)
{
    $clear_date_arr=date_parse_from_format('Y-n-j H:i:sP', $entry->start);
    $month_name = $months_titles_array[$clear_date_arr['month']];
    echo '<option value="' . $clear_date_arr['day'] . ' ' . $month_name . ' в ' . $clear_date_arr['hour'] . ':' . $clear_date_arr['minute'] . ' (' . $entry->title . ')">' . $clear_date_arr['day'] . ' ' . $month_name . ' в ' . $clear_date_arr['hour'] . ':' . $clear_date_arr['minute'] . ' (' . $entry->title . ')</option>';
}
echo '</select>';
    
    
