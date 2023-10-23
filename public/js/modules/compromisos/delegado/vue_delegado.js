var app = new Vue({
    el: '#main',
    data: {
        formDelegado: {
            'id': 0,
            'identificacion': '',
            'nombres': '',
            'email': '',
            'cargo': '',
            'telefono': '',
            'celular': '',
            'institucion_id': ''
        },
    },
    created: function() {
        this.limpiarFormDelegado();
    },
    methods: {
        limpiarFormDelegado: function() {
            this.formDelegado.id = 0;
            this.formDelegado.identificacion = '';
            this.formDelegado.nombres = '';
            this.formDelegado.email = '';
            this.formDelegado.cargo = '';
            this.formDelegado.telefono = '';
            this.formDelegado.celular = '';
            this.formDelegado.institucion_id = '';
            $("#filtro_institucion").val(null).change();
            // resetarModal();
        },
        async guardarDelegadoInstitucion() {
            let error = "";
            if (this.formDelegado.identificacion == '' || this.formDelegado.identificacion == null) error += "\n Debe digitar el número de cédula";
            if (this.formDelegado.nombres == '' || this.formDelegado.nombres == null) error += "\n Debe digitar los nombres del funcionario";
            if (this.formDelegado.email == '' || this.formDelegado.email == null) error += "\n Debe digitar el email del funcionario";
            if (this.formDelegado.cargo == '' || this.formDelegado.cargo == null) error += "\n Debe digitar el cargo del funcionario";
            if (this.formDelegado.telefono == '' || this.formDelegado.telefono == null) error += "\n Debe digitar el telefono del funcionario";
            if (this.formDelegado.celular == '' || this.formDelegado.celular == null) error += "\n Debe digitar el número de celular del funcionario";
            if (this.formDelegado.institucion_id == '' || this.formDelegado.institucion_id == null) error += "\n Debe seleccionar una institución";

            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }
            var urlKeeps = 'guardarDelegadoInstitucion';
            iniciar_modal_espera();
            //app.cargando=true;
            await axios.post(urlKeeps, this.formDelegado).then(response => {
                this.limpiarFormDelegado();
                $("#cerrar_delegado").click();
                parar_modal_espera();
                //app.cargando=false;
                alertToastSuccess("Grabado Exitosamente", 3500);
                datatableCargarDelegado();
            }).catch(error => {
                app.cargando = false;
                swal("Cancelado!", "Error al grabar...", "error");
            });
        },
        async editarDelegadoInstitucion(id) {
            var urlKeeps = 'editarDelegadoInstitucion'; //setea la ruta
            var fill = {
                'id': id //busca el id y lo almacena en la var 'id'
            }
            app.cargando = true;
            await axios.post(urlKeeps, fill)
                .then(response => {
                    app.cargando = false; //desaparece indicador de cargando
                    this.formDelegado = response.data.datos; //llena el formulario con los datos
                    $("#filtro_institucion").val(this.formDelegado.institucion_id).change();
                }).catch(error => {
                    app.cargando = false; //desaparece indicador de cargando
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },
        async eliminarDelegadoInstitucion(id) { //eliminar un registro 
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
                },
                function(isConfirm) {
                    if (isConfirm) {
                        var urlKeeps = 'eliminarDelegadoInstitucion'; //setea la ruta del controlador
                        var fill = { 'id': id } //busca el id y lo almacena en la var 'id'
                        app.cargando = true; //mientras busca la data, el indicador se activa "cargando"
                        axios.post(urlKeeps, fill) //elimino registro
                            .then(response => {
                                app.cargando = false; //desaparece indicador de cargando
                                datatableCargarDelegado();
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


    },

});