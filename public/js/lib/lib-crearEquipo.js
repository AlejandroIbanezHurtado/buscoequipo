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
            rangelength: [6, 20]
          }
        },
        messages: {
            nombre: {
                required: "Escribe un nombre",
                rangelength: "El nombre debe de tener entre 6 y 20 caracteres"
            }
        }
    });

    guardar.on("click",function(){
        console.log(validator)
        spinner = $("<div class='loader'></div>");
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
                complete: function(data){
                    console.log(data);
                    window.location.href="/mis/equipos";
                }
            });
        }
        // spinner = $("<div>").addClass("spinner-border text-danger").attr("id","cargando").attr("role","status").append($("span").addClass("sr-only").text("Loading..."));
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