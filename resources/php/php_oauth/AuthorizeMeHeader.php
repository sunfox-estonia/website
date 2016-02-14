<?php
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
?>
<html>
<head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?=(_("Братство ролевиков и исторических реконструкторов в Ида-Вирумаа, Эстония. Тренировки по фехтованию, исторические костюмы, LARP (ролевые игры живого действия)."));?>"/>
  <meta name="keywords" content="<?=(_("LARP, ролевые игры, реконструкторы, историческая реконструкция, Ида-Вирумаа, Йыхви, Нарва, тренировки по фехтованию, исторические костюмы, кружок по интересам, братство"));?>"/>
  <meta name="author" content="MFO Virumaa Viikingid"/>
  <meta name="robots" content="all"/>
  <meta name="robots" content="noarchive"/>
  <title><?=(_("Викинги Вирумаа"));?></title>
<link href="/resources/css/normalize.css" rel="stylesheet">
<link href="/resources/css/adaptive.css" rel="stylesheet">
<link href="/resources/css/glyphicons.css" rel="stylesheet"><link href="/resources/css/font-awesome.css" rel="stylesheet">
<link href="/resources/css/alerts.css" rel="stylesheet">
<link href="/resources/css/forms.css" rel="stylesheet">
<link href="/resources/css/virvik.css" rel="stylesheet">
<link href="/resources/css/bootstrap-social.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row"><div class="col-xs-12 col-sm-offset-2 col-sm-8">