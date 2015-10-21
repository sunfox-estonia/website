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
    var thirdhill_val = Math.round(t*0.06-20);
    var sechill_val = Math.round(t*0.025);
    var firsthill_val = Math.round(t*(-0.04)+30);
    var flag_val_ver = Math.round(t*(-0.03)+10);
    var flag_val_hor = Math.round(t*(-0.005));
    $('div#intro_thirdhill').css('background-position', '0px ' + thirdhill_val + 'px');
    $('div#intro_sechill').css('background-position', '0 ' + sechill_val + 'px');
    $('div#intro_firsthill').css('background-position', '0 ' + firsthill_val + 'px');
    $('div#intro_flag').css('background-position', flag_val_hor + 'px ' + flag_val_ver + 'px');
}
