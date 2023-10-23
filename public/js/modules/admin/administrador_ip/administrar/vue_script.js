var app = new Vue({
    el: '#main',
    data() {
        return {
            cargando: false,
            habilitaEditar: false,
            formIp: {
                'id':0,
                'tipo':'',
                'seccion':'',
                'objeto1':0,
                'objeto2':0,
                'objeto3':0,
                'objeto4':0,
            },
            ip_registrar:'',
        }
    },
    created: function() {
        this.limpiarFormulario();
    },
    methods: {
        limpiarFormulario: function() {
            this.formIp.id=0;
            this.formIp.tipo='';
            this.formIp.seccion='';
            this.formIp.objeto1=0;
            this.formIp.objeto2=0;
            this.formIp.objeto3=0;
            this.formIp.objeto4=0;
            this.ip_registrar='';
            this.habilitaEditar=false;
            $("#tipo_ip").val(null).change();
            $("#seccion_ip").val(null).change();
            document.getElementById('ip_registrar_').style.color = '';
        },
        //AGREGANDO LA IP
        async agregarIp() {
            var error = "";
            if (document.getElementById('ip_registrar_').style.color == 'red') error += '\n Tiene errores en la Dirección Ip';
            if (this.ip_registrar == null || this.ip_registrar == '') error += '\n Debe ingresar una Ip';
            if (this.formIp.tipo == null || this.formIp.tipo == '' || this.formIp.tipo == 'SELECCIONE UNA OPCIÓN') error += '\n Debe seleccionar el tipo de ip';
            if (this.formIp.seccion == null || this.formIp.seccion == '' || this.formIp.seccion == 'SELECCIONE UNA OPCIÓN') error += '\n Debe seleccionar el tipo de sección';

            if (error.length > 0) {
                swal("Errores", error, "error");
                return false;
            }
            var urlKeeps = 'agregarIp';
            app.cargando = true;
            //iniciar_modal_espera();
            this.arregloIp = this.ip_registrar.split('.');
            this.formIp.objeto1 = this.arregloIp[0];
            this.formIp.objeto2 = this.arregloIp[1];
            this.formIp.objeto3 = this.arregloIp[2];
            this.formIp.objeto4 = this.arregloIp[3];
            await axios.post(urlKeeps,this.formIp).then(response => {
                app.cargando = false;
                this.limpiarFormulario();
                //parar_modal_espera();
                datatableCargarIpsAdministrar();
                alertToastSuccess("IP AGREGADA", 3500)
            }).catch(error => {
                app.cargando = false;
                swal("Cancelado!", "Error al grabar...", "error");
            });
        },
        //EDITAR IP
        async editarIp(id) {
            var urlKeeps = 'editarIp'; //setea la ruta
            var fill = {
                'id': id //busca el id y lo almacena en la var 'id'
            }
            app.cargando = true; //mientras busca la data, el indicador se activa "cargando"
            await axios.post(urlKeeps, fill)
            .then(response => {
                    this.limpiarFormulario();
                    app.cargando = false; //desaparece indicador de cargando
                    this.habilitaEditar=true;
                    this.formIp = response.data.datos; //llena el formulario con los datos
                    this.ip_registrar = this.formIp.objeto1 +"."+ this.formIp.objeto2 +"."+ this.formIp.objeto3 +"."+ this.formIp.objeto4;
                    $("#tipo_ip").val(this.formIp.tipo).change();
                    $("#seccion_ip").val(this.formIp.seccion).change();

                }).catch(error => {
                    app.cargando = false; //desaparece indicador de cargando
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },

        //ELIMINANDO FUNCIONARIO CON DISPOSITVO
        async eliminarIp(id) { //eliminar un registro 
            let result = await swal({
                    title: "Estás seguro de realizar esta accion",
                    text: "Al confirmar se eliminaran los datos exitosamente",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Si!",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: false
                },
                function(isConfirm) {
                    if (isConfirm) {
                        var urlKeeps = 'eliminarIp'; //setea la ruta del controlador
                        var fill = { 'id': id } //busca el id y lo almacena en la var 'id'
                        app.cargando = true; //mientras busca la data, el indicador se activa "cargando"
                        axios.post(urlKeeps, fill) //elimino registro
                            .then(response => {
                                app.cargando = false; //desaparece indicador de cargando
                                datatableCargarIpsAdministrar();
                                alertToastSuccess("Eliminado Exitosamente", 3500);
                            })
                            .catch(error => {
                                app.cargando = false; //desaparece indicador de cargando
                                swal("Cancelado!", "Error al grabar...", "error");
                            });
                    } else {
                        swal("Cancelado!", "No se registraron cambios...", "error");
                        return false;
                    }
                }
            )
        },
       
    }
})