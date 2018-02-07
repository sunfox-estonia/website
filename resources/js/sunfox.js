/*
Viruviking main JS file
Author: Victor Litvinkov
*/
/*
Local href onclick autoscroll to the anchor
*/
$('a[href^="#anchor_"]').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top
        }, 600);
        return false;
      }
    }
});

/*
Home page intro paralax
More info: http://stackoverflow.com/questions/15465481/is-there-a-way-to-make-parallax-work-within-a-div
*/
function intro_parallax() {
    var ev = {
        scrollTop: document.body.scrollTop || document.documentElement.scrollTop
    };
    ev.ratioScrolled = ev.scrollTop / (document.body.scrollHeight - document.documentElement.clientHeight);
    intro_parallax_render(ev);
}
function intro_parallax_render(ev) {
    var t = ev.scrollTop;
    var thirdhill_val = Math.round(t*0.05-20);
    var sechill_val = Math.round(t*0.025);
    var firsthill_val = Math.round(t*(-0.04)+30);
    var flag_val_ver = Math.round(t*(-0.03)+10);
    var flag_val_hor = Math.round(t*(-0.005));
    $('div#intro_thirdhill').css('background-position', '0px ' + thirdhill_val + 'px');
    $('div#intro_sechill').css('background-position', '0 ' + sechill_val + 'px');
    $('div#intro_firsthill').css('background-position', '0 ' + firsthill_val + 'px');
    $('div#intro_flag').css('background-position', flag_val_hor + 'px ' + flag_val_ver + 'px');
}
/*
Homepage Contact Form AJAX processing
*/
$("form[name=FormContactUs] button[type=submit]").click(function(){
  $("form[name=FormContactUs] button[type=submit]").prop( "disabled", true ).html('<img src="/resources/img/ico/preloader.gif" alt="Загрузка..." />').blur();

  var value_mail = $("form[name=FormContactUs] input[name=contact_mail]").val();
  var value_message = $("form[name=FormContactUs] textarea[name=contact_message]").val();
  var value_response_captcha = $("form[name=FormContactUs] textarea[name=g-recaptcha-response]").val();
  var dataString = 'contact_mail='+ value_mail + '&contact_message='+ value_message + '&recaptcha_response_field='+ value_response_captcha;
  $.ajax({
    type: "POST",
    url: "/resources/php/php_plg_mailer/sunfox.mailer.php",
    data: dataString,
    cache: false,
    success: function(response){
      $("form[name=FormContactUs] div.g-recaptcha").hide();
      $("form[name=FormContactUs] button[type=submit]").hide();
      switch(response){
      case 'true':
        $("form[name=FormContactUs] div#FormContactUsMessageOk").fadeIn('slow');
      break;
      case 'false':
        $("form[name=FormContactUs] div#FormContactUsMessageErr").fadeIn('slow');
      break;
      default:
        $("form[name=FormContactUs] div#FormContactUsMessageErr").fadeIn('slow');
      }
    },
    error:function(){
      $("form[name=FormContactUs] button[type=submit]").hide();
      $("form[name=FormContactUs] div.g-recaptcha").hide();
      $("form[name=FormContactUs] div#FormContactUsMessageErr").fadeIn('slow');
    }
  });
  return false;
});
/*
Bus timetable
*/
$('#ModalBusTimetable table tbody td').each(function() {
    var BusTime = $(this).html();
    var FormattedTime = new moment(BusTime, 'HH:mm');
    var CurrentTime = moment();
    // var CurrentTime = moment('17:10', 'HH:mm');
    var Duration = CurrentTime.diff(FormattedTime, 'minutes');    
    
    if (FormattedTime < CurrentTime) {
      $(this).addClass('text-muted');
    } else if(FormattedTime.isAfter(CurrentTime) && Duration > -5) {
      $(this).addClass('in5min');
    } else if(FormattedTime.isAfter(CurrentTime) && Duration > -10) {
      $(this).addClass('in10min'); 
    } else if(FormattedTime.isAfter(CurrentTime) && Duration > -15) {
      $(this).addClass('in15min');
    }
    
    var TodayNumber = moment().isoWeekday(); 
    // var TodayNumber = 6;
    switch (TodayNumber) {
      case 6:
        $("#ModalBusTimetable tbody tr td:first-child").addClass('text-muted');
        $("#ModalBusTimetable tbody tr td.in5min:first-child").css('background-color', '#b3b3b3');
        $("#ModalBusTimetable tbody tr td.in10min:first-child").css('background-color', '#cecece');
        $("#ModalBusTimetable tbody tr td.in15min:first-child").css('background-color', '#e3e3e3');
            
        $("#ModalBusTimetable tbody tr td:last-child").addClass('text-muted');
        $("#ModalBusTimetable tbody tr td.in5min:last-child").css('background-color', '#b3b3b3');
        $("#ModalBusTimetable tbody tr td.in10min:last-child").css('background-color', '#cecece');
        $("#ModalBusTimetable tbody tr td.in15min:last-child").css('background-color', '#e3e3e3');
        break;
      case 7:
        $("#ModalBusTimetable tbody td").addClass('text-muted');
        $("#ModalBusTimetable tbody tr td.in5min").css('background-color', '#b3b3b3');
        $("#ModalBusTimetable tbody tr td.in10min").css('background-color', '#cecece');
        $("#ModalBusTimetable tbody tr td.in15min").css('background-color', '#e3e3e3');            
        break;
      default:
        break;
    }
});
