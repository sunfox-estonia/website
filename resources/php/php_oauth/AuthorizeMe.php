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
</head>
<body>
    <div class="container">
        <div class="row"><div class="col-xs-12"><?=$user_data->{'given_name'};?> <?=$user_data->{'family_name'};?>
                <br/><?=$user_data->{'email'};?>
            <form action="" method="POST" name="FormEventRegister">
            <fieldset>
                <legend><?=(_("Представьтесь, пожалуйста!"));?></legend>
                <div class="row"><div class="col-xs-4"><image src="<?=$user_data->{'picture'};?>" style="border-radius: 50%;width:140px;"/></div>
                <div class="col-xs-8">
                    <label for="contact_mail"><?=(_("Электропочта"));?></label><input class="input-large" type="email" name="contact_mail" value="" placeholder="nickname@domain.tld" required>
                    <label for="contact_mail"><?=(_("Номер телефона"));?></label><input class="input-large" type="tel" name="contact_mail" value="" placeholder="nickname@domain.tld" required>
                    <label for="contact_mail"><?=(_("Возраст"));?></label><input class="input-small" type="number" name="contact_mail" value="" placeholder="nickname@domain.tld" required>
                    <p class="help-block">В мероприятиях сообщества могут принимать участие юноши и девушки в возрасте от 14 до 23 лет. Лицам, не достигшим совершеннолетия необходимо разрешение родителей или опекунов.</p>
                    <button type="submit" class="btn-large"><?=(_("Подтверждаю"));?></button>
                </div>
           </fieldset>
           </form>                
            </div>
        </div>        
    </div>
    
    
    
    
    <?=$user_data->{'link'};?>
</body>
<script src='/resources/js/jquery.min.js'></script><script src='/resources/js/virvik.js'></script></html>