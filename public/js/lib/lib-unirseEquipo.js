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
                $("#boton").removeClass("d-none")
                if(result2[0].email===result.jugadores[i].email)
                {
                    $("#boton").attr("equipo","dentro").attr("class","btn btn-danger my-4").text("Salir");
                }
            }
            $("#boton").on("click",function(event){
                event.preventDefault();
                if($(this).attr("equipo")==="dentro")
                {
                    if(result2[0].email==result.jugadores[0].email)
                    {
                        $.post("/api/borraJugadorEnEquipoCapitan/"+id,
                        {
                            id_equipo: id
                        },function(data){
                            data = JSON.parse(data);
                            if(data.id_nuevo_capitan==null) window.location.href="/equipos";
                            $("#modalHora").find(".modal-body").children().remove();
                            $("#modalHora").find(".modal-body").append("<h2>AVISO DEL SISTEMA</h2>");
                            $("#modalHora").find(".modal-body").append("<p>"+data.mensaje+"</p>");
                            $("#modalHora").modal("show");
                            $("#contJugadores").find("[id_jugador='"+result2[0].id+"']").remove();
                            $("#contJugadores").find("[id_jugador='"+data.id_nuevo_capitan+"']").addClass("alert-info");
                            nombre = $("#contJugadores").find("[id_jugador='"+data.id_nuevo_capitan+"']").find("span").text();
                            $("#contJugadores").find("[id_jugador='"+data.id_nuevo_capitan+"']").find("span").text("Capitán - "+nombre);
                        })
                        $(this).attr("equipo","fuera").attr("class","btn btn-success my-4").text("Unirse");
                    }
                    else
                    {
                        $.post("/api/borraJugadorEnEquipo/"+id,
                        {
                            id_equipo: id
                        },function(data){
                            $("#modalHora").find(".modal-body").children().remove();
                            $("#modalHora").find(".modal-body").append("<h2>AVISO DEL SISTEMA</h2>");
                            $("#modalHora").find(".modal-body").append("<p>"+data+"</p>");
                            $("#modalHora").modal("show");
                        })
                        $(this).attr("equipo","fuera").attr("class","btn btn-success my-4").text("Unirse");
                        $("#contJugadores").find("[id_jugador='"+result2[0].id+"']").remove();
                    }
                    
                }
                else
                {
                    $.post("/api/insertaJugadorEnEquipo/"+id,
                    {
                        id_equipo: id
                    },function(data){
                        $("#modalHora").find(".modal-body").children().remove();
                        $("#modalHora").find(".modal-body").append("<h2>AVISO DEL SISTEMA</h2>");
                        $("#modalHora").find(".modal-body").append("<p>"+data+"</p>");
                        $("#modalHora").modal("show");
                    })
                    if(result2[0].imagen==null) result2[0].imagen="user.png";
                    elemento = $("<div id_jugador="+result2[0].id+" class='alert alert-secondary d-flex justify-content-between' role='alert'>\
                    <span>"+result2[0].nombre+" "+result2[0].apellidos+"</span> <img src='/bd/"+result2[0].imagen+"' style='max-width: 50px;'</img>\
                    </div>");
                    contEquipos.append(elemento);
                    $(this).attr("equipo","dentro").attr("class","btn btn-danger my-4").text("Salir");
                }
            })
        })
        for(i=0;i<result.jugadores.length;i++)
        {
            console.log(result)
            color = "alert-secondary";
            c = "";
            if(i==0)
            {
                color = "alert-info";
                c = "<strong>Capitán  -  </strong>"
            }
            elemento = $("<div id_jugador="+result.jugadores[i].jugador_id+"  class='alert "+color+" d-flex justify-content-between' role='alert'>\
            <span>"+c+result.jugadores[i].nombre+" "+result.jugadores[i].apellidos+"</span> <img src='/bd/"+result.jugadores[i].imagen+"' style='max-width: 50px;'</img>\
            </div>");
            contEquipos.append(elemento);
        }
    })
})