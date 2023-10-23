var app = new Vue({
    el: '#main',
    data(){
        return {
            cargando_:false,
            formAccion:{
                'persona_id':'',
                'motivo_id':'',
                'area_id':'616',
                'denominacion_id':'50',
                'cargo_id':'916',
                'tipo_contrato_id':'',
                'fecha_ingreso':'',
                'fecha_salida':'',
                'estado_id':'',
                'numero_partida_presupuestaria':'',
                'observacion':'',
                'edificio_id':'',
                // 'horario_id':''
               // 'area_id_previa':'',
               // 'denominacion_id_previa':'',
               // 'cargo_id_previa':'',
              //  'tipo_contrato_id_previa':''
            },
            bandera : true,
            oculta_accion:true,
            area_id: 0,
            areas:'',
            edificio_id: 0,
            edificios: '',
            edificio_anterior: null,
        }
    },
    created:function(){

    },
    methods:{

            limpiarAccion:function(motivo=null){



                $(".erroresInput").addClass('hidden');


                $("#identificacion_accion").val(null).change();
                if(motivo==null)
                $("#motivo_id").val('44').change();
                $("#area_id_accion").val('616').change();
                $("#cargo_id_accion").val('916').change();
                $("#denominacion_id_accion").val('50').change();
                $("#tipo_contrato_id_accion").val(null).change();
                $("#edificio_id_accion").val(null).change();
                this.formAccion.tipo_contrato_id='';
                this.formAccion.area_id='616';
                this.formAccion.denominacion_id='50';
                this.formAccion.cargo_id='916';
                this.formAccion.persona_id='';
                this.formAccion.fecha_ingreso='';
                this.formAccion.fecha_salida='';
                this.formAccion.numero_partida_presupuestaria='';
                this.formAccion.observacion='';
                this.formAccion.estado_id='DEFECTO';
                this.formAccion.edificio_id='';
                // this.formAccion.horario_id='';

                document.querySelector("input[name='desvinculacion']").checked=false;
                this.oculta_accion=true;
               /* $("#area_id_previa").val(null).change();
                $("#cargo_id_previa").val(null).change();
                $("#denominacion_id_previa").val(null).change();
                $("#tipo_contrato_id_previa").val(null).change();



                this.formAccion.tipo_contrato_id_previa='';
                this.formAccion.area_id_previa='';
                this.formAccion.denominacion_id_previa='';
                this.formAccion.cargo_id_previa='';
                this.formAccion.motivo_id_previa='';*/
            },
            async filtro_edificio(id) {

                //$("#edificio_id_accion").val(null).change();
                this.cargando = true;
                var urlKeeps = 'filtro_edificio';
                var fill = {
                    'area_id': id
                }
                //iniciar_modal_espera_edificio();
                await axios.post(urlKeeps, fill).then(response => {
                    if (response.data.status==200){
                        $("#edificio_id_accion").val(response.data.datos).change();
                    }
                    this.cargando = false;
                    //  parar_modal_espera_edificio();
                }).catch(error => {
                    alertToast("Error..., recargue la página", 3500);
                    this.cargando = false;
                });
            },
            async guardarAccion(){
                if($("#desvinculacion").is(':checked')&&(this.formAccion.fecha_salida==''||this.formAccion.fecha_salida==null)){
                    alertToast("Para desvincular debe colocar la fecha fin de gestión",3500);
                    return false;
                }

                this.formAccion.horario_id = $("#horario_id").val();
                if (buscarErroresInput())  return false;

                let result = await swal({
                    title: "Estás seguro de realizar esta accion",
                    text: "Al confirmar se grabaran los datos exitosamente",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Si!",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: false
                }, function (isConfirm) {
                    if (isConfirm) {
                                app.cargando_=true;
                                app.editar=true;
                                var urlKeeps='guardarAccion';
                                app.formAccion.desvinculacion=$("#desvinculacion").is(':checked');
                                axios.post(urlKeeps,app.formAccion).then(response=>{
                                app.cargando_=false;
                                    if(response.data.status=="200"){
                                        app.limpiarAccion();
                                        alertToastSuccess("Grabado Exitosamente",3500);
                                    }else{
                                        app.cargando_=false;
                                        alertToast(response.data.message,3500);
                                    }

                            }).catch(error => {
                                app.cargando_=false;
                                alertToast("Error en la carga",3500);
                            });
                    } else {
                        swal("Cancelado!", "No se registraron cambios...", "error");
                        return false;
                    }

                })
           },


    }
  })
