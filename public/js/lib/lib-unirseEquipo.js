$(function(){
    var contEquipos = $("#contJugadores");
    var imagen = $("#imagen");
    var titulo = $("h1");
    var arrayid = window.location.href.split("/");
    var id = arrayid[arrayid.length-1];
    $("form").attr("id_equipo",id);
    $.getJSON("/api/obtenEquipo/"+id,function(result){
        titulo.text(result.equipos[0].nombre);
        imagen.attr("src","/bd/"+result.equipos[0].escudo);
    })

    $.getJSON("/api/obtenJugadoresPorEquipo/"+id,function(result){
        $.getJSON("/api/dameSesion/",function(result2){
            for(i=0;i<result.jugadores.length;i++)
            {
                if(result2===result.jugadores[i].email)
                {
                    $(".btn-success").remove();
                    $("form").append($("<button class='btn btn-danger my-4' id='botonSalir'>Salir</button>").on("click",function(event){
                            event.preventDefault();
                            $.post("/api/borraJugadorEnEquipo/"+id,
                            {
                                id_equipo: id
                            },function(data){
                                $("#modalHora").find(".modal-body").children().remove();
                                $("#modalHora").find(".modal-body").append("<h2>AVISO DEL SISTEMA</h2>");
                                $("#modalHora").find(".modal-body").append("<p>"+data+"</p>");
                                $("#modalHora").modal("show");
                            })
                        }))
                }
                
            }
        })
        for(i=0;i<result.jugadores.length;i++)
        {
            color = "alert-secondary";
            c = "";
            if(i==0)
            {
                color = "alert-info";
                c = "<strong>Capitán  -  </strong>"
            }
            elemento = $("<div class='alert "+color+" d-flex justify-content-between' role='alert'>\
            <span>"+c+result.jugadores[i].nombre+" "+result.jugadores[i].apellidos+"</span> <img src='/bd/"+result.jugadores[i].imagen+"' style='max-width: 50px;'</img>\
            </div>");
            contEquipos.append(elemento);
            
        }
    })

    $("#boton").on("click",function(event){
        event.preventDefault();
        $.post("/api/insertaJugadorEnEquipo/"+id,
        {
            id_equipo: id
        },function(data){
            $("#modalHora").find(".modal-body").children().remove();
            $("#modalHora").find(".modal-body").append("<h2>AVISO DEL SISTEMA</h2>");
            $("#modalHora").find(".modal-body").append("<p>"+data+"</p>");
            $("#modalHora").modal("show");
        })
    })
})