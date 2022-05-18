$(function(){
    var contProxPartido = $("#contProxPartido");
    setInterval(function(){
        var hora = $("#horas")
        var min = $("#minutos")
        var seg = $("#segundos")
        if(hora.text()=="00" && min.text()=="00" && seg.text()=="00")
        {
            contProxPartido.children().remove()
            pidePartido();
        }
    },1000)
    
    contProxPartido.append("<div class='loader'></div>")
    pidePartido();
    function pidePartido(){
        $.getJSON("/api/obtenProxPartidos",function(result){
            $(".loader").remove();
            contProxPartido.append("<div class='h5 text-black text-uppercase text-center text-lg-left' id='proxPartido'><div class='d-block d-md-inline-block mb-3 mb-lg-0'><img src='' alt='Image' class='mr-3 image'><span class='d-block d-md-inline-block ml-0 ml-md-3 ml-lg-0'></span></div><span class='text-muted mx-3 text-normal mb-3 mb-lg-0 d-block d-md-inline '>vs</span> <div class='d-block d-md-inline-block'><img src='' alt='Image' class='mr-3 image'><span class='d-block d-md-inline-block ml-0 ml-md-3 ml-lg-0'></span></div></div>");
            console.log(result);
            var prox = $("#proxPartido");
            for(i=0;i<result.proxPartido.length+1;i=i+2)
            {
                j=i;
                if(i==2) j=1;
                prox.children()[i].children[0].setAttribute("src","/bd/"+result.proxPartido[j].escudo)
                prox.children()[i].children[1].innerText=result.proxPartido[j].nombre_equipo
            }
            $('#date-countdown').countdown(result.proxPartido[0].fecha_ini, function(event) {
                var $this = $(this).html(event.strftime(''
                + '<span class="countdown-block"><span class="label">%w</span> sem </span>'
                + '<span class="countdown-block"><span class="label">%d</span> dias </span>'
                + '<span class="countdown-block"><span class="label" id="horas">%H</span> hr </span>'
                + '<span class="countdown-block"><span class="label" id="minutos">%M</span> min </span>'
                + '<span class="countdown-block"><span class="label" id="segundos">%S</span> seg</span>'));
            });
        })
    }
})