$(function(){
    var contUltimoPartidoTorneo = $("#contUltimoPartidoTorneo");
    var contProxPartido = $("#contProxPartido");
    var contPartidos = $("#pills-tabContent");
    var contEquipos = $("#contEquipos");
    var contPistas = $("#contPistas");
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
            contProxPartido.append("<div class='h5 text-black text-uppercase text-center text-lg-left' id='proxPartido'><div class='d-block d-md-inline-block mb-3 mb-lg-0'><img src='' alt='No encontrado' class='mr-3 image'><span class='d-block d-md-inline-block ml-0 ml-md-3 ml-lg-0'></span></div><span class='text-muted mx-3 text-normal mb-3 mb-lg-0 d-block d-md-inline '>vs</span> <div class='d-block d-md-inline-block'><img src='' alt='No encontrado' class='mr-3 image'><span class='d-block d-md-inline-block ml-0 ml-md-3 ml-lg-0'></span></div></div>");
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
            for(i=0;i<result.datos_equipos.length;i++)
            {
                contUltimoPartidoTorneo.children().find("img")[i].setAttribute("src","bd/"+result.datos_equipos[i].escudo);
                contUltimoPartidoTorneo.children().find("img")[i].setAttribute("alt","No encontrado")
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

            for(i=0;i<3;i++) $(contPartidos.children()[i].children[0].children[0]).empty();
            cont=0;
            for(i=0;i<result.partidos.length-1;i=i+2)
            {
                cont++;
                partido = "<div class='row bg-white align-items-center ml-0 mr-0 py-4 match-entry'>\
                    <div class='col-md-4 col-lg-4 mb-4 mb-lg-0'>\
                        <div class='text-center text-lg-left'>\
                        <div class='d-block d-lg-flex align-items-center'>\
                            <div class='image image-small text-center mb-3 mb-lg-0 mr-lg-3'>\
                            <img src='bd/"+result.partidos[i].escudo+"' alt='No encontrado' class='img-fluid'>\
                            </div>\
                            <div class='text'>\
                            <h3 class='h5 mb-0 text-black'>"+result.partidos[i].nombre+"</h3>\
                            </div>\
                        </div>\
                        </div>\
                    </div>\
                    <div class='col-md-4 col-lg-4 text-center mb-4 mb-lg-0'>\
                        <div class='d-inline-block'>\
                        <div class='bg-black py-2 px-4 mb-2 text-white d-inline-block rounded'><span class='h5'>"+result.partidos[i].goles+":"+result.partidos[i+1].goles+"</span></div>\
                        </div>\
                    </div>\
                    <div class='col-md-4 col-lg-4 text-center text-lg-right'>\
                        <div class=''>\
                        <div class='d-block d-lg-flex align-items-center'>\
                            <div class='image image-small ml-lg-3 mb-3 mb-lg-0 order-2'>\
                            <img src='bd/"+result.partidos[i+1].escudo+"' alt='No encontrado' class='img-fluid'>\
                            </div>\
                            <div class='text order-1 w-100'>\
                            <h3 class='h5 mb-0 text-black'></h3>\
                            <span class='text-uppercase small country'>"+result.partidos[i+1].nombre+"</span>\
                            </div>\
                        </div>\
                        </div>\
                    </div>\
                    </div>";
                pos=0;
                if(cont==3) pos=1;
                if(cont==6) pos=2;
                $(contPartidos.children()[pos].children[0].children[0]).append($(partido));   
            }
            
            
            
        })
    }
    setInterval(function(){
        $(contPartidos.children()[0].children[0].children[0]).append("<div class='loader'></div>");
        pideUltimoPartidoTorneo();
    },60000);

    $(contPartidos.children()[0].children[0].children[0]).append("<div class='loader'></div>");
    pideUltimoPartidoTorneo();

    function pideIndex2(){
        $.getJSON("/api/obtenIndex2",function(result){
            for(i=0;i<result.equipos.length;i++)
            {   
                $(contEquipos.children()[0].children[0].children[i].children[0].children[1].children[0]).attr("style","background-image: url('bd/"+result.equipos[i].escudo+"'); height:50vh; background-position: center; background-repeat: no-repeat; background-size: 50%;")
                $(contEquipos.children()[0].children[0].children[i].children[0].children[0].children[0].children[0].children[0]).text(result.equipos[i].nombre_equipo);
                $(contEquipos.children()[0].children[0].children[i].children[0].children[0].children[0].children[1]).text("Capitán: "+result.equipos[i].nombre_jugador+" "+result.equipos[i].apellidos);
            }
            for(i=0;i<result.pistas.length;i++)
            {
                contPistas.children()[i].children[0].children[0].children[0].src="bd/"+result.pistas[i].imagen;
                contPistas.children()[i].children[0].children[1].children[0].children[0].innerText=result.pistas[i].nombre;
                descrip = result.pistas[i].descripcion;
                $(contPistas.children()[i].children[0].children[1].children[0]).append(descrip);
            }
        })
    }
    pideIndex2();
})