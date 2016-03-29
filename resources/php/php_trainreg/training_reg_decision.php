<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . "/resources/php/php_plg_mailer/class.phpmailer.php");

$UserData=json_decode(base64_decode($_GET['usr']),TRUE);
$EventDetails=json_decode(base64_decode($_GET['event']),TRUE);
$decision=$_GET['dec'];

echo '<html><head><link href="/resources/css/normalize.css" rel="stylesheet"><style type="text/css">body{background: #fff url(/resources/eventmarks/128/';
switch ($decision) {
    case 1:
        if(msgAccept($UserData, $EventDetails)===TRUE){
            echo 'accept';
        }else{
            echo 'decline';
        }        
        break;
    case 0:
        if(msgDecline($UserData)){;
            echo 'accept';
        }else{
            echo 'decline';
        } 
        break;
    default:
        echo 'decline';
}
echo '.png)no-repeat center 20px}</style></head><body></body>';

function msgAccept($UserData, $EventDetails){
    
    $months_titles_array = array(1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля', 5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа', 9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря');
    $clear_date_arr=date_parse_from_format('Y-n-j H:i:sP', $EventDetails['start'][0]);
    $month_name = $months_titles_array[$clear_date_arr['month']];
    $client_training_datetime = $clear_date_arr['day'] . ' ' . $month_name . ' в ' . $clear_date_arr['hour'] . ':' . $clear_date_arr['minute']; 
    
    $mail_html_prepare='<html><body><link href="https://viruviking.club/resources/css/virvik_mail.css" rel="stylesheet"><p style="text-align:center;"><img src="https://viruviking.club/resources/img/logo/mail_logo_100.png" style="width:100px;"></p><p>Здравствуйте!</p><p>Координаторы сообщества одобрили Вашу заявку на участие в пробной тренировке по историческому фехтованию. Пожалуйста, крайне внимательно ознакомьтесь с текстом данного сообщения.</p>
<strong>Тренировка состоится:</strong><div style="display:block;width:400px;border:solid 1px #00a106;background:#fff;padding:10px 20px;margin:10px 0">
<p><strong>Дата, время:</strong> '. $client_training_datetime .'
<br/><strong>Место проведения:</strong> '. $EventDetails['location'] .'</p>
<p>Контактное лицо (координатор):';
    $mail_html_prepare.='<br/><strong>Виктор Литвинков</strong><br/><i>Орг. вопросы сообщества в Йыхви</i><br/>+372 55 59 3171<br/><a href="mailto:victor@viruviking.club">victor@viruviking.club</a></div><h2>Информация для новичков</h2><p>Методика обучения, используемая Викингами Вирумаа, достаточно эффективна, и многие замечают резкий прогресс уже через 1-3 недели. Однако хотелось бы придерживать прыть начинающих бойцов, пытающихся форсировать достижение высоких результатов, ибо:<br></p><ul><li>Только ровный и стабильный тренировочный процесс способен, без срывов и серьезных травм, привести к вершинам мастерства.</li><li>Мастерство поединщика способно формироваться лишь на фундаменте персонально отточенной базовой техники! 90% — шлифовка действий и 10% - спаррингов.</li><li>Преждевременное стремление слабо подготовленного спортсмена к настоящему жесткому спаррингу — прямая дорога к разочарованиям и травмам!</li></ul><p>Суть этого предостережения не в том, чтобы удерживать учеников от реальных поединков, но в том, чтобы плавно и последовательно вводить новичков в полновесный тренировочный цикл.</p>';
    $mail_html_prepare.='<h3>Готов ли я к тренировкам?</h3><p>Неплохой физической базой для исторического фехтования являются бокс, борьба, тяжелая атлетика и прочие виды спорта, предполагающие силовую нагрузку. Восточные же единоборства являются, скорее тормозящим фактором, чем фундаментом физической подготовки: приходится тратить массу времени и сил, чтобы вернуть «восточникам» естественность и целесообразность движений.</p><p>Хорошие задатки имеют люди, занимавшиеся альпинизмом, водным туризмом, атлетикой и пожарно-прикладным спортом. Неплохой физической подготовкой также является стандартный набор приседаний, прыжков, отжиманий и подтягиваний.</p><h3>Инвентарь и экипировка</h3><p>Для тренировок по историческому фехтованию в первую очередь Вам потребуется тренировочный (утяжеленный) одноручный меч. На втором этапе тренировочной программы начнутся занятия с «гуманизированным» оружием. Для получения тренировочных снарядов пожалуйста обратитесь к координаторам сообщества или тренеру.</p><p>На тренировку следует приходить в комфортной спортивной одежде, тело должно быть покрыто максимальным образом. Рекомендуемый набор: кофта с длинными рукавами и капюшоном, спортивные штаны. Подберите одежду, которую не жалко испачкать, порвать или потерять. Для защиты рук необходимо использовать перчатки. Рекомендуется одевать тактические перчатки с защитными пластинами или шерстяные перчатки с подкладкой внутри.
</p>';
    $mail_html_prepare.='<p>Для занятий в зале необходима сменная обувь. Если есть желание, можно заниматься в носках или босиком. С собой возьмите бутылочку воды - скорее всего, в ходе интенсивной тренировки захочется пить.</p><h3>Что еще необходимо знать?</h3><p>Первый этап тренировочной программы - общефизическая подготовка. Несколько тренировок подряд Вы будете делать различные упражнения для развития ног, рук и корпуса. Для достижения наилучших результатов рекомендуем ежедневно (или разу в пару дней) самостоятельно повторять комплекс упражнений, предложенных в рамках тренировки. Занятия в спортзале («качалке») и периодическое посещение бассейна послужат отличным дополнением к тренировочному процессу в рамках предложенной программы.</p><p>Выполнение упражнений в рамках физподготовки, а также отработка ударов и приемов на «балде», осуществляется тренировочными (утяжеленными) снарядами (т.е. «на железе»). Контактные бои (спарринги) проводятся только на гуманизированном оружии. Нецелевое использование инвентаря - запрещено.</p><p>Тренировки проводятся на так называемых «тренировочных полигонах» - специально оборудованных площадках. Рекомендовано явиться на место проведения тренировки за 5 минут до начала занятия.</p><p>До встречи на тренировке!</p>';
    $Mail = new PHPMailer;
    $Mail->From     = 'noreply@viruviking.club';
    $Mail->FromName = 'Викинги Вирумаа';
    $Mail->CharSet  = 'utf-8';
    $Mail->Subject  = 'VV - Уведомление с сайта viruviking.club';
    $Mail->isHTML(true);
    $Mail->Body     = $mail_html_prepare;
    // $Mail->AltBody  = $mail_txt_prepare;
    $Mail->AddAddress($UserData[2]['value']);    
    if(!$Mail->Send()) {
      return FALSE;
    } else {
      return TRUE;
    }
}

function msgDecline($UserData){ 
    $mail_html_prepare = '<html><body><link href="https://viruviking.club/resources/css/virvik_mail.css" rel="stylesheet"><p style="text-align:center;"><img src="https://viruviking.club/resources/img/logo/mail_logo_100.png" style="width:100px;"></p><p style="text-align:center;">К сожалению, Ваша заявка на участие в пробной тренировке по историческому фехтованию не удовлетворена.</p>';
    $mail_html_prepare.='<p style="text-align:center;">Если Вы желаете получить более подробную информацию, пожалуйста свяжитесь с координаторами сообщества, воспользовавшись контактными данными, указанными на сайте.</p><p style="text-align:center;"><a href="https://viruviking.club/" target="_blank">www.viruviking.club</a></p>';
            
    $Mail = new PHPMailer;
    $Mail->From     = 'noreply@viruviking.club';
    $Mail->FromName = 'Викинги Вирумаа';
    $Mail->CharSet  = "utf-8";
    $Mail->Subject  = 'VV - Уведомление с viruviking.club';
    $Mail->isHTML(true);
    $Mail->Body     = $mail_html_prepare;
    // $Mail->AltBody  = $mail_txt_prepare;
    $Mail->AddAddress($UserData[2]['value']);    
    if(!$Mail->Send()) {
      return FALSE;
    } else {
      return TRUE;
    }
}