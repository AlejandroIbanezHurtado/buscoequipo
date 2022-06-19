$(function(){
    $( "#datepicker" ).datepicker({
        minDate: 0,
        firstDay: 1,
        beforeShowDay: function(date) {
           var day = date.getDay();
           return [(day != 0), ''];
       }
       });
    $("#datepicker").datepicker('setDate', 'today');
    $.getJSON("/api/obtenPistas",function(result){
        console.log(result)
        $("select").children().remove();
        for(i=0;i<result.pistas.length;i++) $("select").append($("<option value="+result.pistas[i].id+">"+result.pistas[i].nombre+"</option>"))
    })
    $( "#datepicker" ).datepicker( "option", "showAnim", "clip" );
    $(".btn").on("click",function(){
        var res="";
        datepicker = $("#datepicker").val();
        spinner = $("<div class='loader' id='cargando'></div>");
        fecha = new Date();
        time = $("#timepicker").val()+":00";
        date_completo = datepicker.replace("-","/")+" "+time;

        fecha_ini = new Date(date_completo);
        
        if(time!="" && (new Date(date_completo)>fecha) && datepicker!="")
        {
            $("#carga").append(spinner);
            var formData = new FormData();
            formData.append('pista',$("select").val());
            formData.append('fecha_ini',fecha_ini.getTime());
            formData.append('fecha_fin',fecha_ini.getTime());
            $.ajax(res,{
                url: '/api/crearPartidoPermanente',
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                complete: function(){
                    $("div").remove("#cargando");
                },
                success: function(response) {
                    response = JSON.parse(response);
                    console.log(response)
                    res = response.respuesta;
                    if(response.clave==true)
                    {
                        window.setInterval(window.location.href="/mis/partidos", 4500);
                    }
                    mostrarModal(res);
                },
                error: function(){
                    res = "ERROR no se ha podido crear el partido";
                    mostrarModal(res);
                }
            });
        }
        else{
            res = "Selecciona una fecha válida"
            mostrarModal(res);
        }
        
    })

    function mostrarModal(res){
        $("#modalHora").find(".modal-body").children().remove();
        $("#modalHora").find(".modal-body").append("<h2>AVISO DEL SISTEMA</h2>");
        $("#modalHora").find(".modal-body").append("<p>"+res+"</p>");
        $("#modalHora").modal("show");
    }
})