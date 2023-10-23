    var app = new Vue({
        el: '#main',
        data: {
            tabAsignacion: true,
            tabNotificaciones: false,
            tabEliminados: false,
            currentTab: 0,
            currentTab_: 1,
            cargando: false,
            consulta: false,
            formCrear: {
                'id': 0,
                'usuario_id': '',
                'institucion_id': [],
            },
            formNuevo: {
                'id': 0,
                'nombres': '',
                'identificacion': '',
                'email': '',
                'institucion_id': '',
                'cargo': '',
                'telefono': '',
                'celular': '',
            },
            cargarAsignaciones: function() {
                this.currentTab_ = 1;
                this.tabAsignacion = true;
                this.tabNotificaciones = false;
                this.tabEliminados = false;
            },
            cargarNotificaciones: function() {
                this.currentTab_ = 2;
                this.tabAsignacion = false;
                this.tabNotificaciones = true;
                this.tabEliminados = false;
                datatableBuscarNotificaciones();
            },
            cargarEliminados: function() {
                this.currentTab_ = 3;
                this.tabAsignacion = false;
                this.tabNotificaciones = false;
                this.tabEliminados = true;
                datatableBuscarEliminados();
            },
        },
        methods: {
            limpiarForm: function() {
                this.formCrear.id = 0;
                this.formCrear.usuario_id = '';
                this.formCrear.institucion_id = [];
                resetarModal();
            },
            async confirmar() {
                let error = "";
                if (this.formCrear.usuario_id == '' || this.formCrear.usuario_id == null)
                    error = "\n Debe seleccionar un usuario";
                if (this.formCrear.institucion_id == '' || this.formCrear.institucion_id == null)
                    error = "\n Debe seleccionar por lo menos una institución";

                if (error.length > 0) {
                    swal(" ", error, "error");
                    return false;
                } else {
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
                    }, function(isConfirm) {
                        if (isConfirm) {
                            var url = 'guardarAsignacion';
                            var data = new FormData();
                            data.append('id', app.formCrear.id);
                            data.append('usuario_id', app.formCrear.usuario_id);
                            data.append('institucion_id', app.formCrear.institucion_id);

                            cargarLoading();
                            axios.post(url, data).then(response => {

                                $(".confirm").click()
                                app.crear = false;
                                app.formCrear.id = response.data.datos;
                                datatableCargarMensajes();
                                $("#cerrar_modal_asignacion").click();
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
            async editar(id, usuario_id, nombre) {
                resetarModal();

                this.formCrear.id = id;
                var data = new FormData();
                data.append('usuario_id', usuario_id)
                var urlKeeps = 'editar';
                await axios.post(urlKeeps, data).then(response => {
                    var selectUsuario = document.querySelector('#usuario_id');
                    selectUsuario.options.add(new Option(nombre, usuario_id));
                    this.formCrear.usuario_id = usuario_id;
                    $("#usuario_id").val(usuario_id).change();

                    var arregloSelect = [];
                    var select = document.querySelector('#institucion_id');
                    $.each(response.data.datos, function(key, value) {
                        select.options.add(new Option(key, value));
                        arregloSelect.push(value);
                    });
                    this.formCrear.institucion_id = arregloSelect;
                    $("#institucion_id").val(arregloSelect).change();
                });
            },
            async eliminar(id) {
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
                }, function(isConfirm) {
                    if (isConfirm) {
                        var url = 'eliminar';
                        var data = new FormData();
                        var fill = {
                            'id': id
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

        limpiarIngreso: function() {
            this.formNuevo.id = 0;
            this.formNuevo.nombres = '';
            this.formNuevo.identificacion = '';
            this.formNuevo.email = '';
            this.formNuevo.institucion_id = '';
            this.formNuevo.cargo = '';
            this.formNuevo.telefono = '';
            this.formNuevo.celular = '';
        },

    });