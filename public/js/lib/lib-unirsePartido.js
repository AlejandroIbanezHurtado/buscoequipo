$(function(){
    var contEquipo1 = $("#contEquipo1");
    var contEquipo2 = $("#contEquipo2");
    var contPista = $("#contPista");
    var arrayid = window.location.href.split("/");
    var id = arrayid[arrayid.length-1];
    var validator = null;

    var guardar = $("#botonGuardar");
    var nombre = $("#inputNombre");
    

    $.getJSON("/api/obtenPartido/"+id,function(result){
        result2 = JSON.parse(JSON.stringify(result));
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

        if(result2.permanente==true) mostrarBorrarPermanente();
        
        if(result.partido.length<2)
        {
            if(result2.permanente==false)
            {
                contEquipo2.attr("vacio",true);
                boton = $("<div class='text-center'>\
                <button type='button' id='btnUnirseTemporal' class='btn btn-success'>Unirse</button>\
                </div>")
                boton.on("click",function(){
                    unirseTemporal()
                })
                contPista.append(boton)
            }
            if(result2.permanente==true)
            {
                boton = $("<div class='text-center'>\
                <button type='button' id='btnUnirsePermanente' class='btn btn-success'>Unirse</button>\
                </div>")
                boton.on("click",function(){
                    unirsePermanente()
                })
                contPista.append(boton)
            }
            
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

    function unirsePermanente(){
        $.getJSON("/api/unirsePartidoPerma/"+id,function(result){
            res = "";
            if(result.clave==true)
            {
                $("button").remove();
                if(result.equipo.escudo==null) result.equipo.escudo="interrogacion.png";
                $(contEquipo2.children()[0]).text(result.equipo.nombre);
                $(contEquipo2.children()[1]).attr("src","/bd/"+result.equipo.escudo);
                rellenaJugador(contEquipo2,result.equipo.jugadores);
                $("#btnUnirsePermanente").remove();
                
                boton = $("<div class='text-center'>\
                <button type='button' id='btnSalir' class='btn btn-danger'>SALIR</button>\
                </div>")
                boton.on("click",function(){
                    salirPartido()
                })
                contPista.append(boton)
                res = result.respuesta;
            }
            else{
                res = result.respuesta;
            }

            $("#modalHora").find(".modal-body").children().remove();
            $("#modalHora").find(".modal-body").append("<h2>AVISO DEL SISTEMA</h2>");
            $("#modalHora").find(".modal-body").append("<p>"+res+"</p>");
            $("#modalHora").modal("show");
        })
        
    }

    function unirseTemporal(){
        if(contEquipo2.attr("vacio")=="true")
        {
            //crear equipo
            cuerpo = $("<p class='bg-info text-white'>Al no existir equipo temporal, tendrás que crearlo y te asiganarás automáticamente como capitán</p><div class='card-header'>Escudo del equipo</div>\
                <div class='card-body text-center'>\
                    <img class='rounded mb-2' id='imagen' src='' width='250px'>\
                    <br>\
                    <div class='row'>\
                        <div class='col-md-6'>\
                            <label for='file' class='text-dark rounded p-3' id='archivo'>\
                                <i class='fas fa-cloud-upload-alt' style='font-size:36px'></i>\
                            </label>\
                            <input id='file' type='file' onchange='previewFile();' accept='image/png, image/gif, image/jpeg' name='imagen'/>\
                        </div>\
                        <div class='col-md-6'>\
                        <svg xmlns='http://www.w3.org/2000/svg' width='50' height='50' fill='currentColor' class='bi bi-trash3' viewBox='0 0 16 16' id='eliminarFoto'>\
                            <path d='M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z'/>\
                        </svg>\
                        </div>\
                    </div>\
                </div>\
                <div class='card-header'>Nombre del equipo</div>\
                <div class='card-body'>\
                <form id='formulario' class='needs-validation'>\
                  <div class='mb-3'>\
                      <input class='form-control' id='inputNombre' name='nombre'>\
                  </div>\
                </div>\
                </div>\
                <div class='col-xl-12 col-md-12 col-lg-12 my-4'>");
          btn = $("<button class='btn btn-success' type='button' id='botonGuardar'>Guardar cambios</button>")
        cuerpo2 = $("</div>\
        <div class='col-md-6' id='carga'></div>\
        </form>");
            $("#modalHora").find(".modal-body").children().remove();
            $("#modalHora").find(".modal-body").append("<h2>CREAR EQUIPO</h2>");
            $("#modalHora").find(".modal-body").append(cuerpo).append(btn).append(cuerpo2);
            $("#modalHora").modal("show");
            guardaEquipo(btn,id);
        }
    }

    function salirPartido(){
        $.getJSON("/api/borraDePartido/"+id,function(result){
            console.log(result)
            if(result.clave==true)
            {
                $("button").remove();
                $("#btnUnirsePermanente").remove();
                $("#modalHora").find(".modal-body").children().remove();
                $("#modalHora").find(".modal-body").append("<h2>AVISO DEL SISTEMA</h2>");
                $("#modalHora").find(".modal-body").append("<p>"+result.respuesta+"</p>");
                $("#modalHora").modal("show");
                if(result.perma==true)
                {
                    boton = $("<div class='text-center'>\
                    <button type='button' id='btnUnirsePermanente' class='btn btn-success'>Unirse</button>\
                    </div>")
                    boton.on("click",function(){
                        unirsePermanente()
                    })
                    contPista.append(boton)
                }
                else{
                    boton = $("<div class='text-center'>\
                    <button type='button' id='btnUnirseTemporal' class='btn btn-success'>Unirse</button>\
                    </div>")
                    boton.on("click",function(){
                        unirseTemporal()
                    })
                    contPista.append(boton)
                }
                $(contEquipo2.children()[0]).text("A la espera");
                $(contEquipo2.children()[1]).attr("src","/bd/interrogacion.png");
                contEquipo2.find(".alert").remove();
            }
            
        })
        
    }

    function mostrarBorrarPermanente(){
        $.getJSON("/api/mirarSiCapiEstaEnPartido/"+id,function(result){
            if(result==true) 
            {
                $("button").remove();
                boton = $("<div class='text-center'>\
                <button type='button' id='btnSalir' class='btn btn-danger'>SALIR</button>\
                </div>")
                boton.on("click",function(){
                    salirPartido()
                })
                contPista.append(boton);
            }
            
        })
    }
    
    $("#btnUnirseTemporal").on("click",function(){
        unirseTemporal()
    });
    $("#btnUnirsePermanente").on("click",function(){
        unirsePermanente()
    });
    $("#btnSalir").on("click",function(){
        salirPartido()
    })

    $.getJSON("/api/saberDetalle/"+id,function(result){
        if(result.respuesta==true) $("#contDetalle").append($("<a class='text-center' href=/detallePartido/"+id+">Rellenar detalle</a>"));
    })
})
function previewFile(input){
    var file = $("input[type=file]").get(0).files[0];
    var eliminar = $("#eliminarFoto");

    eliminar.on("click",function(){
        $('#file')[0].files[0]=null;
        $("#imagen").attr("src", "http://localhost:8000/images/interrogacion.png").css("width", "128px");
    })

    if(file){
        var reader = new FileReader();

        reader.onload = function(){
            $("#imagen").attr("src", reader.result).css("width", "300px").attr("ruta",reader.result);
        }

        reader.readAsDataURL(file);
    }
}

function guardaEquipo(boton,id){
    jQuery.validator.setDefaults({
        debug: true,
        success: "¡Correcto!"
    });

    
    validator = $( "#formulario" ).validate({
        rules: {
            nombre: {
            required: true,
            rangelength: [4, 25]
            }
        },
        messages: {
            nombre: {
                required: "Escribe un nombre",
                rangelength: "El nombre debe de tener entre 4 y 25 caracteres"
            }
        }
    });
    
    boton.on("click",function(){
        spinner = $("<div class='loader' id='cargando'></div>");
        if(validator.errorList.length==0 && $("#inputNombre").val()!="")
        {
            $("#carga").append(spinner);
            var formData = new FormData();
            var files = $('#file')[0].files[0];
            formData.append('file',files);
            formData.append('nombre',$("#inputNombre").val());
            $.ajax({
                url: '/api/creaEquipoTempo/'+id,
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                success: function(text){
                    $("#btnUnirseTemporal").remove();
                    text = JSON.parse(text);
                    $("#modalHora").modal("hide");
                    $("div").remove("#cargando");
                    $($("#contEquipo2").children()[0]).text(text.detalle.nombre);
                    $($("#contEquipo2").children()[1]).attr("src","/bd/"+text.detalle.escudo);
                    elemento = $("<div id_jugador="+text.detalle.jugadores[0].jugador_id+" class='alert alert-info d-flex justify-content-between' role='alert'>\
                    <span>Capitán - "+text.detalle.jugadores[0].nombre+" "+text.detalle.jugadores[0].apellidos+"</span> <img src='/bd/"+text.detalle.jugadores[0].imagen+"' style='max-width: 50px;'</img>\
                    </div>");
                    $("#contEquipo2").append(elemento);

                    $("#modalHora").find(".modal-body").children().remove();
                    $("#modalHora").find(".modal-body").append("<h2>AVISO DEL SISTEMA</h2>");
                    $("#modalHora").find(".modal-body").append("<p>"+text.respuesta+"</p>");
                    $("#modalHora").modal("show");
                }
            });
        }
        else{
            alert("Introcue un nombre válido");
        }
    })
}
