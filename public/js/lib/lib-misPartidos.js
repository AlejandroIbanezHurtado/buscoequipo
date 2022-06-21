$(function(){
    var contPartidos = $("#contPrinci");
    const fecha_actual = new Date();
    contPartidos.append($("<div class='loader' id='cargando'></div>"))
    $.getJSON("/api/obtenMisPartidos/",function(result){
        console.log(result)
            $(".loader").remove();
            equipos  = result
            for(i=0;i<result.partidos.length;i++) if(result.partidos[i].goles==null) result.partidos[i].goles=0;
            

            //obtenemos los id de los partidos que no se repiten dos veces(partidos a la espera)
            ids_partidos = [];
            for(i=0;i<result.partidos.length;i++) ids_partidos.push(result.partidos[i].partido_id);
            tempArray = ids_partidos.sort();
            duplicados=[];
            for (let i = 0; i < tempArray.length; i++) {
                if (tempArray[i + 1] === tempArray[i]) {
                  duplicados.push(tempArray[i]);
                }
              }
            uniqueArray = tempArray.filter(function(item, pos, self) {
                return self.indexOf(item) == pos;
            })
            diferencia = uniqueArray.filter(x => !duplicados.includes(x));
            
            //recorremos y partimos el array en dos para insertar un equipo vacío(a la espera)
            console.log("Buenos")
            console.log(result.partidos)
            if(diferencia.length>0)
            {
                parts = result.partidos;
                parts1=null;
                parts2=null;
                nuevo=parts;
                
                    for(w=0;w<diferencia.length;w++)
                    {
                        for(i=0;i<parts.length;i++)
                        {
                            // debugger
                            if(diferencia[w]==nuevo[i].partido_id)
                            {
                                ejemplo = {"partido_id":nuevo[i].partido_id,"equipo_id":null,"nombre":"A la espera","escudo":"interrogacion.png","goles":"0","fecha":nuevo[i].fecha};
                                parts1 = nuevo.slice(0,i+1);
                                parts2 = nuevo.slice(i+1,nuevo.length);
                                parts1.push(ejemplo)
                                nuevo = parts1;
                                for(q=0;q<parts2.length;q++) nuevo.push(parts2[q]);
                                i=parts.length;
                            }
                        }
                    }
                result.partidos=nuevo;
            }
            
            j=0;
            for(i=0;i<result.partidos.length-1;i=i+2)
            {
                jugado="";
                fecha_ini = new Date(result.partidos[i].fecha)
                if(fecha_actual<fecha_ini) jugado = "<div><small class='text-danger'>Por jugar</small></div>";
                partido = $("<div class='row bg-white align-items-center text-center ml-0 mr-0 py-4 match-entry' partido_id="+result.partidos[i].partido_id+">\
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
                <div class='col-md-4 col-lg-4 text-center mb-4 mb-lg-0'>"+
                    jugado+
                    "<div class='d-inline-block'>\
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
                    tipo="permanente"
                    if(parseInt(contPartidos.attr("perma"))==0) tipo="temporal"
                    window.location.href="/partido/"+tipo+"/"+$(this).attr("partido_id")
                })
                contPartidos.append(partido);
                j=j+2;
                
            }
    })
})