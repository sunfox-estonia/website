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
Homepage Calendar requests handler
*/
$('div.event').on('click', function(e){
    $('#ModalEventAuth').find('.modal-content').load($(this).attr('data-eventid'));
    $('#ModalEventAuth').modal('show');
});
$("#ModalEventAuth").on('hidden.bs.modal', function () {
    $('#ModalEventAuth').find('.modal-content').empty();
});
/*
Homepage Contact Form AJAX processing
*/		
$("form[name=FormContactUs]").submit(function(event){
    /* stop form from submitting normally */
    event.preventDefault();
    $("form[name=FormContactUs] button[type=submit]").prop( "disabled", true ).html('<img src="/resources/img/ico/preloader.gif" alt="Загрузка..." />').blur();

    /* setup post function vars */
    var url = $(this).attr('action');
    var postdata = $(this).serialize();    

    /* send the data using post and put the results in a div with id="result" */
    /* post(url, postcontent, callback, datatype returned) */

    var request = $.post(
        url,
        postdata,
        formpostcompleted,
        "json"            
    ); // end post function     

    function formpostcompleted(data, status)
    {   
        $("form[name=FormContactUs] div.g-recaptcha").hide();
        $("form[name=FormContactUs] button[type=submit]").hide();
        switch(data){
            case true :
              $("form[name=FormContactUs] div#FormContactUsMessageOk").fadeIn('fast');
            break;
            case false :
              $("form[name=FormContactUs] div#FormContactUsMessageErr").fadeIn('fast');
            break;
            default:
              $("form[name=FormContactUs] div#FormContactUsMessageErr").fadeIn('fast');
        }
    }
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
