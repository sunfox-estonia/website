<?php
require ($_SERVER['DOCUMENT_ROOT'] . "/resources/php/php_plg_recaptcha/recaptchalib.php");
$privatekey = "6Ldj3_8SAAAAANdPcol2bIkhVpuna87pGm9QN2MP";
$publickey = "6Ldj3_8SAAAAAMe37hbwbhvsn3DJMGZjTAT5Ihtz";
if($_POST['contact_mail'] && $_POST['contact_message']){
  $response = null;
  $err = null;
  if ($_POST["recaptcha_response_field"]) {
    $response = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
    if ($response->is_valid) {
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
          header('HTTP/1.1 301 Moved Permanently'); header('Location: /?msg=500'); exit();
        } else {
          header('HTTP/1.1 301 Moved Permanently'); header('Location: /?msg=200'); exit();
        }
    } else {
      header('HTTP/1.1 301 Moved Permanently'); header('Location: /?msg=500'); exit();
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Братство ролевиков и исторических реконструкторов в Ида-Вирумаа, Эстония. Тренировки по фехтованию, исторические костюмы, LARP (ролевые игры живого действия)."/>
  <meta name="keywords" content="LARP, ролевые игры, реконструкторы, историческая реконструкция, Ида-Вирумаа, Йыхви, Нарва, тренировки по фехтованию, исторические костюмы, кружок по интересам, братство"/>
  <meta name="author" content="MFO Virumaa Viikingid"/>
  <meta name="robots" content="all"/>
  <meta name="robots" content="noarchive"/>
  <title>Викинги Вирумаа &ndash; Братство ролевиков и исторических реконструкторов</title>
<link href="resources/css/normalize.css" rel="stylesheet"><link href="resources/css/adaptive.css" rel="stylesheet"><link href="resources/css/glyphicons.css" rel="stylesheet"><link href="resources/css/alerts.css" rel="stylesheet"><link href="resources/css/forms.css" rel="stylesheet"><link href="resources/css/virvik.css" rel="stylesheet"></head>
<body>
<header>
    <div class="container">
      <?php if($_GET['msg'] == '200'){ ?>
        <div class="row"><div class="col-xs-12 col-sm-12 col-md-10 col-lg-7"><div class="alert alert-success" role="alert"><strong>Сообщение отправлено!</strong> Мы свяжемся с Вами в течение нескольких дней.</div></div></div>
      <?php } elseif ($_GET['msg'] == '500') { ?>
        <div class="row"><div class="col-xs-12 col-sm-12 col-md-10 col-lg-7"><div class="alert alert-danger" role="alert"><strong>Ошибка!</strong> Сообщение не удалось отправить.</div></div></div>
      <?php } ?>
      <nav><div class="row">
        <div class="col-xs-7 col-sm-6 col-md-offset-1 col-md-5 col-lg-offset-0 col-lg-4">
            <h1>Викинги Вирумаа<small>Братство ролевиков<br/>и исторических реконструкторов</small></h1>
            <!--<ul class="list-unstyled hidden-xs hidden-sm hidden-md">
                <li><a href="#">Коротко о Братстве</a></li>
                <li><a href="#">Цели и принципы</a></li>
                <li><a href="#">Деятельность</a></li></ul>-->
            <ul class="list-unstyled">
                <li><a href="#BlockContactUs">Контактная информация</a></li></ul>
        </div>
        <div class="col-lg-4 hidden-xs hidden-sm hidden-md">
            <!--<h2>Вечера настольных игр <small>в Нарве, Йыхви и Ору</small></h2>
            <p>Викинги Вирумаа приглашают друзей чтобы вместе сыграть в интересные и захватывающие настольные игры.</p>
            <p><a href="#SpecialBlockBoardGames">Регистрация</a></p>-->
        </div>
        <div class="col-xs-5 col-sm-6 col-sm-offset-0 col-md-5 col-lg-4">
            <p><span class="glyphicon glyphicon-globe"></span>&nbsp;&nbsp;<a href="https://viruviking.club/et/">Eesti keeles</a></p>
            <p><a href="http://vk.com/viruviking"><img src="resources/img/ico/social_vk.png"></a>
                &nbsp;<a href="http://fb.com/viruviking"><img src="resources/img/ico/social_fb.png"></a>
                &nbsp;<a href="http://plus.google.com/+ViruvikingClub" rel="publisher"><img src="resources/img/ico/social_gplus.png"></a>
                &nbsp;<a href="http://www.youtube.com/ViruvikingClub"><img src="resources/img/ico/social_yt.png"></a></p>
            <ul class="list-unstyled">
                <li><a href="http://wiki.viruviking.club">Вики-система Братства</a></li>
                <li><a href="http://mail.viruviking.club">Электронная почта</a></li></ul>
        </div>
      </div></nav>
    </div>
</header>
<main>
    <div class="container preintro"></div>
    <div class="container intro">
      <div class="row"><div id="intro_thirdhill"><div id="intro_sechill"><div id="intro_firsthill"><div id="intro_flag">
        <div class="col-sm-offset-6 col-sm-6 col-md-offset-6 col-md-6 col-lg-offset-4 col-lg-4">
         <h1>Братство Викингов Вирумаа объединяет молодежь, интересующуюся ролевыми играми живого действия и исторической реконструкцией.</h1><h2>Мы проводим тренировки по историческому фехтованию, изготавливаем исторические и ролевые костюмы, участвуем в полевых ролевках и организуем собственные игры.</h2>
         <h3 class="hidden-sm">LARP и историческая реконструкция — весьма интересное и достойное хобби. Вместе участники сообщества изучают историю, тренируются, учатся полезным жизненным навыкам выживания в суровых природных условиях без некоторых вещей. Они воспитывают в себе выдержку, волю, характер. Девушки приобретают навыки шитья, учатся готовить блюда, соотвествующие отыгрываемой исторической эпохе.</h3>
        </div>
        <div class="col-lg-4 hidden-xs hidden-sm hidden-md"><h2>Йомсвикинги</h2>
         <p>История и традиции йомсборгских викингов, описанные в легендах, стали прообразом для Братства Викингов Вирумаа.</p>
         <p>Из-за нехватки информации и отсутствия археологически подтверждёных фактов история братства йомсвикингов крайне запутана и противоречива. Согласно «Саге о йомсвикингах», братство существовало в X—XI веках, а база йомсвикингов находилась в крепости Йомсборг, расположенной на побережье Балтийского моря. Отсюда викинги совершали набеги на Норвегию, Швецию, Англию, Данию и другие страны.</p><p>Йомсвикинги подчинялись жёстким правилам с целью поддержания строгой военной дисциплины в общине. Запрещались ссоры и даже грубое обращение друг другу могло стоить йомсвикингу изгнания. Воинам не следовало показывать страх или бежать перед лицом равного или уступавшего в численности врага. Все трофеи приносились к знамени и делились между членами братства.</p>
         <p>В 1043 году король Норвегии Магнус I решил положить конец йомсвикингам. Он разграбил Йомсборг, сравнял крепость с землей и казнил уцелевших воинов братства.</p>
        </div>
      </div></div></div></div></div>
    </div>
    <div class="container subintro hidden-xs hidden-lg">
      <div class="row">
        <div class="col-sm-6"><h2>Йомсвикинги</h2>
          <p>История и традиции йомсборгских викингов, описанные в легендах, стали прообразом для Братства Викингов Вирумаа.</p>
          <p>Из-за нехватки информации и отсуствия археологически подтверждёных фактов история братства йомсвикингов крайне запутана и противоречива. Согласно «Саге о йомсвикингах», братство существовало в X—XI веках, а база йомсвикингов находилась в крепости Йомсборг, расположенной на побережье Балтийского моря. Отсюда викинги совершали набеги на Норвегию, Швецию, Англию, Данию и другие страны.</p>
        </div>
        <div class="col-sm-6">
          <p style="margin-top:33px;">Йомсвикинги подчинялись жёстким правилам с целью поддержания строгой военной дисциплины в общине. Запрещались ссоры и даже грубое обращение друг другу могло стоить йомсвикингу изгнания. Воинам не следовало показывать страх или бежать перед лицом равного или уступавшего в численности врага. Все трофеи приносились к знамени и делились между членами братства.</p>
          <p>В 1043 году король Норвегии Магнус I решил положить конец йомсвикингам. Он разграбил Йомсборг, сравнял крепость с землей и казнил уцелевших воинов братства.</p>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
<!--
    <div class="container fireplace">
      <div class="row">
        <div class="col-lg-12"><h1>Каждому найдется место возле огня</h1></div>
      </div><div class="row">
        <div class="col-sm-8 col-md-6 col-lg-4"><h3>Ценить каждого</h3><p>Каждый участник приносит к очагу Братства частичку своей души, вместе мы создаем неповторимую атмосферу Братства «Викингов Вирумаа». Каждый из нас пришел в Братство по какой-то своей причине, со своим жизненным опытом, своими проблемами, мечтами, ценностями. Каждый из нас - особенный. Относитесь с уважением и вниманием к каждому, принимайте людей такими какие они есть.</p>
        <h3>Сделать свой вклад</h3><p>Избегайте потребительского отношения к сообществу. Вы можете участвовать в подготовке и организации мероприятий, составлении и проведении проектов, в разработке инфосистем сообщества. Разучив интересный прием и отработав его с ребятами на тренировке Вы получите ценный опыт и сделаете жизнь сообщества - разнообразней.</p>
        <h3>Быть проще</h3><p>Мы собрались вместе чтобы весело и интересно провести свободное время. Здесь никто никому ничего не должен. Если кто-то из участников сообщества берет на себя выполнение задач, связанных с текущей работой Братства, мы благодарны ему за это. При этом у каждого есть право на ошибку. Не ошибается только тот кто ничего не делает.</p></div>
        <div class="col-sm-8 col-md-6 col-lg-4"><h3>Доверять друг другу</h3><p>Братство в целом, и его руководители в частности, оказывают доверие приглашая новых участников в сообщество. Все мы затрачиваем некоторое количество ресурсов на обучение участников, поездки, подготовку и проведение мероприятий. Мы доверяем друг другу свое время, силы, энергию. Для нас важно доверять друг другу во всех делах, связанных с деятельностью сообщества. Доверие важно как при планировании и проведении мероприятий так и в бою, пусть даже игровом.</p>
        <h3>Жизнь - игра. Игра - это маленькая жизнь.</h3><p>Мы создаем собственную игру, альтернативную реальность, в которой каждому из нас тепло и уютно. Мы используем имена, характеристики и легенду персонажей чтобы сделать игру интересней и увлекательней. В любой игре есть правила - их должен соблюдать каждый. В жизни за преступлением закона следует наказание. Нарушение правил Братства повлечет за собой соответствующую реакцию со стороны руководителей сообщества.</p></div>
      </div>
    </div>
    <div class="container">
      <div class="row"><a name="SpecialBlockBoardGames"></a>
        <div class="col-sm-6 col-md-offset-1 col-md-5 col-lg-offset-4 col-lg-4"><h1>Вечера настольных игр</h1>
          <p></p></div>
        <div class="col-sm-6 col-md-5 col-lg-4">
          <form action="#" method="POST">
           <fieldset>
            <legend>Регистрация на мероприятие</legend>
            <label for="eventreg_name">Имя, фамилия</label>
            <input class="input-large" type="text" name="ev
<script src='https://www.google.com/recaptcha/api.js?hl=es'></script>entreg_name" value="" required>
            <label for="eventreg_mail">Электропочта</label>
            <input class="input-large" type="email" name="eventreg_mail" value="" placeholder="nickname@domain.tld" required>
            <p class="help-block">Мы отпр+            <fieldset>
           <legend>Верификация</legend>
           <div class="g-recaptcha" data-sitekey="6Ldj3_8SAAAAAMe37hbwbhvsn3DJMGZjTAT5Ihtz"></div>
          </fieldset>авим подтверждение регистрации и дополнительную информацию о мероприятии на указанный Вами адрес электропочты.</p>
            <label for="eventreg_dateplace">Предстоящие мероприятия</label>
            <select class="input-xlarge" name="eventreg_dateplace" required>
             <option disabled selected>Выбрать подходящее из списка</option>
             <option value="option">1 окт. в 18:00, Нарва, офис Vitatiim</option>
             <option value="option">7 окт. в 13:00, Йыхви, Молодежный центр</option>
             <option value="option">3 нояб. в 14:00, Нарва, офис Vitatiim</option>
            </select>
           </fieldset>
           <button type="submit" class="btn-large">Отправить</button>
          </form>
        </div>
      </div>
    </div>
-->
</main>
<footer>
    <div class="container"><a name="BlockContactUs"></a>
      <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-offset-1 col-md-5 col-lg-offset-0 col-lg-4">
          <p>Свяжитесь с нами, если у Вас возникнут дополнительные вопросы или пожелания. Ниже указаны контактные данные координаторов сообщества.</p>
          <p><strong>Виктор Литвинков</strong><br/><i>Орг. вопросы сообщества в Йыхви</i><br/>+372 55 59 3171<br/><a href="mailto:victor@viruviking.club">victor@viruviking.club</a></p>
          <p><strong>Антон Корнышенков</strong><br/><i>LARP, крафтинг, тренировки в Йыхви</i><br/><a href="mailto:anton@viruviking.club">anton@viruviking.club</a></p>
          <p class="imprint hidden-xs">&copy; Братство &laquo;Викинги Вирумаа&raquo;, 2015 год<br>Иллюстрации: А. Корнышенков, А. Иваненко<br/>Разработка сайта: В. Литвинков, Д. Плюшко.</p>
        </div>
        <div class="col-xs-11 col-sm-6 col-md-5 col-lg-4">
            <p>Присоединиться к Братству могут юноши и девушки в возрасте от 14 до 23 лет, желающие принимать активное участие в жизни сообщества.</p>
            <p>Лицам, не достигшим совершеннолетия необходимо разрешение родителей или опекунов.<br/><a href="https://wiki.viruviking.club/public:start" target="_blank">Информация для родителей</a></p>
            <p class="hidden-sm">Напишите нам о своём желании присоединиться к Братству - для этого воспользуйтесь формой обратной связи, расположенной справа. Укажите корректный адрес эл. почты в соответствующем поле и номер мобильного телефона в сообщении.</p>
        </div>
        <div class="col-xs-7 col-sm-6 col-md-5 col-lg-4">
            <form action="" method="POST" name="FormContactUs">
            <fieldset>
             <legend>Обратная связь</legend>
             <label for="contact_mail">Электропочта</label>
             <input class="input-large" type="email" name="contact_mail" value="" placeholder="nickname@domain.tld" required>
           </fieldset>
            <label for="contact_message">Текст сообщения</label>
            <textarea class="input-xlarge" name="contact_message" rows="3" required ></textarea>
          <fieldset>
            <legend>Верификация</legend>
            <?php echo recaptcha_get_html($publickey, $err, true); ?>
          </fieldset>
            <button type="submit" class="btn-large">Отправить</button>
           </form>
         </div>
      </div>
    </div>
</footer>
</body>
<script src='resources/js/jquery.min.js'></script><script src='resources/js/virvik.js'></script><script type="text/javascript">
$(window).scroll(function () {
  intro_parallax();
});
</script></html>
