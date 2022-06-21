$(function(){
    var otro = $("#otro");
    var contCampos = $("#contCampos");
    var arrayid = window.location.href.split("/");
    var id = arrayid[arrayid.length-1];

    rellenaJugadores();
    otro.on("click",function(){
        n = parseInt(otro.attr("n"));
        n++;
         elemento = $("<div class='px-4 bg-negro'>\
         <hr class='mt-0 mb-4'>\
         <div class='row d-flex align-items-center'>\
             <div class='col-xl-4 col-md-4 col-lg-4'>\
                 <div class='card mb-4'>\
                 <div class='card-header'>Datos "+n+"</div>\
                     <div class='card-body'>\
                         <div class='mb-3'>\
                           <select class='form-select juga' aria-label='Default select example' name='jugador"+n+"'>\
                               <option selected>Cargando...</option>\
                           </select>\
                           <input type='number' name='minuto"+n+"' requiredminlength='1' maxlength='2' style='width: 30%'>\
                           <select class='form-select' aria-label='Default select example' name='tipo"+n+"'>\
                               <option selected value='g'>gol</option>\
                               <option value='a'>amarilla</option>\
                               <option value='r'>roja</option>\
                           </select>\
                         </div>\
                     </div>\
                 </div>\
             </div>\
         </div>\
     </div>");
     contCampos.append(elemento)
        otro.attr("n",n);
        rellenaJugadores();
    })


    function rellenaJugadores(){
        $.getJSON("/api/obtenJugadoresPorPartido/"+id,function(result){
            console.log(result)
            for(i=0;i<result.jugadores.length;i++)
            {
                $("select.juga").children().remove();
                $("select.juga").append($("<option value="+result.jugadores[i].jugador_id+"$"+result.jugadores[i].equipo_id+">amarilla</option>"))
            }
        })
    }
})