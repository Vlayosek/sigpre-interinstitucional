var appHistorial = new Vue({
    el: "#main",
    data() {
        return {
            currentTab_: 1,
            customLink: "informacion",
            cargando: false,
            cargando_: false,
            activos: 0,
            inactivos: 0,
            pendientes: 0,
            formFiltro: {
                identificacion: "",
                filtro: "",
            },
            filtro: false,
            formHistorial: {
                area_id: "",
                denominacion_id: "",
                cargo_id: "",
                fecha_ingreso: "",
                fecha_salida: "",
                tipo_contrato_id: "",
            },
            formGrupo: {
                id: "0",
                descripcion: "",
                teletrabajo: false,
                estado: "",
                grupo_id: "0",
                fecha_inicio: null,
                fecha_fin: null,
                lunes: "",
                martes: "",
                miercole: "",
                jueves: "",
                viernes: "",
                sabado: "",
                domingo: "",
            },
            persona_id: 0,
            actualizado: false,
            cargandoItem: false,
            arregloSemana: [],
            nuevoGrupo: false,
        };
    },
    created: function () {
        this.obtenerDiasSemana(this.formGrupo.id);
    },
    methods: {
        validarCampos: function () {
            let errores = "";

            if (this.formGrupo.descripcion.trim().length == 0)
                errores += "\n Debe agregar una descripcion";

            return errores;
        },
        limpiarHorario: function () {
            this.formGrupo.id = "0";
            this.formGrupo.descripcion = "";
            this.formGrupo.teletrabajo = false;
            this.formGrupo.fecha_inicio = ""; //new Date().toISOString().slice(0, 10)
            this.formGrupo.fecha_fin = "";
            $(".dia_semana_arreglo").val("");
            $(".span_descripcion").val("");
            $("#fecha_inicio_area").val(null);
            $("#fecha_fin_area").val(null);
        },
        limpiarFiltro: function () {
            this.formFiltro.identificacion = "";
            this.formFiltro.filtro = "";
            $(".filtro_select").addClass("hidden");
        },
        changeFiltro: function () {
            var filtroDato = this.formFiltro.filtro;
            if (filtroDato == "sueldo_") $("#busqueda").addClass("numero");
            else {
                $("#busqueda").removeClass("numero");
                $(".filtro_select").addClass("hidden");
                //   $(".filtro_select_input").addClass("hidden");

                if (filtroDato.indexOf("_id") != -1) {
                    $("#" + filtroDato + "_busqueda").removeClass("hidden");
                } else $(".filtro_select_input").removeClass("hidden");
            }
        },

        cambiarEstado: function (id, persona_id) {
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/uath/cambiarEstado";

            //var urlKeeps='cambiarEstado';
            var fill = {
                id: id,
            };
            axios.post(urlKeeps, fill).then((response) => {
                alertToastSuccess("Grabado exitoso", 3500);
                datatablehistorialPersona(persona_id);
            });
        },
        cambiarPrincipal: function (id, persona_id) {
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/uath/cambiarPrincipal";

            // var urlKeeps='cambiarPrincipal';
            var fill = {
                id: id,
            };

            axios.post(urlKeeps, fill).then((response) => {
                alertToastSuccess("Grabado exitoso", 3500);
                datatablehistorialPersona(persona_id);
            });
        },
        actualizadoHistorial: function () {
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/uath/actualizadoHistorial";

            //  var urlKeeps='actualizadoHistorial';
            var fill = {
                id: this.persona_id,
            };
            axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.actualizado = true;

                    alertToastSuccess("Grabado exitoso", 3500);
                })
                .catch((error) => {
                    alertToast("Error al grabar...", 3500);
                });
        },
        consultaEstados: function () {
            this.cargandoItem = true;

            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/uath/consultaEstados";
            // var urlKeeps='consultaEstados';
            var fill = {
                id: 0,
            };
            axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargandoItem = false;
                    this.activos = response.data.datos.activos;
                    this.inactivos = response.data.datos.inactivos;
                    this.pendientes = response.data.datos.pendientes;
                })
                .catch((error) => {
                    this.cargandoItem = false;
                    alertToast("Error, recargue la página", 3500);
                });
        },
        consultaPersonaHistorial: function (id) {
            this.persona_id = id;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/uath/consultaPersonaHistorial";

            //    var urlKeeps='consultaPersonaHistorial';
            var fill = {
                id: id,
            };
            axios.post(urlKeeps, fill).then((response) => {
                this.actualizado = response.data.actualizado;
                $(".span_apelllidos_nombres").text(
                    response.data.persona.apellidos_nombres.toUpperCase()
                );
                $(".span_identificacion").text(
                    response.data.persona.identificacion
                );
            });
        },
        filtrarPersona: function () {
            if (this.formFiltro.identificacion.length < 3) {
                alertToast(
                    "Debe escribir en el filtro al menos 3 caracteres",
                    3500
                );
                return false;
            }

            this.filtro = true;
            datatableCargarPersonas();
            // this.limpiarFiltro();
        },
        quitarFiltro: function () {
            this.limpiarFiltro();
            this.filtro = false;
            datatableCargarPersonas();
        },
        limpiarHistorial: function () {
            this.formHistorial.tipo_contrato_id = "";
            this.formHistorial.area_id = "";
            this.formHistorial.denominacion_id = "";
            this.formHistorial.cargo_id = "";
            this.formHistorial.fecha_ingreso = "";
            this.formHistorial.fecha_salida = "";
        },
        async asignacionHorario(id) {
            this.persona_id = id;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/uath/asignacionHorario";
            var fill = {
                id: id,
            };
            this.cargando_ = true;
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    if (response.data.status == "200") {
                        this.limpiarHorario();
                        this.cargando_ = false;
                        datatablehistorialHorario(id);
                    } else alertToast("Error en la carga", 3500);
                })
                .catch((error) => {
                    alertToast("Error en la carga", 3500);
                });
        },
        async obtenerDiasSemana(id) {
            var urlKeeps = "obtenerDiaSemana";
            var fill = {
                id: id,
            };
            this.cargando = true;
            await axios.post(urlKeeps, fill).then((response) => {
                this.arregloSemana = response.data.datos;
                appHistorial.cargando = false;
            });
        },
        async guardarGrupo() {
            var data = new FormData();

            var error = this.validarCampos();
            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }
            var fill = this.formGrupo;
            var urlKeeps = "guardarGrupo";

            for (const property in fill) {
                let atributo = `${property}`;
                let valor = `${fill[property]}`;
                data.append(atributo, valor);
            }
            $("input[name*='hora_inicio_']").each(function () {
                var dia_semana = this.name.replace("hora_inicio_", "");
                var valor = $(this).val();
                data.append("hora_inicio[" + dia_semana + "]", valor);
            });
            $("input[name*='hora_fin_']").each(function () {
                var dia_semana = this.name.replace("hora_fin_", "");
                var valor = $(this).val();
                data.append("hora_fin[" + dia_semana + "]", valor);
            });
            data.append("persona_id", this.persona_id);
            this.cargando = true;
            await axios
                .post(urlKeeps, data)
                .then((response) => {
                    appHistorial.cargando = false;
                    if (response.data.status != "200") {
                        alertToast(response.data.message, 3500);
                    } else {
                        // datatableCargarPersonas();
                        this.limpiarHorario();
                        alertToastSuccess("Grabado Exitoso", 3500);
                        document.querySelector("#cerrar_modal_horario").click();
                    }
                })
                .catch((error) => {
                    appHistorial.cargando = false;
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },
        async editarGrupo(id) {
            var urlKeeps = "editarGrupo";
            var fill = {
                id: id,
            };
            this.cargando = true;
            await axios.post(urlKeeps, fill).then((response) => {
                appHistorial.cargando = false;
                this.formGrupo = response.data.datos;

                $("input[name='hora_inicio__LUNES']").val(
                    this.formGrupo.lunes.split(" ")[0]
                );
                $("input[name='hora_fin__LUNES']").val(
                    this.formGrupo.lunes.split(" ")[1]
                );

                $("input[name='hora_inicio__MARTES']").val(
                    this.formGrupo.martes.split(" ")[0]
                );
                $("input[name='hora_fin__MARTES']").val(
                    this.formGrupo.martes.split(" ")[1]
                );

                $("input[name='hora_inicio__MIERCOLES']").val(
                    this.formGrupo.miercoles.split(" ")[0]
                );
                $("input[name='hora_fin__MIERCOLES']").val(
                    this.formGrupo.miercoles.split(" ")[1]
                );

                $("input[name='hora_inicio__JUEVES']").val(
                    this.formGrupo.jueves.split(" ")[0]
                );
                $("input[name='hora_fin__JUEVES']").val(
                    this.formGrupo.jueves.split(" ")[1]
                );

                $("input[name='hora_inicio__VIERNES']").val(
                    this.formGrupo.viernes.split(" ")[0]
                );
                $("input[name='hora_fin__VIERNES']").val(
                    this.formGrupo.viernes.split(" ")[1]
                );

                $("input[name='hora_inicio__SABADO']").val(
                    this.formGrupo.sabado.split(" ")[0]
                );
                $("input[name='hora_fin__SABADO']").val(
                    this.formGrupo.sabado.split(" ")[1]
                );

                $("input[name='hora_inicio__DOMINGO']").val(
                    this.formGrupo.domingo.split(" ")[0]
                );
                $("input[name='hora_fin__DOMINGO']").val(
                    this.formGrupo.domingo.split(" ")[1]
                );

                $(".span_descripcion").text(
                    this.formGrupo.descripcion.toUpperCase()
                );
            });
        },
        async obtenerHorarios(id) {
            datatablehistorialHorario(id);
        },
        async eliminarHorario(id) {
            var urlKeeps = "eliminarHorario";
            var fill = {
                id: id,
            };
            this.cargando = true;
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    if (response.data.status == "200") {
                        datatablehistorialHorario(this.persona_id);
                        this.cargando = false;
                        alertToastSuccess(response.data.message, 3500);
                    } else {
                        this.cargando = false;
                        alertToast("Error al Eliminar", 3500);
                    }
                })
                .catch((error) => {
                    this.cargando = false;

                    swal("Cancelado!", "Error al eliminar...", "error");
                });
        },
    },
});
