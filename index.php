<?php
/* Recaptcha settings */
require ($_SERVER['DOCUMENT_ROOT'] . "/resources/php/php_plg_recaptcha/recaptchalib.php");
// $publickey = "6Ldj3_8SAAAAAMe37hbwbhvsn3DJMGZjTAT5Ihtz";
$err = null;
/* Site translation */
session_start();
if (!isset($_SESSION['native']) || isset($_GET['lang'])) {
  $_SESSION['native'] = isset($_GET['lang']) ? $_GET['lang'] : substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
}
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
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?=(_("Братство ролевиков и исторических реконструкторов в Ида-Вирумаа, Эстония. Тренировки по фехтованию, исторические костюмы, LARP (ролевые игры живого действия)."));?>"/>
  <meta name="keywords" content="<?=(_("LARP, ролевые игры, реконструкторы, историческая реконструкция, Ида-Вирумаа, Йыхви, Нарва, тренировки по фехтованию, исторические костюмы, кружок по интересам, братство"));?>"/>
  <meta name="author" content="MFO Virumaa Viikingid"/>
  <meta name="robots" content="all"/>
  <meta name="robots" content="noarchive"/>
  <title><?=(_("Викинги Вирумаа"));?> &ndash; <?=(_("Братство ролевиков"));?> <?=(_("и исторических реконструкторов"));?></title>
<link href="/resources/css/normalize.css" rel="stylesheet">
<link href="/resources/css/adaptive.css" rel="stylesheet">
<link href="/resources/css/glyphicons.css" rel="stylesheet">
<link href="/resources/css/alerts.css" rel="stylesheet">
<link href="/resources/css/forms.css" rel="stylesheet">
<link href="/resources/css/virvik.css" rel="stylesheet">
<script src='https://www.google.com/recaptcha/api.js?hl=<?=$_SESSION['native'];?>'></script>
</head>
<body>
<header>
    <div class="container">
      <nav><div class="row">
        <div class="col-xs-7 col-sm-6 col-md-offset-1 col-md-5 col-lg-offset-0 col-lg-4">
            <h1><?=(_("Викинги Вирумаа"));?><small><?=(_("Братство ролевиков"));?><br/><?=(_("и исторических реконструкторов"));?></small></h1>
            <!--<ul class="list-unstyled hidden-xs hidden-sm hidden-md">
                <li><a href="#"></a></li>
              </ul>-->
            <ul class="list-unstyled">
                <li><a href="#BlockContactUs"><?=(_("Контактная информация"));?></a></li></ul>
        </div>
        <div class="col-lg-4 hidden-xs hidden-sm hidden-md">
            <!--<h2><small></small></h2>
            <p></p>
            <p><a href="#SpecialBlockLink"></a></p>-->
        </div>
        <div class="col-xs-5 col-sm-6 col-sm-offset-0 col-md-5 col-lg-4">
            <p><span class="glyphicon glyphicon-globe"></span>&nbsp;&nbsp;<?php
                switch ($_SESSION['native']) {
                  case "et":
                    echo '<a href="https://viruviking.club/lang/ru">&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081;</a></p>';
                    break;
                  default:
                    echo '<a href="https://viruviking.club/lang/et">Eesti keeles</a></p>';
                    break;
                } ?></p>
            <p><a href="http://vk.com/viruviking"><img src="/resources/img/ico/social_vk.png"></a>
                &nbsp;<a href="http://fb.com/viruviking"><img src="/resources/img/ico/social_fb.png"></a>
                &nbsp;<a href="http://plus.google.com/+ViruvikingClub" rel="publisher"><img src="/resources/img/ico/social_gplus.png"></a>
                &nbsp;<a href="http://www.youtube.com/ViruvikingClub"><img src="/resources/img/ico/social_yt.png"></a></p>
            <ul class="list-unstyled">
                <li><a href="http://wiki.viruviking.club"><?=(_("Вики-система Братства"));?></a></li>
                <li><a href="http://mail.viruviking.club"><?=(_("Электронная почта"));?></a></li></ul>
        </div>
      </div></nav>
    </div>
</header>
<main>
    <div class="container preintro"></div>
    <div class="container intro">
      <div class="row"><div id="intro_thirdhill"><div id="intro_sechill"><div id="intro_firsthill"><div id="intro_flag">
        <div class="col-sm-offset-6 col-sm-6 col-md-offset-6 col-md-6 col-lg-offset-4 col-lg-4">
         <h1><?=(_("Братство Викингов Вирумаа объединяет молодежь, интересующуюся ролевыми играми живого действия и исторической реконструкцией."));?></h1><h2><?=(_("Мы проводим тренировки по историческому фехтованию, изготавливаем исторические и ролевые костюмы, участвуем в полевых ролевках и организуем собственные игры."));?></h2>
         <h3 class="hidden-sm"><?=(_("LARP и историческая реконструкция — весьма интересное и достойное хобби. Вместе участники сообщества изучают историю, тренируются, учатся полезным жизненным навыкам выживания в суровых природных условиях без некоторых вещей. Они воспитывают в себе выдержку, волю, характер. Девушки приобретают навыки шитья, учатся готовить блюда, соотвествующие отыгрываемой исторической эпохе."));?></h3>
        </div>
        <div class="col-lg-4 hidden-xs hidden-sm hidden-md"><h2><?=(_("Йомсвикинги"));?></h2>
         <p><?=(_("История и традиции йомсборгских викингов, описанные в легендах, стали прообразом для Братства Викингов Вирумаа."));?></p>
         <p><?=(_("Из-за нехватки информации и отсутствия археологически подтверждёных фактов история братства йомсвикингов крайне запутана и противоречива. Согласно «Саге о йомсвикингах», братство существовало в X—XI веках, а база йомсвикингов находилась в крепости Йомсборг, расположенной на побережье Балтийского моря. Отсюда викинги совершали набеги на Норвегию, Швецию, Англию, Данию и другие страны."));?></p><p><?=(_("Йомсвикинги подчинялись жёстким правилам с целью поддержания строгой военной дисциплины в общине. Запрещались ссоры и даже грубое обращение друг другу могло стоить йомсвикингу изгнания. Воинам не следовало показывать страх или бежать перед лицом равного или уступавшего в численности врага. Все трофеи приносились к знамени и делились между членами братства."));?></p>
         <p><?=(_("В 1043 году король Норвегии Магнус I решил положить конец йомсвикингам. Он разграбил Йомсборг, сравнял крепость с землей и казнил уцелевших воинов братства."));?></p>
        </div>
      </div></div></div></div></div>
    </div>
    <div class="container subintro hidden-xs hidden-lg">
      <div class="row">
        <div class="col-sm-6"><h2><?=(_("Йомсвикинги"));?></h2>
          <p><?=(_("История и традиции йомсборгских викингов, описанные в легендах, стали прообразом для Братства Викингов Вирумаа."));?></p>
          <p><?=(_("Из-за нехватки информации и отсутствия археологически подтверждёных фактов история братства йомсвикингов крайне запутана и противоречива. Согласно «Саге о йомсвикингах», братство существовало в X—XI веках, а база йомсвикингов находилась в крепости Йомсборг, расположенной на побережье Балтийского моря. Отсюда викинги совершали набеги на Норвегию, Швецию, Англию, Данию и другие страны."));?></p>
        </div>
        <div class="col-sm-6">
          <p style="margin-top:33px;"><?=(_("Йомсвикинги подчинялись жёстким правилам с целью поддержания строгой военной дисциплины в общине. Запрещались ссоры и даже грубое обращение друг другу могло стоить йомсвикингу изгнания. Воинам не следовало показывать страх или бежать перед лицом равного или уступавшего в численности врага. Все трофеи приносились к знамени и делились между членами братства."));?></p>
          <p><?=(_("В 1043 году король Норвегии Магнус I решил положить конец йомсвикингам. Он разграбил Йомсборг, сравнял крепость с землей и казнил уцелевших воинов братства."));?></p>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
</main>
<footer>
    <div class="container"><a name="BlockContactUs"></a>
      <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-offset-1 col-md-5 col-lg-offset-0 col-lg-4">
          <p><?=(_("Свяжитесь с нами, если у Вас возникнут дополнительные вопросы или пожелания. Ниже указаны контактные данные координаторов сообщества."));?></p>
          <p><strong><?=(_("Виктор Литвинков"));?></strong><br/><i><?=(_("Орг. вопросы сообщества в Йыхви"));?></i><br/>+372 55 59 3171<br/><a href="mailto:victor@viruviking.club">victor@viruviking.club</a></p>
          <p><strong><?=(_("Антон Корнышенков"));?></strong><br/><i><?=(_("LARP, крафтинг, тренировки в Йыхви"));?></i><br/><a href="mailto:anton@viruviking.club">anton@viruviking.club</a></p>
          <p class="imprint hidden-xs"><?=(_("&copy; Братство &laquo;Викинги Вирумаа&raquo;, 2015 год"));?><br><?=(_("Иллюстрации: А. Корнышенков, А. Иваненко<br/>Разработка сайта: В. Литвинков, Д. Плюшко."));?></p>
        </div>
        <div class="col-xs-11 col-sm-6 col-md-5 col-lg-4">
            <p><?=(_("Присоединиться к Братству могут юноши и девушки в возрасте от 14 до 23 лет, желающие принимать активное участие в жизни сообщества."));?></p>
            <p><?=(_("Лицам, не достигшим совершеннолетия необходимо разрешение родителей или опекунов."));?><br/><a href="https://wiki.viruviking.club/public:start" target="_blank"><?=(_("Информация для родителей"));?></a></p>
            <p class="hidden-sm"><?=(_("Напишите нам о своём желании присоединиться к Братству - для этого воспользуйтесь формой обратной связи, расположенной справа. Укажите корректный адрес эл. почты в соответствующем поле и номер мобильного телефона в сообщении."));?></p>
        </div>
        <div class="col-xs-7 col-sm-6 col-md-5 col-lg-4">
            <form action="" method="POST" name="FormContactUs">
            <fieldset>
             <legend><?=(_("Обратная связь"));?></legend>
             <label for="contact_mail"><?=(_("Электропочта"));?></label>
             <input class="input-large" type="email" name="contact_mail" value="" placeholder="nickname@domain.tld" required>
           </fieldset>
            <label for="contact_message"><?=(_("Текст сообщения"));?></label>
            <textarea class="input-xlarge" name="contact_message" rows="3" required ></textarea>
          <fieldset>
            <legend><?=(_("Верификация"));?></legend>
            <div class="g-recaptcha" data-sitekey="6Ldj3_8SAAAAAMe37hbwbhvsn3DJMGZjTAT5Ihtz"></div>
          </fieldset>
            <button type="submit" class="btn-large"><?=(_("Отправить"));?></button>
            <div class="alert alert-danger" role="alert" id="BlockMessageErr"><?=(_("<strong>Ошибка!</strong> Сообщение не удалось отправить."));?></div>
            <div class="alert alert-success" role="alert" id="BlockMessageOk"><?=(_("<strong>Сообщение отправлено!</strong> Мы свяжемся с Вами в течение нескольких дней."));?></div>
           </form>
         </div>
      </div>
    </div>
</footer>
</body>
<script src='/resources/js/jquery.min.js'></script><script src='/resources/js/virvik.js'></script><script type="text/javascript">
$(window).scroll(function () {
  intro_parallax();
});
$("form[name=FormContactUs]>button[type=submit]").click(function(){
  $("form[name=FormContactUs]>button[type=submit]").prop( "disabled", true ).html('<img src="/resources/img/ico/preloader.gif" alt="<?=(_("Загрузка..."));?>" />').blur();
  
  var value_mail = $("form[name=FormContactUs] input[name=contact_mail]").val();
  var value_message = $("form[name=FormContactUs] textarea[name=contact_message]").val();
  var value_response_captcha = $("form[name=FormContactUs] textarea[name=g-recaptcha-response]").val();
  var dataString = 'contact_mail='+ value_mail + '&contact_message='+ value_message + '&recaptcha_response_field='+ value_response_captcha;
  $.ajax({
    type: "POST",
    url: "/resources/php/php_plg_mailer/virvik.mailer.php",
    data: dataString,
    cache: false,
    success: function(response){
      $("form[name=FormContactUs] div#recaptcha_widget_div").hide();
      $("form[name=FormContactUs]>button[type=submit]").hide();
      switch(response){
      case 'true':
        $("form[name=FormContactUs]>div#BlockMessageOk").fadeIn('slow');
      break;
      case 'false':
        $("form[name=FormContactUs]>div#BlockMessageErr").fadeIn('slow');
      break;
      default:
        $("form[name=FormContactUs]>div#BlockMessageErr").fadeIn('slow');
      }
    },
    error:function(){
      $("form[name=FormContactUs]>button[type=submit]").hide();
      $("form[name=FormContactUs]>div#recaptcha_widget_div").hide();
      $("form[name=FormContactUs]>div#BlockMessageErr").fadeIn('slow');
    }
  });
  return false;
});
</script></html>
