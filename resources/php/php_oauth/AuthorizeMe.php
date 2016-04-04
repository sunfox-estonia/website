
            <form name="FormClientIdentify" style="margin-bottom:15px;">
            <fieldset>
                <legend><?=(_("Идентификация"));?></legend>
                <image src="<?=$User['picture'];?>" style="border-radius: 6px;width:100px;float: right;margin: 15px 0 5px 10px;"/>
                <label for="client_fullname"><?=(_("Имя, фамилия"));?></label><input class="input-medium" type="text" name="client_fullname" value="<?=$User['fullname'];?>" <?=(is_null($User['fullname']) ? '' : 'disabled');?>>
                <label for="client_age"><?=(_("Возраст"));?></label><input class="input-small" type="number" name="client_age" placeholder="14+" value="<?=$User['age'];?>" <?=(is_null($User['age']) ? '' : 'disabled');?>>
                <p class="help-block"><?=(_("В мероприятиях сообщества могут принимать участие юноши и девушки в возрасте от 14 до 23 лет."));?></p>
                <label for="client_email"><?=(_("Электропочта"));?></label><input class="input-large" type="email" name="client_email" value="<?=$User['email'];?>" placeholder="nickname@domain.tld" <?=(is_null($User['email']) ? '' : 'disabled');?>>
                <label for="client_tel"><?=(_("Номер телефона"));?></label><input class="input-large" type="tel" name="client_tel" placeholder="+372 XX XX XXXX">
           </fieldset>
           <fieldset style="display:none;" id="ClientIntifyNeedsParentAccept">
                <legend><?=(_("Контактные данные родителей или опекуна"));?></legend>
                <label for="client_parent_name"><?=(_("Имя, отчество"));?></label><input class="input-large" type="text" name="client_parent_name">
                <label for="client_parent_tel"><?=(_("Номер телефона"));?></label><input class="input-large" type="tel" name="client_parent_tel" placeholder="+372 XX XX XXXX">
            </fieldset>

            <?php if(!is_null($User['age'])){ ?>
              <?=($User['age']>=14 && $User['age']<=23) ? '<button type="submit" class="btn-large">' . (_("Подтверждаю")) . '</button>' : '<div class="alert alert-warning" role="alert" id="FormClientIdentifyMessageAttention" style="display: block!important;">' . (_("<strong>Вы не можете зарегистрироваться</strong> на пробную тренировку! Свяжитесь с кооординаторами сообщества для уточнения данного вопроса.")) . '</div><button type="reset" class="btn-large" onclick="window.close();">' . (_("Закрыть")) . '</button>';?>
            <?php }else{ ?>
              <div class="alert alert-warning" role="alert" id="FormClientIdentifyMessageAttention"><?=(_("<strong>Вы не можете зарегистрироваться</strong> на пробную тренировку! Свяжитесь с кооординаторами сообщества для уточнения данного вопроса."));?></div>
              <button type="submit" class="btn-large"><?=(_("Подтверждаю"));?></button><button type="reset" class="btn-large" style="display:none;" onclick="window.close();"><?=(_("Закрыть"));?></button>
            <?php } ?>
           </form>
           </div>
        </div>
    </div>
<!--<?=$user_data->{'link'};?>-->
</body>
<script src='/resources/js/jquery.min.js'></script><script src='/resources/js/jquery.cookies.js'></script><script src='/resources/js/virvik.js'></script>
<script>
$("form[name='FormClientIdentify']").submit(function(e){
    e.preventDefault();
    $("form[name='FormClientIdentify'] input[name='client_fullname']").removeClass("error");
    $("form[name='FormClientIdentify'] input[name='client_age']").removeClass("error");
    $("form[name='FormClientIdentify'] input[name='client_email']").removeClass("error");
    $("form[name='FormClientIdentify'] input[name='client_tel']").removeClass("error");
    $("form[name='FormClientIdentify'] input[name='client_parent_name']").removeClass("error");
    $("form[name='FormClientIdentify'] input[name='client_parent_tel']").removeClass("error");
    $("form[name='FormClientIdentify'] button[type='submit']").removeClass("error");
    var FormRating=0;
    var emailExp = "/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/";

    var ClientAge = $("form[name='FormClientIdentify'] input[name='client_age']").val();
    if(ClientAge<18){
        var FormRating=FormRating-2;
    }
    if($("form[name='FormClientIdentify'] input[name='client_fullname']").val().length >= 3){
        var FormRating=FormRating+1;
    }else{
        $("form[name='FormClientIdentify'] input[name='client_fullname']").addClass("error");
    }
    if($("form[name='FormClientIdentify'] input[name='client_age']").val().length <2){
        $("form[name='FormClientIdentify'] input[name='client_age']").addClass("error");
    }else{
        var FormRating=FormRating+1;
    }
    if ($("form[name='FormClientIdentify'] input[name='client_email']").val().length > 7){
        var FormRating=FormRating+1;
    } else {
        $("form[name='FormClientIdentify'] input[name='client_email']").addClass("error");
    }
    if($("form[name='FormClientIdentify'] input[name='client_tel']").val().length > 5){
        var FormRating=FormRating+1;
    } else {
        $("form[name='FormClientIdentify'] input[name='client_tel']").addClass("error");
    }
    if($("form[name='FormClientIdentify'] input[name='client_parent_name']").val().length >= 3){
        var FormRating=FormRating+1;
    }else{
        $("form[name='FormClientIdentify'] input[name='client_parent_name']").addClass("error");
    }
    if($("form[name='FormClientIdentify'] input[name='client_parent_tel']").val().length > 5){
        var FormRating=FormRating+1;
    } else {
        $("form[name='FormClientIdentify'] input[name='client_parent_tel']").addClass("error");
    }
    if(FormRating<4){
        $("form[name='FormClientIdentify'] button[type='submit']").addClass("error");
    }else{
        var ClientDataArr = [];
        $.each($("form[name='FormClientIdentify'] input"), function() {
            ClientDataArr.push({name:$(this).attr('name'),value:$(this).val()});
        });
        ClientDataArr.push({name:'client_first_name',value:'<?=$User['firstname'];?>'});
        ClientDataArr.push({name:'client_last_name',value:'<?=$User['lastname'];?>'});
        ClientDataArr.push({name:'client_gender',value:'<?=$User['gender'];?>'});
        ClientDataArr.push({name:'client_language',value:'<?=(!is_null($User['language']) ? $User['language'] : $_SESSION['native']);?>'});
        ClientDataArr.push({name:'client_profile_link',value:'<?=$User['profile_link'];?>'});
        JsonPrepared=JSON.stringify(ClientDataArr);
        $.cookie('UserDataTransfer', JsonPrepared, { expires: 1, path: '/' });
        window.close();
    }
});

$("form[name='FormClientIdentify'] input[name='client_age']").one().blur(function() {
    $("form[name='FormClientIdentify'] input[name='client_age']").removeClass("error");
    var ClientAgeFlag=true;
    var ClientAge = $(this).val();
    if (ClientAge != '' && ClientAgeFlag){
    switch (true) {
      case (ClientAge<14):
        $('#FormClientIdentifyMessageAttention').show("fast");
        $("form[name='FormClientIdentify'] button[type='submit']").hide();
        $("form[name='FormClientIdentify'] button[type='reset']").show();
        ClientAgeFlag = false;
        break
      case (ClientAge<18):
        $('#ClientIntifyNeedsParentAccept').slideDown("fast");
        ClientAgeFlag = false;
        break
      case (ClientAge>23):
        $('#FormClientIdentifyMessageAttention').show("fast");
        $("form[name='FormClientIdentify'] button[type='submit']").hide();
        $("form[name='FormClientIdentify'] button[type='reset']").show();
        ClientAgeFlag = false;
        break
      default:
        ClientAgeFlag = false;
    }
    $("form[name='FormClientIdentify'] input[name='client_age']").attr("disabled", "disabled");
    }
});
</script>
</html>
