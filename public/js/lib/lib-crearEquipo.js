$(function(){
    var guardar = $("#botonGuardar");
    var nombre = $("#inputNombre");

    jQuery.validator.setDefaults({
        debug: true,
        success: "¡Correcto!"
    });
    var validator = $( "#formulario" ).validate({
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

    guardar.on("click",function(){
        spinner = $("<div class='loader' id='cargando'></div>");
        if(validator.errorList.length==0 && $("#inputNombre").attr("value")!="")
        {
            $("#carga").append(spinner);
            var formData = new FormData();
            var files = $('#file')[0].files[0];
            formData.append('file',files);
            formData.append('nombre',nombre.val());
            $.ajax({
                url: '/api/creaEquipoPerma',
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                success: function(text){
                    text = JSON.parse(text);
                    $("div").remove("#cargando");
                    $("#modalHora").find(".modal-body").children().remove();
                    $("#modalHora").find(".modal-body").append("<h2>AVISO DEL SISTEMA</h2>");
                    $("#modalHora").find(".modal-body").append("<p>"+text+"</p>");
                    $("#modalHora").modal("show");
                    if(text=="EL EQUIPO SE HA CREADO CORRECTAMENTE") window.setInterval(window.location.href="/mis/equipos", 4500);
                }
            });
        }
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