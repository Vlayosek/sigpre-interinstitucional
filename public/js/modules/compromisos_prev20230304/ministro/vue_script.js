    var app = new Vue({
        el: '#main',
        data: {
            formCrear: {
                'id': 0,
                'identificacion': '',
                'nombres': '',
                'email': '',
                'cargo': '',
                'extension': '',
                'password': ''
            },
            cargando: false,
        },
        created: function() {
            this.limpiarForm();
        },
        methods: {
            limpiarForm: function() {
                this.formCrear.id = 0;
                this.formCrear.identificacion = '';
                this.formCrear.nombres = '';
                this.formCrear.email = '';
                this.formCrear.cargo = '';
                this.formCrear.extension = '';
                this.formCrear.password = '';
                this.generacion_clave();
            },
            async generacion_clave() {
                var urlKeeps = 'generacion_clave';
                var fill = {
                    'id': 0
                }
                await axios.post(urlKeeps, fill).then(response => {
                    app.cargando = false;
                    if (response.data.status == 200) {
                        this.formCrear.password = response.data.datos;
                    } else {
                        //swal("Cancelado!", "Error al generar clave...", "error");
                        console.log('error al generar clave');
                    }
                }).catch(error => {
                    app.cargando = false;
                    console.log('error al generar clave');
                });
            },
            async editarUsuario(id) {
                this.disabled_institucion = true;
                $("#principal_tab").click();
                var urlKeeps = 'editarUsuario';
                $("[name='errorCorreo']").text('Correo válido');
                app.cargando = true;
                var fill = {
                    'id': id
                }
                await axios.post(urlKeeps, fill).then(response => {

                    app.cargando = false;
                    if (response.data.status == 200) {
                        this.formCrear = response.data.datos;
                    } else {
                        swal("Cancelado!", "Error al grabar...", "error");
                    }
                }).catch(error => {

                    app.cargando = false;
                    swal("Cancelado!", "Error al grabar...", "error");
                });
            },
            async guardarUsuario() {
                let error = "";
                if (this.formCrear.nombres == '' || this.formCrear.nombres == null) error += "\n Debe digitar los nombres del usuario";
                if (this.formCrear.identificacion == '' || this.formCrear.identificacion == null) error += "\n Debe escribir la identificacion del usuario";
                if (this.formCrear.email == '' || this.formCrear.email == null) error += "\n Debe escribir el correo del usuario";
                if (this.formCrear.cargo == '' || this.formCrear.cargo == null) error += "\n Debe escribir el cargo del usuario";
                if (this.formCrear.extension == '' || this.formCrear.extension == null) error += "\n Debe escribir la extensión del usuario";
                if (this.formCrear.id == 0) {
                    if (this.formCrear.password == '' || this.formCrear.password == null) error += "\n Debe escribir la contraseña del usuario";
                    if (this.formCrear.password.length < 6) error += "\n Debe escribir la contraseña en minimo de 5 caracteres";
                }
                if ($("[name='errorCorreo']").text() == 'Correo no válido' || $("[name='errorCorreo']").text() == '') {
                    error += "\n Debe validar el correo correctamente";
                }
                if (error.length > 0) {
                    swal(" ", error, "error");
                    return false;
                }
                var urlKeeps = 'guardarUsuario';

                app.cargando = true;
                await axios.post(urlKeeps, this.formCrear).then(response => {
                    if (response.data.status == 200) {
                        this.limpiarForm();
                        $("#cerrar_modal").click();

                        app.cargando = false;
                        alertToastSuccess("Grabado Exitosamente", 3500);
                        datatableCargar();
                    } else {

                        app.cargando = false;
                        swal("Cancelado!", "Error al grabar...", "error");
                    }
                }).catch(error => {

                    app.cargando = false;
                    swal("Cancelado!", "Error al grabar...", "error");
                });
            },

            async inactivarUsuario(id) { //eliminar un registro 
                let result = await swal({
                        title: "Estás seguro de realizar esta acción",
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
                            var urlKeeps = 'inactivarUsuario'; //setea la ruta del controlador
                            var fill = { 'id': id } //busca el id y lo almacena en la var 'id'
                            app.cargando = true; //mientras busca la data, el indicador se activa "cargando"
                            axios.post(urlKeeps, fill) //elimino registro
                                .then(response => {
                                    app.cargando = false; //desaparece indicador de cargando
                                    datatableCargar();
                                    alertToastSuccess("Usuario Inactivo Exitosamente", 3500);
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

            async cambiarEstadoUsuario(id) { //eliminar un registro 
                let result = await swal({
                        title: "Estás seguro de realizar esta acción",
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
                            var urlKeeps = 'cambiarEstadoUsuario'; //setea la ruta del controlador
                            var fill = { 'id': id } //busca el id y lo almacena en la var 'id'
                            app.cargando = true; //mientras busca la data, el indicador se activa "cargando"
                            axios.post(urlKeeps, fill) //elimino registro
                                .then(response => {
                                    app.cargando = false; //desaparece indicador de cargando
                                    datatableCargar();
                                    alertToastSuccess("Usuario Inactivo Exitosamente", 3500);
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