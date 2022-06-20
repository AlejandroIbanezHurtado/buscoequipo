$(function(){
    var cont = $("#contPrinci")
    spinner = $("<div class='loader' id='cargando'></div>");
    cont.append(spinner);
    $.getJSON("/api/obtenPistas",function(result){
        $("#cargando").remove()
        console.log(result)
        for(i=0;i<result.pistas.length;i++)
        {
            elemento = $("<div class='site-section' data-aos='fade-up'>\
            <div class='container'>\
              <div class='row align-items-center'>\
                <div class='col-md-6'>\
                  <img src='/bd/"+result.pistas[i].imagen+"' alt='Image' class='img-fluid'>\
                </div>\
                <div class='col-md-6 pl-md-5'>\
                  <h2 class='text-black'>"+result.pistas[i].nombre+"</h2>\
                  <p class='lead'>"+result.pistas[i].descripcion+"</p>\
                  <div id='map"+i+"' style='width: 600px; height: 400px;'></div>\
                  </div>\
              </div>\
            </div>\
          </div>");
          cont.append(elemento)
          c1 = result.pistas[i].coordenadas.split(",")[0];
          c2 = result.pistas[i].coordenadas.split(",")[1];
          mapa(i,parseFloat(c1),parseFloat(c2), result.pistas[i].nombre);
        }
        
    })
})
function mapa(n, c1, c2, pista){
    var nombre = "map"+n;
    var map = L.map(nombre).setView([c1, c2], 16);
    var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
    var marker = L.marker([c1, c2]).addTo(map)
    .bindPopup("<b>"+pista+"</b><br />").openPopup();
}