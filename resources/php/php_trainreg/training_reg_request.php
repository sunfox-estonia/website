<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . "/resources/php/php_plg_mailer/class.phpmailer.php");
/*
$_COOKIE['UserDataTransfer']:

array(11) {
  [0]=>
  object(stdClass)#1 (2) {
    ["name"]=>
    string(15) "client_fullname"
    ["value"]=>
    string(16) "Viktor Litvinkov"
  }
  [1]=>
  object(stdClass)#2 (2) {
    ["name"]=>
    string(10) "client_age"
    ["value"]=>
    string(2) "19"
  }
  [2]=>
  object(stdClass)#3 (2) {
    ["name"]=>
    string(12) "client_email"
    ["value"]=>
    string(19) "riigikogu@gmail.com"
  }
  [3]=>
  object(stdClass)#4 (2) {
    ["name"]=>
    string(10) "client_tel"
    ["value"]=>
    string(12) "+37255593171"
  }
  [4]=>
  object(stdClass)#5 (2) {
    ["name"]=>
    string(18) "client_parent_name"
    ["value"]=>
    string(0) ""
  }
  [5]=>
  object(stdClass)#6 (2) {
    ["name"]=>
    string(17) "client_parent_tel"
    ["value"]=>
    string(0) ""
  }
  [6]=>
  object(stdClass)#7 (2) {
    ["name"]=>
    string(17) "client_first_name"
    ["value"]=>
    string(6) "Viktor"
  }
  [7]=>
  object(stdClass)#8 (2) {
    ["name"]=>
    string(16) "client_last_name"
    ["value"]=>
    string(9) "Litvinkov"
  }
  [8]=>
  object(stdClass)#9 (2) {
    ["name"]=>
    string(13) "client_gender"
    ["value"]=>
    string(4) "male"
  }
  [9]=>
  object(stdClass)#10 (2) {
    ["name"]=>
    string(15) "client_language"
    ["value"]=>
    string(2) "ru"
  }
  [10]=>
  object(stdClass)#11 (2) {
    ["name"]=>
    string(19) "client_profile_link"
    ["value"]=>
    string(40) "https://plus.google.com/+ViktorLitvinkov"
  }
}
*/
if($_COOKIE['UserDataTransfer'] && $_POST['client_training_datetime']){
    $UserData=json_decode($_COOKIE['UserDataTransfer'], TRUE);
    $EventData=base64_decode($_POST['client_training_datetime']);
    $client_training_datetime=json_decode($EventData, TRUE);

    $months_titles_array = array(1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля', 5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа', 9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря');
    $clear_date_arr=date_parse_from_format('Y-n-j H:i:sP', $client_training_datetime['start'][0]);
    $month_name = $months_titles_array[$clear_date_arr['month']];
    $client_training_datetime = $clear_date_arr['day'] . ' ' . $month_name . ' в ' . $clear_date_arr['hour'] . ':' . $clear_date_arr['minute'] . ' (' . $client_training_datetime['region'][0] . ')';

    $TryClientMesg=msgCLient($UserData, $client_training_datetime);
    $TryCoordinatorMesg=msgCoordinator($UserData,$client_training_datetime,$TryClientMesg);
    if ($TryCoordinatorMesg===true){
        $return="true";
    } else {
        $return="false";
    }
    echo $return;
}

function msgCoordinator($UserData,$client_training_datetime,$TryClientMesg){

    $UserDataEncoded=base64_encode($_COOKIE['UserDataTransfer']);
    $mail_html_prepare = '<html><body>';
    $mail_html_prepare .= '<link href="https://viruviking.club/resources/css/virvik_mail.css" rel="stylesheet">';
    $mail_html_prepare .= '<p>Привет!</p><p>Поступила новая заявка на участие в пробной тренировке по историческому фехтованию <i>' . $client_training_datetime . '</i>.</p>';
    $mail_html_prepare .= '<p>Необходимо выполнить следующие действия:<br>- проверить указанный профиль заявителя в социальной сети, убедиться в том, что личные данные (имя, фамилия, возраст) предоставлены верно;<br>- проверить контексты, в которых упоминается имя и/или фамилия заявителя в Сети, сделать это можно при помощи любого поисковика;<br>- в случае, если заявка предоставлена ';
    $mail_html_prepare .= 'несовершеннолетним лицом, необходимо связаться с родителями/опекуном, воспользовавшись указанным номером телефона;<br>- если данные, предоставленные в заявке и/или профиль заявителя в социальной сети вызывают сомнение, заявку следует отклонить.</p>';
    $mail_html_prepare .= '<p>Благодарим за ответственный подход к обработке заявок на участие в тренировках!</p>';
    $mail_html_prepare .= '<p><strong>Заявитель:</strong>';
    $mail_html_prepare .= '<br>Имя, фамилия: '.$UserData[0]['value'];
    $mail_html_prepare .= '<br>Возраст: '.$UserData[1]['value'];
    $mail_html_prepare .= '<br>Родной язык: '.$UserData[9]['value'];
    $mail_html_prepare .= '</p><p>Номер телефона: '.$UserData[3]['value'];
    $client_email_Test = ($TryClientMesg===true) ? '<strong style="color:green;">OK</strong>' : '<strong style="color:red;">ERROR</strong>';
    $mail_html_prepare .= '<br>Адрес электропочты: <a href="mailto:'.$UserData[2]['value'].'">'.$UserData[2]['value'] . '</a> - ' . $client_email_Test;
    if ($UserData[1]['value'] < 18) {
        $mail_html_prepare .= '</p><p><strong>Контактные данные родителя/опекуна:</strong>';
        $mail_html_prepare .= '<br>Имя, отчество: '.$UserData[4]['value'];
        $mail_html_prepare .= '<br>Номер телефона: '.$UserData[5]['value'];
    }
    $mail_html_prepare .= '</p><p>Профиль заявителя: <a href="'. $UserData[10]['value'] .'" target="_blank">'.$UserData[10]['value'].'</a>';
    $mail_html_prepare .= '</p><p>Выбранная дата, время тренировки: <i>'.$client_training_datetime.'</i>';
    $mail_html_prepare .= '</p><p><strong>Доступные действия:</strong><br>- <a href="https://viruviking.club/resources/php/php_trainreg/training_reg_decision.php?usr='.$UserDataEncoded.'&event='.$_POST['client_training_datetime'].'&dec=1">Подтвердить</a><br>- <a href="https://viruviking.club/resources/php/php_trainreg/training_reg_decision.php?usr='.$UserDataEncoded.'&event='.$_POST['client_training_datetime'].'&dec=0">Отклонить</a></p>';

    $Mail = new PHPMailer;
    $Mail->From     = 'noreply@viruviking.club';
    $Mail->FromName = 'Викинги Вирумаа';
    $Mail->CharSet  = "utf-8";
    $Mail->Subject  = 'VV - Уведомление с сайта viruviking.club';
    $Mail->isHTML(true);
    $Mail->Body     = $mail_html_prepare;
    // $Mail->AltBody  = $mail_txt_prepare;
    $Mail->AddAddress('victor@viruviking.club');
    if(!$Mail->Send()) {
      return false;
    } else {
      return true;
    }
}

function msgCLient($UserData,$client_training_datetime){
    $seasons_arr = array(
    	0 => 'зимнего',
    	1 => 'весенного',
    	2 => 'летнего',
    	3 => 'осеннего'
    );
    $сurrent_season=$seasons_arr[floor(date('n') / 3) % 4];
    $mail_html_prepare = '<html><body><link href="https://viruviking.club/resources/css/virvik_mail.css" rel="stylesheet"><p style="text-align:center;"><img src="https://viruviking.club/resources/img/logo/mail_logo_100.png" style="width:100px;"></p><p style="text-align:center;">Пусть лучи ' . $сurrent_season . ' солнца озарят Ваш дом!</p><p style="text-align:center;">Мы получили Вашу заявку на участие в пробной тренировке по историческому фехтованию<br>'. $client_training_datetime . '.</p>';
    $mail_html_prepare.='<p style="text-align:center;">Заявка будет рассмотрена Координаторами в течение нескольких дней.<br>Мы сообщим Вам о результатах рассмотрения заявки посредством электронной почты.</p><p style="text-align:center;"><a href="https://viruviking.club/" target="_blank">www.viruviking.club</a></p>';

    $Mail = new PHPMailer;
    $Mail->From     = 'noreply@viruviking.club';
    $Mail->FromName = 'Викинги Вирумаа';
    $Mail->CharSet  = "utf-8";
    $Mail->Subject  = 'VV - Уведомление с сайта viruviking.club';
    $Mail->isHTML(true);
    $Mail->Body     = $mail_html_prepare;
    // $Mail->AltBody  = $mail_txt_prepare;
    $Mail->AddAddress($UserData[2]['value']);
    if(!$Mail->Send()) {
      return false;
    } else {
      return true;
    }
}
