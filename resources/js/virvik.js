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
/* Parallax effect */
function parallax() {
    var ev = {
        scrollTop: document.body.scrollTop || document.documentElement.scrollTop
    };
    ev.ratioScrolled = ev.scrollTop / (document.body.scrollHeight - document.documentElement.clientHeight);
    render(ev);
}
function render(ev) {
    var t = ev.scrollTop;
    var y = Math.round(t * 2/3);
    // $('.intro').css('background-position', '0 ' + y + 'px');
    $('div.intro_thirdhill').css('background-position', '0 ' + y+1 + 'px');
    $('div.intro_sechill').css('background-position', '0 ' + y+5 + 'px');
    $('div.intro_firsthill').css('background-position', '0 ' + y + 'px');
    $('div.intro_flag').css('background-position', '0 ' + y + 'px');
}
