$(function(){
    var contPerma = $("#contEquiposPerma");
    var contTempo = $("#contEquiposTempo");
    var pagPerma = $("#paginadorPerma");
    var pagTempo = $("#paginadorTempo");

    function obtenEquiposPerma(pagina = 1, fila = 4, perma=1, contenedor,rm,paginador,b=0){
        $.getJSON("/api/obtenEquiposPermaPaginados/"+pagina+"/"+fila+"/"+perma,function(result){
            if(result.equipos.length!=0)
            {
                console.log(result);
                $("#"+rm).remove();
                if(b==1) 
                {
                    $(".fila-"+rm).remove();
                }
                eli = 0;
                if(perma==1) eli="1";
                if(perma==0) eli="2";
                fila=$("<div class='row justify-content-center fila-rm"+eli+"'><div>");
                fila2=$("<div class='row justify-content-center fila-rm"+eli+"'><div>");
                for(i=0;i<result.equipos.length;i++)
                {
                    color="bg-success";
                    if(result.equipos[i].numero>=12) color="bg-danger"
                    equipo = $("<div class='mb-4 mb-lg-0 col-6 col-md-4 col-lg-2 text-center'>\
                            <div class='player mb-5'>\
                            <span class='team-number "+color+"'>"+result.equipos[i].numero+"</span>\
                            <a href='/unete/equipo/"+result.equipos[i].id+"'><img src='bd/"+result.equipos[i].escudo+"' alt='Image' class='img-fluid image rounded-circle'></a>\
                            <h2>"+result.equipos[i].nombre_equipo+"</h2>\
                            <span class='position'>"+result.equipos[i].nombre_jugador+" "+result.equipos[i].apellidos+"</span>\
                            </div>\
                        </div>");
                    if(i>=4)
                    {
                        if(i<9) fila2.append(equipo);
                    }
                    else{
                        fila.append(equipo);
                    }
                }
                contenedor.append(fila);
                contenedor.append(fila2);
                if(b==0)
                {
                    for(i=0;i<Math.ceil(parseInt(result.total_equipos)/result.equipos.length);i++)
                    {
                        if(i<5)
                        {
                            if(i==0)
                            {
                                $(paginador.children()[0].children[0]).after($("<li class='active'><a>"+(i+1)+"</a></li>"))
                            }
                            else{
                                $(paginador.children()[0].children[i]).after($("<li><a>"+(i+1)+"</a></li>"))
                            }
                        }
                    }
                    paginador.attr("pag",1);
                }
                
            }
        })
    }
    programaClicks(pagPerma,"rm1",contPerma,1);
    programaClicks(pagTempo,"rm2",contTempo,0);
    function programaClicks(paginador,rm,cont,perma){
        paginador.find(".anterior").on("click",function(){
            if(parseInt($(this).parent().parent().attr("pag"))>1) 
            {
                obtenEquiposPerma(parseInt($(this).parent().parent().attr("pag"))-1,8,perma,cont,rm,paginador,1);
                $(this).parent().parent().attr("pag",parseInt($(this).parent().parent().attr("pag"))-1);
                $(this).parent().find(".active").removeClass("active");
                $($(this).parent().children()[parseInt($(this).parent().parent().attr("pag"))]).addClass("active");
            }
        })
        paginador.find(".siguiente").on("click",function(){ 
            if(parseInt($(this).parent().parent().attr("pag"))<$(this).parent().children().length-2)
            {
                obtenEquiposPerma(parseInt($(this).parent().parent().attr("pag"))+1,8,perma,cont,rm,paginador,1);
                $(this).parent().parent().attr("pag",parseInt($(this).parent().parent().attr("pag"))+1);
                $(this).parent().find(".active").removeClass("active");
                $($(this).parent().children()[parseInt($(this).parent().parent().attr("pag"))]).addClass("active");
            }
            
        })
    }
        // obtenEquiposPerma(parseInt($(this).parent().parent().attr("pag"))+1,8,perma,cont,rm,paginador,1);
        // $(this).parent().parent().attr("pag",parseInt($(this).parent().parent().attr("pag"))+1);
    
    
    obtenEquiposPerma(1,8,1,contPerma,"rm1",pagPerma);
    obtenEquiposPerma(1,8,0,contTempo,"rm2",pagTempo);
})