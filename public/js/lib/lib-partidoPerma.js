$(function(){
    var contEquipo1 = $("#contEquipo1");
    var contEquipo2 = $("#contEquipo2");
    var contPista = $("#contPista");
    var arrayid = window.location.href.split("/");
    var id = arrayid[arrayid.length-1];

    $.getJSON("/api/obtenPartido/"+id,function(result){
        result2 = JSON.parse(JSON.stringify(result));
        console.log(result2);
        for(i=0;i<result.partido.length;i++)
        {
            $($("#contEquipo"+(i+1)).children()[0]).text(result.partido[i].nombre)
            $($("#contEquipo"+(i+1)).children()[1]).attr("src","/bd/"+result.partido[i].escudo)
        }
        gol1 ="0";
        gol2="0";
        if(typeof result.resultados[0] != "undefined") gol1 = result.resultados[0].goles;
        if(typeof result.resultados[1] != "undefined") gol2 = result.resultados[1].goles;

        
            $(contPista.children()[0]).removeClass("d-none").text(gol1+":"+gol2)
        
        $(contPista.children()[1]).text(result.partido[0].nombre_pista)
        $(contPista.children()[2]).text(result.partido[0].fecha_ini)
        $(contPista.children()[3]).attr("src","/bd/"+result.partido[0].imagen)
        if(result.partido.length<2)
        {
            contEquipo2.append($("<div class='text-center'>\
            <button type='button' class='btn btn-success'>Unirse</button>\
          </div>"))
        }
        for(i=0;i<result.jugadores1.length;i++) if(result.jugadores1[i].imagen==null) result.jugadores1[i].imagen="user.png";
        rellenaJugador(contEquipo1, result.jugadores1);
        if(typeof result.jugadores2 != "undefined") 
        {
            for(i=0;i<result.jugadores2.length;i++) if(result.jugadores2[i].imagen==null) result.jugadores2[i].imagen="user.png";
            rellenaJugador(contEquipo2, result.jugadores2);
        }
        if(typeof result.detalles != "undefined")
        {
            for(i=0;i<result2.detalles.length;i++)
            {
                // console.log(result2.detalles[i])
                gol="";
                amarilla="";
                roja="";
                if(result2.detalles[i].gol=="1") gol = "<img src='/images/balon.png' width='20px' alt='gol' class='mx-3'></img>";
                if(result2.detalles[i].amarilla=="1") amarilla = "<img src='/images/amarilla.png' width='20px' alt='tarjeta amarilla' class='mx-3'></img>";
                if(result2.detalles[i].roja=="1") roja = "<img src='/images/roja.png' width='20px' alt='tarjeta roja' class='mx-3'></img>";
                
                contPista.append($("<div><span class='h3'>"+result.detalles[i].minuto+"' - </span>"+result.detalles[i].nombre+" "+result.detalles[i].apellidos+" "+gol+amarilla+roja+"</div>"))
            }
        }

    })

    function rellenaJugador(cont, jugadores)
    {
        for(i=0;i<jugadores.length;i++)
        {
            color = "secondary";
            capitan = "";
            if(i==0) 
            {
                capitan = "Capitán  -  "
                color="info";
            }
            cont.append($("<div class='alert alert-"+color+" d-flex justify-content-between' role='alert'>\
            <span>"+capitan+jugadores[i].nombre+" "+jugadores[i].apellidos+"</span> <img src='/bd/"+jugadores[i].imagen+"' style='max-width: 50px;'</img>\
            </div>"));
        }
    }

    function unirse(){
        $.getJSON("/api/unirsePartidoPerma"+id,function(result){
            a = result;
        })
    }

    // function mostrarResultados(resultados){
    //     for(i=0;i<resultados.length;i++)
    // }
    
})