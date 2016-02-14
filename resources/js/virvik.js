/*
Viruviking main JS file
Author: Victor Litvinkov
*/
/*
Local href onclick autoscroll to the anchor
*/
$(function() {
  $('a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top
        }, 1000);
        return false;
      }
    }
  });
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
      $("form[name=FormContactUs] div.g-recaptcha").hide();
      $("form[name=FormContactUs]>button[type=submit]").hide();
      switch(response){
      case 'true':
        $("form[name=FormContactUs]>div#FormContactUsMessageOk").fadeIn('slow');
      break;
      case 'false':
        $("form[name=FormContactUs]>div#FormContactUsMessageErr").fadeIn('slow');
      break;
      default:
        $("form[name=FormContactUs]>div#FormContactUsMessageErr").fadeIn('slow');
      }
    },
    error:function(){
      $("form[name=FormContactUs]>button[type=submit]").hide();
      $("form[name=FormContactUs]>div.g-recaptcha").hide();
      $("form[name=FormContactUs]>div#FormContactUsMessageErr").fadeIn('slow');
    }
  });
  return false;
});
/*
 * Popup window call function
 */
function PopupCenter(url, title, w, h) {
    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;
    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    // Puts focus on the newWindow
    if (window.focus) {
        newWindow.focus();
    }
}
