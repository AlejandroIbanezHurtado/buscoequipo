$(function(){
    var contPerma = $("#contEquiposPerma");
    var contTempo = $("#contEquiposTempo");
    var pagPerma = $("#paginadorPerma");
    var pagTempo = $("#paginadorTempo");

    function obtenEquiposPerma(pagina = 1, fila = 4, perma=1, contenedor,rm){
        $.getJSON("/api/obtenEquiposPermaPaginados/"+pagina+"/"+fila+"/"+perma,function(result){
            console.log(result);
            $("#"+rm).remove();
            fila=$("<div class='row justify-content-center'><div>");
            fila2=$("<div class='row justify-content-center'><div>");
            for(i=0;i<result.equipos.length;i++)
            {
                color="bg-success";
                if(result.equipos[i].numero>=12) color="bg-danger"
                equipo = $("<div class='mb-4 mb-lg-0 col-6 col-md-4 col-lg-2 text-center'>\
                        <div class='player mb-5'>\
                        <span class='team-number "+color+"'>"+result.equipos[i].numero+"</span>\
                        <a href='/unete/equipo/"+result.equipos[i].id+"'><img src='bd/"+result.equipos[i].escudo+"' alt='Image' class='img-fluid image rounded-circle'></a>\
                        <h2>"+result.equipos[i].nombre_equipo+"</h2>\
                        <span class='position'>"+result.equipos[i].nombre_jugador+" "+result.equipos[i].apellidos+"</span>\
                        </div>\
                    </div>");
                if(i>=4)
                {
                    if(i<9) fila2.append(equipo);
                }
                else{
                    fila.append(equipo);
                }
            }
            contenedor.append(fila);
            contenedor.append(fila2);
        })
    }

    obtenEquiposPerma(1,8,0,contTempo,"rm1");
    obtenEquiposPerma(1,8,1,contPerma,"rm2");
})