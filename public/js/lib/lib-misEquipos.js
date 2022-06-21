$(function(){
    var contenedor = $("#contPrinci");
    contenedor.append($("<div class='loader' id='cargando'></div>"))
    $.getJSON("/api/obtenMisEquipos/",function(result){
        for(i=0;i<result.equipos.length;i++)
        {
            $("#cargando").remove();
            equipo = $("<div class='mb-5 mb-lg-0 col-12 text-center w-100'>\
                <hr/>\
                <div class='player mb-5'>\
                <a href='/unete/equipo/"+result.equipos[i].equipo_id+"'><img src='/bd/"+result.equipos[i].escudo+"' alt='Image' class='img-fluid image rounded-circle'></a>\
                <h2>"+result.equipos[i].nombre+"</h2>\
                </div>\
            </div>");
          contenedor.append(equipo)
        }
        console.log(result)
    })
    
})