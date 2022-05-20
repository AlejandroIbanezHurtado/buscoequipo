$(function(){
    var contUltimoPartidoTorneo = $("#contUltimoPartidoTorneo");
    var contProxPartido = $("#contProxPartido");
    setInterval(function(){
        var hora = $("#horas")
        var min = $("#minutos")
        var seg = $("#segundos")
        if(hora.text()=="00" && min.text()=="00" && seg.text()=="00")
        {
            contProxPartido.children().remove()
            pideProxPartido();
        }
    },1000)
    
    contProxPartido.append("<div class='loader'></div>")
    pideProxPartido();
    function pideProxPartido(){
        $.getJSON("/api/obtenProxPartidos",function(result){
            $(".loader").remove();
            contProxPartido.append("<div class='h5 text-black text-uppercase text-center text-lg-left' id='proxPartido'><div class='d-block d-md-inline-block mb-3 mb-lg-0'><img src='' alt='Image' class='mr-3 image'><span class='d-block d-md-inline-block ml-0 ml-md-3 ml-lg-0'></span></div><span class='text-muted mx-3 text-normal mb-3 mb-lg-0 d-block d-md-inline '>vs</span> <div class='d-block d-md-inline-block'><img src='' alt='Image' class='mr-3 image'><span class='d-block d-md-inline-block ml-0 ml-md-3 ml-lg-0'></span></div></div>");
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

    function pideUltimoPartidoTorneo(){
        $.getJSON("/api/obtenIndex1",function(result){
            nombres = $(".nombre-equipo-torneo")
            console.log(result);
            for(i=0;i<result.datos_equipos.length;i++)
            {
                contUltimoPartidoTorneo.children().find("img")[i].setAttribute("src","bd/"+result.datos_equipos[i].escudo);
                nombres[i].innerText=result.datos_equipos[i].nombre
            }
            datos_torneo = $(".datos_torneo");
            tipos = ["cuartos","semifinal","final"];
            datos_torneo.children()[0].children[0].innerText=result.nombre_torneo+" - "+tipos[result.tipo];
            goles1 = 0;
            goles2 = 0;
            if(typeof result.goles_partido[0]!=="undefined") goles1 = result.goles_partido[0].goles;
            if(typeof result.goles_partido[1]!=="undefined") goles2 = result.goles_partido[1].goles;
            datos_torneo.children()[1].children[0].innerText=goles1+":"+goles2;
            $("#fecha_torneo").text(result.datos_equipos[0].fecha_ini)
        })
    }
    setInterval(function(){
        pideUltimoPartidoTorneo();
    },60000);

    pideUltimoPartidoTorneo();
})