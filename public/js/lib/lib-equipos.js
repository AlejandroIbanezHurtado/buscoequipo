$(function(){
    var contPerma = $("#contEquiposPerma");
    var contTempo = $("#contEquiposTempo");
    var pagPerma = $("#paginadorPerma");
    var pagTempo = $("#paginadorTempo");

    function obtenEquiposPerma(pagina = 1, fila = 8){
        $.getJSON("/api/obtenEquiposPermaPaginados/"+pagina+"/"+fila,function(result){
            console.log(result);
            $(contPerma.children()[1]).empty();
            for(j=0;j<2;j++)
            {
                fila=$("<div class='row justify-content-center'><div>");
                for(i=0;i<result.equipos.length;i++)
                {
                    equipo = $("<div class='mb-4 mb-lg-0 col-6 col-md-4 col-lg-2 text-center'>\
                                <div class='player mb-5'>\
                                <span class='team-number'>8</span>\
                                <img src='bd/"+result.equipos[i].escudo+"' alt='Image' class='img-fluid image rounded-circle'>\
                                <h2>"+result.equipos[i].nombre_equipo+"</h2>\
                                <span class='position'>"+result.equipos[i].nombre_jugador+" "+result.equipos[i].apellidos+"</span>\
                                </div>\
                            </div>");
                    fila.append(equipo);
                }
                $(contPerma.children()[1]).append(fila)
            }
        })
    }

    obtenEquiposPerma();
})