<?php
/**
 * GET TRAINING DATES FROM YANDEX CALENDAR
 * Данный код собирает данные из календаря VV - Пробные тренировки в Yandex и подготавливает список мероприятий для формы регистрации на главной странице сайта.
 */

session_start();
switch ($_SESSION['native']) {
  case "et":
    $locale="et_EE.UTF-8";
    break;
  default:
    $_SESSION['native']="ru";
    $locale="ru_RU.UTF-8";
    break;
}
setlocale(LC_MESSAGES,  $locale);
$domain = 'VirvikApp';
bindtextdomain($domain, $_SERVER['DOCUMENT_ROOT'] . "/resources/locale");
textdomain($domain);
bind_textdomain_codeset($domain, 'UTF-8');
switch ($_SESSION['native']) {
  case "et":
    $months_titles_array = array(1 => 'jaanuar', 2 => 'veebruar', 3 => 'märts', 4 => 'aprill', 5 => 'mai', 6 => 'juuni', 7 => 'juuli', 8 => 'august', 9 => 'september', 10 => 'oktoober', 11 => 'november', 12 => 'detsember');
    $at_txt=' kell ';
    break;
  default:
    $months_titles_array = array(1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля', 5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа', 9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря');
    $at_txt=' в ';
    break;
}
$yacal_feed_xml = file_get_contents('https://calendar.yandex.ru/export/rss.xml?private_token=c5e4bc75eb27a6d532ce46428a481693f4a0c165&tz_id=Europe/Tallinn&limit=5');
$events_xml=new SimpleXMLElement($yacal_feed_xml);
/*
 * Show next events
 */
echo '<select class="form-control input-xlarge" name="client_training_datetime">';
foreach($events_xml->entry as $entry)
{
    preg_match("'</p><p xmlns=\"\">(.*?)</p>'si", $entry->content, $result);
    $event_location=substr($result[0],24,-4);

    $option_value=base64_encode(json_encode(array('start'=>$entry->start, 'region'=>$entry->title, 'location'=>$event_location)));
    $clear_date_arr=date_parse_from_format('Y-n-j H:i:sP', $entry->start);
    $month_name = $months_titles_array[$clear_date_arr['month']];
    echo '<option value="'.$option_value.'">' . $clear_date_arr['day'] . ' ' . $month_name . $at_txt . $clear_date_arr['hour'] . ':' . $clear_date_arr['minute'] . ' (' . $entry->title . ')</option>';
}
echo '</select>';
