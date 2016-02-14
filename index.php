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
<link href="/resources/css/glyphicons.css" rel="stylesheet"><link href="/resources/css/font-awesome.css" rel="stylesheet">
<link href="/resources/css/alerts.css" rel="stylesheet">
<link href="/resources/css/forms.css" rel="stylesheet">
<link href="/resources/css/virvik.css" rel="stylesheet">
<link href="/resources/css/bootstrap-social.css" rel="stylesheet">
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
    <!-- Intro block -->
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
    
    <!-- Trainings adv + reg block -->
    <div class="container preadv_training"></div>
    <div class="container adv_training">
      <div class="row">
        <div class="col-md-6 col-lg-4">
            <h1><?=(_("Приглашаем на тренировки<small>по историческому фехтованию</small>"));?></h1><h2><?=(_("Мы проводим тренировки по историческому фехтованию, изготавливаем исторические и ролевые костюмы, участвуем в полевых ролевках и организуем собственные игры."));?></h2>
         <h3 class="hidden-sm"><?=(_("LARP и историческая реконструкция — весьма интересное и достойное хобби. Вместе участники сообщества изучают историю, тренируются, учатся полезным жизненным навыкам выживания в суровых природных условиях без некоторых вещей. Они воспитывают в себе выдержку, волю, характер. Девушки приобретают навыки шитья, учатся готовить блюда, соотвествующие отыгрываемой исторической эпохе."));?></h3>
        </div>
        <div class="col-md-6 col-lg-4 hidden-xs hidden-sm">  
            <form action="" method="POST" name="FormEventRegister">
            <fieldset>
             <legend><?=(_("Регистрация на пробную тренировку"));?></legend>
             <p>Необходимо предоставить реальное имя, фамилию, возраст и адрес эл. почты.</p>
             <p>Подробная информация о месте проведения тренировки и материалы для ознакомления будут высланы Вам по электронной почте.</p>
           </fieldset>
           <fieldset>
            <legend><?=(_("Представьтесь, пожалуйста"));?></legend>
            <p id="FormEventRegister_ModalRequest">
                <a class="btn btn-sm btn-social btn-vk" onclick="PopupCenter('http://oauth.vk.com/authorize?client_id=5293223&redirect_uri=https://v2.viruviking.club/resources/php/php_oauth/VkController.php&response_type=code', '<?=(_("Викинги Вирумаа"));?>', 780, 650)"><span class="fa fa-vk"></span>vk.com</a>
                <a class="btn btn-sm btn-social btn-google" onclick="PopupCenter('https://accounts.google.com/o/oauth2/auth?redirect_uri=https://v2.viruviking.club/resources/php/php_oauth/GoogleController.php&response_type=code&client_id=700082934855-sdrba0vc2mf1dpf75ho869tdghtdrv0g.apps.googleusercontent.com&scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email+https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile', '<?=(_("Викинги Вирумаа"));?>', 500, 650)"><span class="fa fa-google"></span>Google+</a><br/>
                <a class="btn btn-sm btn-social btn-facebook" onclick="PopupCenter('https://www.facebook.com/dialog/oauth?client_id=1704584893161917&redirect_uri=https://v2.viruviking.club/resources/php/php_oauth/FacebookController.php&response_type=code', '<?=(_("Викинги Вирумаа"));?>', 780, 650)"><span class="fa fa-facebook"></span>facebook.com</a>
            </p>
            <div id="FormEventRegister_ModalAnswer">
                <label for="client_fullname"><?=(_("Имя, фамилия"));?></label><input class="input-large" type="text" name="client_fullname" value="" disabled>
                <label for="client_email"><?=(_("Электропочта"));?></label><input class="input-large" type="email" name="client_email" value="" disabled>
            </div>
           </fieldset>
           <fieldset>
            <legend><?=(_("Выберите подходящую тренировку"));?></legend>
            <label for="contact_message"><?=(_("Место проведения, дата, время:"));?></label>
            <select class="form-control">
                <optgroup label="Кохтла-Ярве - Ахтме">
                <option selected value="">11 апреля в 14:00</option>
                <option value="">21 мая в 18:00</option>
                <option value="">6 июня в 14:00</option>
                </optgroup>
            </select>
           </fieldset>
            <button type="submit" class="btn-large"><?=(_("Отправить"));?></button>
            </form>
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
                <input class="input-large" type="email" name="contact_mail" value="" placeholder="nickname@domain.tld" required>            <label for="contact_message"><?=(_("Текст сообщения"));?></label>
                <textarea class="input-xlarge" name="contact_message" rows="3" required ></textarea>
            </fieldset>
            <fieldset>
                <legend><?=(_("Верификация"));?></legend>
                <div class="g-recaptcha" data-sitekey="6Ldj3_8SAAAAAMe37hbwbhvsn3DJMGZjTAT5Ihtz"></div>
            </fieldset>
            <button type="submit" class="btn-large"><?=(_("Отправить"));?></button>
            <div class="alert alert-danger" role="alert" id="FormContactUsMessageErr"><?=(_("<strong>Ошибка!</strong> Сообщение не удалось отправить."));?></div>
            <div class="alert alert-success" role="alert" id="FormContactUsMessageOk"><?=(_("<strong>Сообщение отправлено!</strong> Мы свяжемся с Вами в течение нескольких дней."));?></div>
           </form>
         </div>
      </div>
    </div>
</footer>
</body>
<script src='/resources/js/jquery.min.js'></script><script src='/resources/js/jquery.cookies.js'></script><script src='/resources/js/virvik.js'></script><script type="text/javascript">
$(window).scroll(function () {
  intro_parallax();
});
$('document').ready(function() {
    console.log( "Document ready!" );
    Bjorn = setInterval(function(){   
        if($.cookie('UserDataTransfer')){
            console.log( "Cookies are ready!" );
            clearInterval(Bjorn);
            // Скрыть абзац с авторизацией
            $("p#FormEventRegister_ModalRequest").hide();
            $("div#FormEventRegister_ModalAnswer").fadeIn('fast');
            // Добавить в скрыте поля значения из кукисов
            // Показать данные юзера: имя, фамилия, майл, фото
            var UserData = jQuery.parseJSON($.cookie('UserDataTransfer')); 
            $("div#FormEventRegister_ModalAnswer input[name=client_fullname]").val(UserData[0].value);
            $("div#FormEventRegister_ModalAnswer input[name=client_email]").val(UserData[2].value);
        }else{
            console.log( "Cookies is not ready :(" );
        }
    }, 2000);
});
</script></html>
