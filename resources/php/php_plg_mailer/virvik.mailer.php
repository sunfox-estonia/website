<?php
// require ($_SERVER['DOCUMENT_ROOT'] . "/resources/php/php_plg_recaptcha/recaptchalib.php");
// $privatekey = "6Ldj3_8SAAAAANdPcol2bIkhVpuna87pGm9QN2MP";
if($_POST['contact_mail'] && $_POST['contact_message']){
  $response = null;
  $err = null;
  if ($_POST["recaptcha_response_field"]) {
    $response=json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Ldj3_8SAAAAANdPcol2bIkhVpuna87pGm9QN2MP&response=".$_POST['recaptcha_response_field']."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
    if ($response['success'] == true) {
        require_once ($_SERVER['DOCUMENT_ROOT'] . "/resources/php/php_plg_mailer/class.phpmailer.php");
        $Mail = new PHPMailer;
        $mail_body      = eregi_replace("[\]",'',$_POST["contact_message"]);
        $Mail->From     = $_POST["contact_mail"];
        $Mail->FromName = 'Посетитель viruviking.club';
        $Mail->CharSet  = "utf-8";
        $Mail->Subject  = 'Сообщение с viruviking.club';
        $Mail->Body     = $mail_body;
        $Mail->AddAddress('info@viruviking.club');
        if(!$Mail->Send()) {
          $return="false";
        } else {
          $return="true";
        }
    } else {
      $return="false";
    }
  } else {
    $return="false";
  }
} else {
  $return="false";
}
echo $return;
