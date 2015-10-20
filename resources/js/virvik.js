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
function parallax() {
    var ev = {
        scrollTop: document.body.scrollTop || document.documentElement.scrollTop
    };
    ev.ratioScrolled = ev.scrollTop / (document.body.scrollHeight - document.documentElement.clientHeight);
    render(ev);
}
function render(ev) {
    var t = ev.scrollTop;
    var y = Math.round(t * 2/3) - 800;
    // var y = Math.round((1 - ev.ratioScrolled) * -100);
    $('div#intro_thirdhill').css('background-position', '0 ' + y + 'px');
    $('div#intro_sechill').css('background-position', '0 ' + y + 'px');
    $('div#intro_firsthill').css('background-position', '0 ' + y + 'px');
    $('div#intro_flag').css('background-position', '0 ' + y + 'px');
}
