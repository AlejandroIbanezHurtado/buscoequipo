$(function(){
    var contProxPartido = $("#contProxPartido");
    var contPartidos = $("#contPartidos");
    var paginador = $("#paginador");
    
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

    function rellenaEquipos(pagina=1, fila=5, perma=1, orden="asc"){
        contPartidos.empty();
        contPartidos.append("<div class='loader'></div>")
        $.getJSON("/api/obtenPartidosPag/"+pagina+"/"+fila+"/"+perma+"/"+orden,function(result){
            $(".loader").remove();
            paginador.attr("pag",pagina);
            paginador.attr("total",result.total/2);
            equipos  = result
            console.log(result)
            for(i=0;i<result.partidos.length;i++) if(result.partidos[i].goles==null) result.partidos[i].goles=0;
            j=0;
            for(i=0;i<result.partidos.length-1;i=i+2)
            {
                // debugger
                partido = $("<div class='row bg-white align-items-center ml-0 mr-0 py-4 match-entry' partido_id="+result.partidos[i].partido_id+">\
                <div class='col-md-4 col-lg-4 mb-4 mb-lg-0'>\
                    <div class='text-center text-lg-left'>\
                    <div class='d-block d-lg-flex align-items-center'>\
                        <div class='image image-small text-center mb-3 mb-lg-0 mr-lg-3'>\
                        <img src='/bd/"+result.partidos[i].escudo+"' alt='No encontrado' class='img-fluid'>\
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
                    <div><small>"+result.partidos[j].fecha+"</small></div>\
                </div>\
                <div class='col-md-4 col-lg-4 text-center text-lg-right'>\
                    <div class=''>\
                    <div class='d-block d-lg-flex align-items-center'>\
                        <div class='image image-small ml-lg-3 mb-3 mb-lg-0 order-2'>\
                        <img src='/bd/"+result.partidos[i+1].escudo+"' alt='No encontrado' class='img-fluid'>\
                        </div>\
                        <div class='text order-1 w-100'>\
                        <h3 class='h5 mb-0 text-black'></h3>\
                        <span class='text-uppercase small country'>"+result.partidos[i+1].nombre+"</span>\
                        </div>\
                    </div>\
                    </div>\
                </div>\
                </div>");
                partido.on("click",function(){
                    window.location.href="/partido/permanente/"+$(this).attr("partido_id")
                })
                contPartidos.append(partido);
                j=j+2;
            }
            
        })
    }

    function paginacion(pagina=1, fila=5, perma=1, orden="asc")
    {
        paginador.find(".anterior").on("click",function(){
            total = parseInt(paginador.attr("total"))
            pagina = parseInt(paginador.attr("pag"))-1
            if(pagina+1>1) 
            {
                rellenaEquipos(pagina, fila, perma, orden);
                paginador.attr("pag",pagina);
                $("li.numero").text(pagina);
            }
        })
    
        paginador.find(".siguiente").on("click",function(){
            total = parseInt(paginador.attr("total"))
            pagina = parseInt(paginador.attr("pag"))+1
            if(pagina-1<Math.ceil(total/3)) 
            {
                rellenaEquipos(pagina, fila, perma, orden);
                paginador.attr("pag",pagina);
                $("li.numero").text(pagina);
            }
        })  
    }

    rellenaEquipos(1,3,1,"asc");
    paginacion(1,3,1,"asc");

    $("select").on("change",function(){
        orden = $("select").val();
        rellenaEquipos(1,3,1,orden);
        paginacion(1,3,1,orden);
    })
        
})