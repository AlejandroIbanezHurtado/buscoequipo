$(function(){
    $(".calendario").datepicker({
         minDate: 0,
         firstDay: 1,
         beforeShowDay: function(date) {
            var day = date.getDay();
            return [(day != 0), ''];
        }
        });
    $( ".calendario" ).datepicker( "option", "showAnim", "drop");
    $( ".calendario" ).datepicker({
        format:'mm/dd/yyyy',
    }).datepicker("setDate",'now+1');

    $(".redes").on("click",function(){
        window.open($(this).attr("href"),'_blank');
    })

    $(".redes").mouseover(function(){
        $(this).css('cursor','pointer');
    })

    $('.dropdown').hover(function() {
        $(this).find('.dropdown-menu').first().stop(true, true).delay(250).slideDown();
      }, function() {
        $(this).find('.dropdown-menu').first().stop(true, true).delay(100).slideUp()
      });

    $("#mapa").on("click",function(){
        window.open("/pdf/mapaNav.pdf");
    })
    $("#guia").on("click",function(){
        window.open("/pdf/guiaEstilos.pdf");
    })
    $("#plantilla").on("click",function(){
        window.open("https://themewagon.com/themes/free-responsive-bootstrap-4-html5-car-rental-website-template-carrent/");
    })
})