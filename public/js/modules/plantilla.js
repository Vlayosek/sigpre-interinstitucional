var arregloPlantilla=[];
var arregloInforme=[];
var arregloOpcionesAgregadas=[];
var URLactual = window.location.href;
var lugar=URLactual.split('/')[4];

function consultaPlantilla(id=null){
    var data = new FormData();
    data.append('id', $("#plantilla_id").val());
    data.append('informe_id', id);
    var objApiRest = new AJAXRestFilePOST('/inventario/consultaPlantilla', data);
    objApiRest.extractDataAjaxFile(function (_resultContent) {
        if (_resultContent.status == 200) {
            arregloPlantilla=_resultContent.message;
            arregloInforme=_resultContent.informe;
            recorridoArreglo();
            if(arregloPlantilla!=null){
                $("#cargaPlantilla").removeClass('hidden');
                formarPlantilla();
            }else
            $("#cargaPlantilla").addClass('hidden');
            
        }else{
            $("#cargaPlantilla").addClass('hidden');
        }
    });
}
function formarPlantilla(){
    $("#tabSecciones").html('');
    $("#panelSeccion").html('');
    
    $.each(arregloPlantilla.plantilla_seccion, function (_key, _value) {
        var id=_value.seccion.id;
        var descripcion=_value.seccion.descripcion;
        formarItem(_key,descripcion,id);
        formarParametrosSeccion(id,_value.seccion);
    });
}
function formarParametrosSeccion(id,arreglo){
    html='';
    html+='<div class="table-responsive"><table class="table-striped table-bordered" width="100%" border=0 id="dtmenuOpciones'+id+'">';
        if(arreglo.estado_parametro.length>0){
            html+='<thead>';
            html+='<th style="font-weight:bold">'+arreglo.descripcion;
            html+='</th>';
            $.each(arreglo.estado_parametro, function(key, value) {
                html+='<th style="font-weight:bold">'+value.descripcion+'</th>'
            });

            if(arreglo.caracteristica==1){
                html+='<th style="font-weight:bold;text-align:center">Caracteristicas';
                html+='</th>';
            }
            if(arreglo.observacion==1){
                html+='<th style="font-weight:bold;text-align:center">Observaciones';
                html+='</th>';
            }
        html+='</thead>';
        html+='<tbody>';
    
        $.each(arreglo.detalle_secciones, function(key, value) {
            var checked=value.activo==0?'':'checked';
            var idParametro=value.parametro.id;
            if(checked!=''){
                html+='<tr>';
                html+='<td>'+value.parametro.descripcion+'</td>';
                $.each(arreglo.estado_parametro, function(key, value) {
                    if(arreglo.estado_parametro.length>1){
                        var idCheck=0;
                        if(arregloOpcionesAgregadas.length==0){
                            if(key==0)
                            html+='<td style="text-align:center"><input checked type="radio" data-id="'+idCheck+'" value="'+value.id+'" name="radio_'+idParametro+'"></td>'
                            else
                            html+='<td style="text-align:center"><input  type="radio" data-id="'+idCheck+'" value="'+value.id+'" name="radio_'+idParametro+'"></td>'
                        }else{
                            if(typeof arregloOpcionesAgregadas[idParametro]!=='undefined'){
                                checkedOpcion=arregloOpcionesAgregadas[idParametro].estado_parametro_id==value.id?'checked':'';
                                idCheck=arregloOpcionesAgregadas[idParametro].id;
                            }
                            else{
                                checkedOpcion='';
                                idCheck=0;

                            }
                          
                            html+='<td style="text-align:center"><input '+checkedOpcion+' data-id="'+idCheck+'" type="radio" value="'+value.id+'" name="radio_'+idParametro+'"></td>'
                        }
                    }
                    else{
                       var idCheck=0;
                        if(arregloOpcionesAgregadas.length==0)
                            html+='<td style="text-align:center"><input checked type="checkbox" data-id="'+idCheck+'" value="'+value.id+'" name="check_parametro_'+idParametro+'"></td>'
                        else{
                            if(typeof arregloOpcionesAgregadas[idParametro]!=='undefined'){
                                checkedOpcion=arregloOpcionesAgregadas[idParametro].estado==1?'checked':'';
                                idCheck=arregloOpcionesAgregadas[idParametro].id;
                            }
                            else{
                                checkedOpcion='';
                                idCheck=0;

                            }
                            html+='<td style="text-align:center"><input '+checkedOpcion+' data-id="'+idCheck+'" type="checkbox" value="'+value.id+'" name="check_parametro_'+idParametro+'"></td>'
    
                        }
                    }
                });
                if(arreglo.caracteristica==1){
                    var idCheck=0;
                    if(arregloOpcionesAgregadas.length==0)
                    html+='<td><input type="text" class="form-control" name="caracteristicas_'+idParametro+'" data-id="'+idCheck+'" id="caracteristicas_'+idParametro+'" placeholder="Caracteristicas">'
                    else{
                        if(typeof arregloOpcionesAgregadas[idParametro]!=='undefined'){
                            checkedOpcion=arregloOpcionesAgregadas[idParametro].caracteristicas==null?'':arregloOpcionesAgregadas[idParametro].caracteristicas;
                            idCheck=arregloOpcionesAgregadas[idParametro].id;
                        }
                        else{
                            checkedOpcion='';
                            idCheck=0;
                        }
                        
                        html+='<td><input type="text" class="form-control" data-id="'+idCheck+'" name="caracteristicas_'+idParametro+'" id="caracteristicas_'+idParametro+'" placeholder="Caracteristicas" value="'+checkedOpcion+'">'
                    }
                }
                if(arreglo.observacion==1){
                    var idCheck=0;
                    if(arregloOpcionesAgregadas.length==0)
                    html+='<td><input type="text" class="form-control" name="observacion_'+idParametro+'" data-id="'+idCheck+'" id="observacion_'+idParametro+'"placeholder="Obsersacion"></td>'
                    else{
                        if(typeof arregloOpcionesAgregadas[idParametro]!=='undefined'){
                            checkedOpcion=arregloOpcionesAgregadas[idParametro].observacion==null?'':arregloOpcionesAgregadas[idParametro].observacion;
                            idCheck=arregloOpcionesAgregadas[idParametro].id;
                        }
                        else{
                            checkedOpcion='';
                            idCheck=0;
                        }
    
                         html+='<td><input type="text" class="form-control" data-id="'+idCheck+'" name="observacion_'+idParametro+'" id="observacion_'+idParametro+'"placeholder="Obsersacion" value="'+checkedOpcion+'"></td>'
                    }
                }
                html+='</tr>';
    
            }
        });
    }else{
        var colspan=arreglo.detalle_secciones!=null?(arreglo.detalle_secciones.length):1;
        colspan=arreglo.columnas!=1?colspan:(arreglo.botones==1?(colspan+2):colspan);
        html+='<thead>';
        html+='<th style="font-weight:bold;text-align:center" colspan='+colspan+' >'+arreglo.descripcion;
        html+='</th>';
        html+='</thead>';
        html+='<tbody>';
        if(arreglo.columnas!=1){
            $.each(arreglo.detalle_secciones, function(key, value) {
                var idParametro=value.parametro.id;
                var idCheck=0;
                html+='<tr>';
                if(arregloOpcionesAgregadas.length==0)
                html+='<td style="font-weight:bold;text-align:center"><textarea data-id="'+idCheck+'" name="input_observacion_'+value.parametro_id+'" class="form-control-t1"></textarea>';
                else{
                    idCheck=arregloOpcionesAgregadas[idParametro].id;
                    html+='<td style="font-weight:bold;text-align:center"><textarea data-id="'+idCheck+'" name="input_observacion_'+value.parametro_id+'" class="form-control-t1">'+arregloOpcionesAgregadas[idParametro].observacion+'</textarea>';
                }
                html+='</td>';
                html+='</tr>';
            });
        }else{
            html+='<tr>';
            if(arreglo.botones==1){
                html+='<td style="font-weight:bold;text-align:center">';
                html+='</td>';
            }
          
            $.each(arreglo.detalle_secciones, function(key, value) {
                html+='<td style="font-weight:bold;text-align:center">';
                html+=value.parametro.descripcion;
                html+='</td>';
            });
            if(arreglo.botones==1){
                html+='<td style="font-weight:bold;text-align:center">';
                html+='</td>';
            }
            html+='</tr>';
            html+='<tr id="1" class="tr_clone">';
            $.each(arreglo.detalle_secciones, function(key, value) {
                var idCheck=0;
                if(key==0&&arreglo.botones==1){
                    html+='<td style="font-weight:bold;text-align:center">';
                    html+='<button class="btn btn-danger btn-xs" onclick="eliminartrClone(this)"><i class="fa fa-minus"></i></button>';
                    html+='</td>';
                }
                html+='<td style="font-weight:bold;text-align:center">';
                //html+='<input type="text" data-id="'+idCheck+'" id="'+value.parametro.id+'_1" name="input_actividad_'+key+'_'+value.parametro_id+'">';
                var clase=arreglo.botones!=1?'form-control-t1':'form-control form-control-sb';
                html+='<textarea class="'+clase+'" data-id="'+idCheck+'" id="'+value.parametro.id+'_1" name="input_actividad_'+value.parametro_id+'"></textarea>';
                html+='</td>';
            });
            if(arreglo.botones==1){
                html+='<td style="font-weight:bold;text-align:center">';
                html+='<button class="btn btn-primary btn-xs tr_clone_add" onclick="trClone(this)"><i class="fa fa-plus"></i></button>';
                html+='</td>';
            }
     

            html+='</tr>';

        }
       
    }
  
    html+='</tbody>';
    html+='</table></div>';
    $("#tab"+id+"").html(html);

}
function formarContenido(id,inicio){
    html='';
    if(inicio==0){
        html+='   <div class="tab-pane active" id="tab'+id+'">';
        html+=' </div>';
    }else{
        html+='   <div class="tab-pane" id="tab'+id+'">';
        html+=' </div>';
    }
    $("#panelSeccion").append(html);
}
function formarItem(inicio,descripcion,id){
    html='';
    if(inicio==0){
        html+='<li class="nav-item active">';
        html+='<a class="nav-link active" href="#tab'+id+'" data-toggle="tab"><span class="escritorio">'+descripcion+'</span><span class="movil">'+(inicio+1)+'</span></a>';
      
    }else{
        html+='<li class="nav-item ">';
        html+='<a class="nav-link " href="#tab'+id+'" data-toggle="tab"><span class="escritorio">'+descripcion+'</span><span class="movil">'+(inicio+1)+'</span></a>';
    }
    html+='</li>';
    $("#tabSecciones").append(html);
    formarContenido(id,inicio);
    return 1;
}  
var regex = /^(.*)(\d)+$/i;
var cindex = 1;
function eliminartrClone(e){
    var $tr    = $(e).closest('.tr_clone');
    $tr.remove();
}
function trClone(e) {
    var $tr    = $(e).closest('.tr_clone');
    var $clone = $tr.clone(true);
    cindex++;
    $clone.find(':input').val('');
    $clone.attr('id', cindex); //update row id if required
    //update ids of elements in row
    $tr.find("textarea").each(function() {
            var id = this.id.split('_')[0]+'_'+cindex;
            this.id = id;
    });
    $tr.after($clone);
}