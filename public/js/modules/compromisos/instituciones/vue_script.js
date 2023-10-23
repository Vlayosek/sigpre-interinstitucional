var app = new Vue({
    el: "#main",
    data: {
        formCrear: {
            id: 0,
            nombre: "",
            institucion_id: "",
            siglas: "",
            ministro_usuario_id: "",
        },
        formGabinete: {
            id: 0,
            nombre: "",
            siglas: "",
        },
        formCompromiso: {
            id: 0,
            arreglo_compromisos: [],
            id_institucion: 0,
            id_institucion_: 0,
            motivo: "",
        },
        consulta: false,
        id_institucion: 0,
        id_institucion_: 0,
        anterior_institucion: "",
        actual_institucion: "",
        arreglo_compromisos: [],
        cargando: false,
        gabinete: false,
        text_gabinete: "<i class='fa fa-tasks'></i>&nbsp;GABINETE",
        text_institucion: "<i class='fa fa-tasks'></i>&nbsp;INSTITUCION",
        disabled_institucion: false,
        activar_boton_migracion: false,
        activar_boton_migrados: true,
    },
    created: function () {
        this.limpiarForm();
    },
    methods: {
        limpiarForm: function () {
            this.formCrear.id = 0;
            this.formCrear.nombre = "";
            this.formCrear.institucion_id = "";
            this.formCrear.siglas = "";
            this.formCrear.ministro_usuario_id = "";
            this.disabled_institucion = false;
            this.formGabinete.id = 0;
            this.formGabinete.nombre = "";
            this.formGabinete.siglas = "";

            this.formCompromiso.id = 0;
            this.formCompromiso.arreglo_compromisos = [];
            this.formCompromiso.id_institucion = 0;
            this.formCompromiso.id_institucion_ = 0;
            this.formCompromiso.motivo = "";

            $("#filtro_institucion").val(null).trigger("change");
            $("#ministro_usuario_id").val(null).trigger("change");
            $("#filtro_ministro_id").val(null).trigger("change");
            $("#filtro_gabinete").val(null).trigger("change");
            $("#identificacion_institucion").val(null).trigger("change");
            $("#identificacion_institucion_").val(null).trigger("change");

            // resetarModal();
        },
        cambioVisualizador: function () {
            if (this.gabinete) this.gabinete = false;
            else this.gabinete = true;
        },

        async consultaInstitucion(id) {
            this.disabled_institucion = true;
            $("#principal_tab").click();
            var urlKeeps = "consultaInstitucion";

            app.cargando = true;
            var fill = {
                id: id,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200) {
                        this.formCrear = response.data.datos;
                        $("#filtro_institucion")
                            .val(this.formCrear.institucion_id)
                            .change();
                        $("#ministro_usuario_id")
                            .val(this.formCrear.ministro_usuario_id)
                            .change();
                    } else {
                        swal("Cancelado!", "Error al grabar...", "error");
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },
        async guardarGabinete(edita) {
            let error = "";
            if (
                this.formGabinete.nombre == "" ||
                this.formGabinete.nombre == null
            )
                error += "\n Debe digitar el nombre del gabinete";
            if (
                this.formGabinete.siglas == "" ||
                this.formGabinete.siglas == null
            )
                error += "\n Debe escribir las siglas del gabinete";

            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }
            if (edita == true) var urlKeeps = "guardaEditaGabinete";
            else var urlKeeps = "guardarGabinete";

            app.cargando = true;
            await axios
                .post(urlKeeps, this.formGabinete)
                .then((response) => {
                    if (response.data.status == 200) {
                        this.limpiarForm();
                        $("#cerrar_modal_gabinete").click();
                        $("#cerrar_modal_gabinete_").click();
                        app.cargando = false;
                        alertToastSuccess("Grabado Exitosamente", 3500);
                        datatableCargarGabinete();
                    } else {
                        app.cargando = false;
                        swal("Cancelado!", "Error al grabar...", "error");
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },
        async guardarInstitucion() {
            let error = "";
            if (this.formCrear.nombre == "" || this.formCrear.nombre == null)
                error += "\n Debe digitar los nombre de la institucion";
            if (
                this.formCrear.institucion_id == "" ||
                this.formCrear.institucion_id == null
            )
                error += "\n Debe seleccionar una institución";
            if (
                this.formCrear.ministro_usuario_id == "" ||
                this.formCrear.ministro_usuario_id == null
            )
                error += "\n Debe seleccionar un usuario Ministro";
            if (this.formCrear.siglas == "" || this.formCrear.siglas == null)
                error += "\n Debe escribir las siglas de la institucion";

            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }
            var urlKeeps = "guardarInstitucion";
            app.cargando = true;
            await axios
                .post(urlKeeps, this.formCrear)
                .then((response) => {
                    if (response.data.status == 200) {
                        this.limpiarForm();
                        $("#cerrar_modal_institucion").click();
                        $("#cerrar_modal_institucion_").click();

                        app.cargando = false;
                        alertToastSuccess("Grabado Exitosamente", 3500);
                        datatableCargar();
                    } else {
                        app.cargando = false;
                        swal("Cancelado!", "Error al grabar...", "error");
                    }
                })
                .catch((error) => {
                    app.cargando = false;
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },
        async eliminarGabinete(id) {
            //eliminar un registro
            let result = await swal(
                {
                    title: "Estás seguro de realizar esta acción",
                    text: "Al confirmar se grabaran los datos exitosamente",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Si!",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: false,
                },
                function (isConfirm) {
                    if (isConfirm) {
                        var urlKeeps = "eliminarInstitucion"; //setea la ruta del controlador
                        var fill = { id: id }; //busca el id y lo almacena en la var 'id'
                        app.cargando = true; //mientras busca la data, el indicador se activa "cargando"
                        axios
                            .post(urlKeeps, fill) //elimino registro
                            .then((response) => {
                                app.cargando = false; //desaparece indicador de cargando
                                datatableCargarGabinete();
                                alertToastSuccess(
                                    "Eliminado Exitosamente",
                                    3500
                                );
                            })
                            .catch((error) => {
                                app.cargando = false; //desaparece indicador de cargando
                                swal(
                                    "Cancelado!",
                                    "Error al grabar...",
                                    "error"
                                );
                            });
                    } else {
                        swal(
                            "Cancelado!",
                            "No se registraron cambios...",
                            "error"
                        );
                        return false;
                    }
                }
            );
        },
        async eliminarInstitucion(id) {
            //eliminar un registro
            let result = await swal(
                {
                    title: "Estás seguro de realizar esta acción",
                    text: "Al confirmar se grabaran los datos exitosamente",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Si!",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: false,
                },
                function (isConfirm) {
                    if (isConfirm) {
                        var urlKeeps = "eliminarInstitucion"; //setea la ruta del controlador
                        var fill = { id: id }; //busca el id y lo almacena en la var 'id'
                        app.cargando = true; //mientras busca la data, el indicador se activa "cargando"
                        axios
                            .post(urlKeeps, fill) //elimino registro
                            .then((response) => {
                                app.cargando = false; //desaparece indicador de cargando
                                datatableCargar();
                                alertToastSuccess(
                                    "Eliminado Exitosamente",
                                    3500
                                );
                            })
                            .catch((error) => {
                                app.cargando = false; //desaparece indicador de cargando
                                swal(
                                    "Cancelado!",
                                    "Error al grabar...",
                                    "error"
                                );
                            });
                    } else {
                        swal(
                            "Cancelado!",
                            "No se registraron cambios...",
                            "error"
                        );
                        return false;
                    }
                }
            );
        },
        async editaInstitucion(id) {
            var urlKeeps = "editaInstitucion"; //setea la ruta
            var fill = {
                id: id, //busca el id y lo almacena en la var 'id'
            };
            app.cargando = true;
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false; //desaparece indicador de cargando
                    this.formCrear = response.data.datos; //llena el formulario con los datos
                    $("#filtro_gabinete")
                        .val(this.formCrear.institucion_id)
                        .change();
                    $("#filtro_ministro_id")
                        .val(this.formCrear.ministro_usuario_id)
                        .change();
                })
                .catch((error) => {
                    app.cargando = false; //desaparece indicador de cargando
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },
        async editaGabinete(id) {
            var urlKeeps = "editaGabinete"; //setea la ruta
            var fill = {
                id: id, //busca el id y lo almacena en la var 'id'
            };
            app.cargando = true;
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false; //desaparece indicador de cargando
                    this.formGabinete = response.data.datos; //llena el formulario con los datos
                    $("#filtro_gabinete")
                        .val(this.formCrear.institucion_id)
                        .change();
                    $("#filtro_ministro_id")
                        .val(this.formCrear.ministro_usuario_id)
                        .change();
                })
                .catch((error) => {
                    app.cargando = false; //desaparece indicador de cargando
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },
        // Busca los compromisos por la institucion
        async buscarCompromisos() {
            if (
                $("#identificacion_institucion").val() == "" ||
                $("#identificacion_institucion").val() == null
            ) {
                alertToast("No ha seleccionado ninguna institucion", 3500);

                return false;
            }

            datatableBuscarCompromisosServerSide();
            this.consulta = true;
        },

        //
        async elegirInstitucion() {
            var seleccionados = dtmenuMigracion.api().rows({ selected: true });
            var arregloSeleccionados = [];
            if (!seleccionados.data().length) {
                alertToast("No ha seleccionado ningún item", 3500);
                return false;
            } else {
                seleccionados.every(function (key, data) {
                    arregloSeleccionados.push(this.data().id);
                    /* this.formCompromiso.arreglo_compromisos.push(
                        this.data().id
                    ); */
                });
                this.formCompromiso.arreglo_compromisos = arregloSeleccionados;
            }

            /* var elegir = $(
                "#identificacion_institucion_ option:selected"
            ).text(); */
        },

        async migrarCompromisos() {
            if (
                $("#identificacion_institucion_").val() == "" ||
                $("#identificacion_institucion_").val() == null
            ) {
                alertToast(
                    "No ha seleccionado ninguna institucion a migrar",
                    3500
                );

                return false;
            }

            var urlKeeps = "migrarCompromisos";
            let fill = {
                arreglo_compromisos: this.formCompromiso.arreglo_compromisos,
                id_institucion: this.formCompromiso.id_institucion,
                id_institucion_: this.formCompromiso.id_institucion_,
                motivo: this.formCompromiso.motivo,
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    console.log(response);
                    if (response.data.status != 200)
                        alertToast(response.data.message, 3500);
                    //alertToast(response.data.message, 3500);
                    else alertToastSuccess(response.data.message, 3500);
                    datatableBuscarCompromisosServerSide();
                    this.limpiarForm();
                    $("#cerrar_modal_migracion_compromiso").trigger("click");
                })
                .catch((error) => {
                    alertToast("Error, recargue la página", 3500);
                });
        },

        async cargarDatatableDatosMigrados() {
            datatableCodigosMigradosServerSide();
        },
    },
});
