    var app = new Vue({
    el: '#main',
    data: {
            formCrear: {
                'id':0,
                'usuario_id':'',
                'institucion_id':[],
                },
        },
        methods: {
            limpiarForm:function(){
                this.formCrear.id=0;
                this.formCrear.usuario_id='';
                this.formCrear.institucion_id=[];
                resetarModal();
              
            },
            async confirmar() {
                let error="";
                if(this.formCrear.usuario_id==''||this.formCrear.usuario_id==null)
                error="\n Debe seleccionar un usuario";
                if(this.formCrear.institucion_id==''||this.formCrear.institucion_id==null)
                error="\n Debe seleccionar por lo menos una institución";

                if (error.length > 0) {
                    swal(" ", error, "error");
                    return false;
                } else {
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
                            var url = 'guardarAsignacion';
                            var data = new FormData();
                            data.append('id', app.formCrear.id);
                            data.append('usuario_id', app.formCrear.usuario_id);
                            data.append('institucion_id', app.formCrear.institucion_id);
    
                            cargarLoading();
                            axios.post(url, data).then(response => {
    
                                  $(".confirm").click()
                                    app.crear=false;
                                    app.formCrear.id = response.data.datos;
                                    datatableCargar();
    
                                    alertToastSuccess("Registro grabado exitosamente", 3500)
                            }).catch(error => {
                                $(".confirm").click()
                                swal("Cancelado!", "Error al grabar...", "error");
                            });
    
    
                        } else {
                            swal("Cancelado!", "No se registraron cambios...", "error");
                            return false;
                        }
                    });
                }
    
    
    
            },
            async editar(id,usuario_id,nombre){
                resetarModal();

                this.formCrear.id=id;
                var data=new FormData();
                data.append('usuario_id',usuario_id)
                var urlKeeps = 'editar';
                await axios.post(urlKeeps,data).then(response => {
                    var selectUsuario = document.querySelector('#usuario_id');
                    selectUsuario.options.add(new Option(nombre, usuario_id));
                     this.formCrear.usuario_id=usuario_id;
                    $("#usuario_id").val(usuario_id).change();
                   
                    var arregloSelect=[];
                    var select = document.querySelector('#institucion_id');
                    $.each(response.data.datos, function(key, value) {
                        select.options.add(new Option(key, value));
                        arregloSelect.push(value);
                    });
                    this.formCrear.institucion_id=arregloSelect;
                    $("#institucion_id").val(arregloSelect).change();
                });
            },
            async eliminar(id){
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
                        var url = 'eliminar';
                        var data = new FormData();
                        var fill={
                            'id':id
                        }
                        cargarLoading();
                        axios.post(url, fill).then(response => {
                                datatableCargar();
                                alertToastSuccess("Registro eliminado exitosamente", 3500)
                        }).catch(error => {
                            $(".confirm").click()
                            swal("Cancelado!", "Error al grabar...", "error");
                        });

                    } else {
                        swal("Cancelado!", "No se registraron cambios...", "error");
                        return false;
                    }
                });
            }
        
          
        },

});
