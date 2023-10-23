var app = new Vue({
    el: "#main",
    data() {
        return {
            currentTab: 1,
            cargando: false,
            //reporte compromisos cumplidos
            cumplido: 0,
            gabinete_id: 0,
            institucion_id: 0,
            arrayGabinete: [],
            arrayInstitucion: [],
            mapacalor: true,
            //reporte ejecutivo
            formConsultar: {
                id: "",
                nombre_compromiso: "",
                institucion_responsable: "",
                institucion_corresponsable: [],
                gabinete_sectorial: "",
                fecha_inicio: "",
                fecha_fin: "",
                canton: "",
                tipo_ubicacion: "",
                objetivo: [],
                tipo_reporte: "",
            },
            arrayCompromisosInstitucion: [],
            arrayCompromisos: [],
            arrayPeriodo: [],
            arrayCompromiso: [],
            mostrarFiltroPeriodo: false,
            mostrarFiltroCompromiso: false,
            hoy: "",
            habilitarCompromiso: true,
            filtro_compromiso_individual: [],
            filtro_compromiso_individual_join: [],
            institucion_anterior: null,
            graficos_estados: false,
            grafico1: null,
            grafico2: null,
            habilitaImprimir: false,
            institucion_id_temp: 0,
            gabinete_id_temp: 0,
            arregloDatosCompromisosBasico: [],
            arregloDatosUbicacionGenerados: null,
            base64PieImagen: null,
            filtro_institucion_ejecutivo: [],
            filtro_compromisos: [],
            botonRetornoHabilitado: 'provincias',
            botonResumenGabinete: 'gestion',
            filtro_ubicacion_seleccionado: false
        };
    },
    created: function() {
        //this.limpiarReporteEjecutivo();
        /// this.consulta_compromiso();
    },
    methods: {
        limpiarReporteCC: function() {
            this.gabinete_id = 0;
            this.institucion_id = 0;
            this.institucion_temp = 0;
            this.arrayInstitucion = [];
            this.arrayGabinete = [];
            this.institucion_anterior = null;
            $("#filtro_gabinete").val(null).change();
            $("#filtro_institucion").val(null).change();
            //$("#fecha_inicio_cc").val(null).change();
            //$("#fecha_fin_cc").val(null).change();
        },
        consultasCC: function() {
            this.limpiarReporteCC();
            this.filtro_gabinete_cc();
            this.filtro_institucion_cc();
        },
        //REPORTE COMPROMISOS CUMPLIDOS
        async exportarExcelCumplidos() {
            var fecha_inicio = $("#fecha_inicio_cumplidos").val();
            var fecha_fin = $("#fecha_fin_cumplidos").val();
            var filtro_institucion = $("#filtro_institucion").val();
            var filtro_gabinete = $("#filtro_gabinete").val();
            let error = validarFechasEntradas(fecha_inicio, fecha_fin, null);
            if (!error) return false;

            var urlKeeps = "exportarExcelCumplidos";
            var fill = {
                fecha_inicio: fecha_inicio, //busca el id y lo almacena en la variable
                fecha_fin: fecha_fin, //busca el id y lo almacena en la variable
                filtro_institucion: filtro_institucion, //busca el id y lo almacena en la variable
                filtro_gabinete: filtro_gabinete,
            };

            this.cargando = true;
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status == 200) {
                        alertToastSuccess("Reporte Generado", 3500);
                        var direccion =
                            document.querySelector("#direccionDocumentos")
                            .value +
                            "/COMPROMISOS_GENERADOS/" +
                            response.data.documento_nombre;
                        downloadURI(direccion, response.data.documento_nombre);
                    }
                })
                .catch((error) => {
                    this.cargando = false;
                    alertToast("Error, recargue la página", 3500);
                });
        },

        //AL SELECCIONAR EL GABINETE
        async filtro_institucion_cc(gabinete = true) {
            if (this.gabinete_id != 0 && this.institucion_anterior == null)
                this.arrayInstitucion = [];
            this.cargando = true;
            var urlKeeps = "filtro_institucion_cc";
            var fill = {
                gabinete_id: gabinete ? this.gabinete_id : [],
            };
            iniciar_modal_espera();
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.arrayInstitucion =
                        response.data.datos != null ? response.data.datos : [];
                    if (this.institucion_anterior != null) {
                        $("#filtro_institucion")
                            .val(this.institucion_anterior)
                            .change();
                    }
                    this.cargando = false;
                    parar_modal_espera();
                })
                .catch((error) => {
                    alertToast("Error..., recargue la página", 3500);
                    this.cargando = false;
                });
        },
        //AL SELECCIONAR INSTITUCION
        limpiarFiltroGabinete: function() {
            this.arrayGabinete = [];
            $("#filtro_gabinete").val(null).change();
        },
        async filtroCompromisosporInstitucion() {
            this.cargando = true;
            var urlKeeps = "busquedaCompromisosporReporte";
            var fill = {
                fecha_inicio: $("#fecha_inicio_ejecutivo").val(), // search term
                fecha_fin: $("#fecha_fin_ejecutivo").val(), // search term
                filtro_institucion_ejecutivo: $("#filtro_institucion_ejecutivo").val() != null ? $("#filtro_institucion_ejecutivo").val() : [],
                valida_fecha: validarFechasEntradas($("#fecha_inicio_ejecutivo").val(), $("#fecha_fin_ejecutivo").val(), null), // search term
            };
            // iniciar_modal_espera();
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.arrayCompromisosInstitucion =
                        response.data.datos != null ?
                        response.data.datos : [];

                    this.cargando = false;
                    //     parar_modal_espera();
                })
                .catch((error) => {
                    alertToast("Error..., recargue la página", 3500);
                    this.cargando = false;
                });
        },
        async filtro_gabinete_cc(limpiar = null) {
            this.institucion_anterior = limpiar;
            if (this.institucion_id == 0) this.limpiarFiltroGabinete();
            this.cargando = true;
            var urlKeeps = "filtro_gabinete_cc";
            var fill = {
                institucion_id: this.institucion_id,
            };
            iniciar_modal_espera();
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    if (this.institucion_id == 0 || this.institucion_id == "")
                        this.arrayGabinete =
                        response.data.datos != null ?
                        response.data.datos : [];
                    else {
                        $("#filtro_gabinete")
                            .val(response.data.datos.id)
                            .change();
                    }
                    this.cargando = false;
                    parar_modal_espera();
                })
                .catch((error) => {
                    alertToast("Error..., recargue la página", 3500);
                    this.cargando = false;
                });
        },
        //REPORTE EJECUTIVO
        limpiarReporteEjecutivo: function() {
            this.limpiarFormCrear();
            this.filtro_institucion_cc(false);
            this.mostrarFiltroPeriodo = false;
            this.mostrarFiltroCompromiso = false;
            this.arrayPeriodo = [];
            this.arrayCompromisos = [];
            this.hoy = "";
            $("#filtro_institucion_ejecutivo").val(null).change();
            $("#filtro_periodos").val(null).change();
            $("#filtro_periodos option:first-child").attr(
                "disabled",
                "disabled"
            );
            $("#chk_periodo_actual").prop("checked", false);
            $("#filtro_compromisos_detallado").val(null).change();
            $("#filtro_compromisos_detallado option:first-child").attr(
                "disabled",
                "disabled"
            );
            $("#filtro_compromisos").val(null).change();
            $("#filtro_compromisos option:first-child").attr(
                "disabled",
                "disabled"
            );
        },
        limpiarPeriodo: function() {
            this.limpiarFormCrear();
            this.mostrarFiltroPeriodo = false;
            this.arrayPeriodo = [];
            this.hoy = "";
            $("#filtro_periodos").val(null).change();
            $("#chk_periodo_actual").prop("checked", false);
        },
        limpiarCompromiso: function() {
            this.limpiarFormCrear();
            this.mostrarFiltroCompromiso = false;
            this.arrayCompromisos = [];
            $("#filtro_compromisos").val(null).change();
            $("#filtro_compromisos option:first-child").attr(
                "disabled",
                "disabled"
            );
        },
        limpiarFormCrear: function() {
            this.formConsultar.id = "";
            this.formConsultar.nombre_compromiso = "";
            this.formConsultar.institucion_responsable = "";
            this.formConsultar.institucion_corresponsable = [];
            this.formConsultar.gabinete_sectorial = "";
            this.formConsultar.fecha_inicio = "";
            this.formConsultar.fecha_fin = "";
            this.formConsultar.canton = "";
            this.formConsultar.tipo_ubicacion = "";
            this.formConsultar.objetivo = [];
            this.formConsultar.tipo_reporte = "";
        },
        async mostrar_periodo() {
            this.mostrarFiltroPeriodo = true;
            this.limpiarCompromiso();
            if (
                document.getElementById("chk_periodo_actual").checked == false
            ) {
                this.consulta_periodo_actual();
            }
        },
        async mostrar_compromiso() {
            this.mostrarFiltroCompromiso = true;
            this.limpiarPeriodo();
            //     this.consulta_compromiso();
        },
        async mostrar_periodo_actual() {
            if (document.getElementById("chk_periodo_actual").checked == true) {
                this.hoy = new Date().toISOString().slice(0, 10);
                this.consulta_periodo_actual();
            } else {
                this.hoy = "";
                this.consulta_periodo_actual();
            }
        },
        async consulta_periodo_actual() {
            this.cargando = true;
            var urlKeeps = "consulta_periodo_actual";
            var fill = {
                hoy: this.hoy,
            };
            iniciar_modal_espera();
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.arrayPeriodo =
                        response.data.datos != null ? response.data.datos : [];
                    this.cargando = false;
                    parar_modal_espera();
                })
                .catch((error) => {
                    alertToast("Error..., recargue la página", 3500);
                    this.cargando = false;
                });
        },
        async consulta_compromiso() {
            this.cargando = true;
            var urlKeeps = "consulta_compromiso";

            var fill = {
                fecha_inicio_re: $("#fecha_inicio_ejecutivo").val(),
                fecha_fin_re: $("#fecha_fin_ejecutivo").val(),
            };
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.arrayCompromisos =
                        response.data.datos != null ? response.data.datos : [];
                    this.cargando = false;
                })
                .catch((error) => {
                    alertToast("Error..., recargue la página", 3500);
                    this.cargando = false;
                });
        },
        async consultaReporteEjecutivo(ejecutivo = null) {
            var errores = "";
            if (this.formConsultar.id == null || this.formConsultar.id == 0)
                errores += "\n Debe seleccionar un compromiso";
            if (errores.length > 0) {
                swal("Errores", errores, "error");
                return false;
            }
            var urlKeeps = "consultaReporteEjecutivo";
            var fill = {
                id: this.formConsultar.id,
                tipo_periodo: this.mostrarFiltroPeriodo,
                tipo_compromiso: this.mostrarFiltroCompromiso,
            };
            this.cargando = true;
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.datos != null) {
                        this.formConsultar = response.data.datos;
                        this.formConsultar.institucion_corresponsable =
                            response.data.corresponsables != null ?
                            response.data.corresponsables : [];
                        this.formConsultar.objetivo =
                            response.data.objetivos != null ?
                            response.data.objetivos : [];
                        this.formConsultar.tipo_reporte = ejecutivo;
                        this.generaReporteEjecutivo();
                    } else {
                        alertToast("Error, No hay datos", 3500);
                    }
                })
                .catch((error) => {
                    this.cargando = false;
                    alertToast("Error, recargue la página", 3500);
                });
        },
        async exportarExcelEjecutivo() {
            let fecha_inicio = $("#fecha_inicio_ejecutivo").val();
            let fecha_fin = $("#fecha_fin_ejecutivo").val();
            let id = $("#filtro_compromisos").val() != null ? $("#filtro_compromisos").val() : [];
            let filtro_institucion_ejecutivo = $("#filtro_institucion_ejecutivo").val() != null ? $("#filtro_institucion_ejecutivo").val() : [];

            let error = validarFechasEntradas(fecha_inicio, fecha_fin, null);
            if (!error) return false;
            let fill = {
                id: id,
                fecha_inicio: fecha_inicio,
                fecha_fin: fecha_fin,
                filtro_institucion_ejecutivo: filtro_institucion_ejecutivo
            }
            var urlKeeps = "exportarExcelEjecutivo";
            this.cargando = true;
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status == 200) {
                        alertToastSuccess("Reporte Generado", 3500);
                        var direccion =
                            document.querySelector("#direccionDocumentos")
                            .value +
                            "/COMPROMISOS_GENERADOS/" +
                            response.data.documento_nombre;
                        downloadURI(direccion, response.data.documento_nombre);
                        if (this.formConsultar.tipo_reporte == true) $("#cerrar_reporte_ejecutivo").click();
                        else $("#cerrar_reporte_detallado").click();
                    }
                })
                .catch((error) => {
                    this.cargando = false;
                    alertToast("Error, recargue la página", 3500);
                });
        },
        //REPORTE COMPROMISOS MINISTERIO
        limpiarCompromisoMinisterio: function() {
            $("#filtro_inst").val(null).change();
        },
        async exportarExcelMinisterio() {
            var institucion_filtro = $("#filtro_inst").val() == "" || $("#filtro_inst").val() == null ? [] : $("#filtro_inst").val();

            if (institucion_filtro == []) {
                alertToast("Debe seleccionar una institución", 3500);
                return false;
            }
            let fecha_inicio = $("#fecha_inicio_ministerio").val() == "" || $("#fecha_inicio_ministerio").val() == null ? null : $("#fecha_inicio_ministerio").val();
            let fecha_fin = $("#fecha_fin_ministerio").val() == "" || $("#fecha_fin_ministerio").val() == null ? null : $("#fecha_fin_ministerio").val();
            let error = validarFechasEntradas(fecha_inicio, fecha_fin, null);
            if (!error) return false;

            var urlKeeps = "exportarExcelMinisterio";
            var fill = {
                institucion: institucion_filtro, //busca el id y lo almacena en la variable
                fecha_inicio: fecha_inicio, //busca el id y lo almacena en la variable
                fecha_fin: fecha_fin, //busca el id y lo almacena en la variable
            };

            this.cargando = true;
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status == 200) {
                        alertToastSuccess("Reporte Generado", 3500);
                        var direccion =
                            document.querySelector("#direccionDocumentos")
                            .value +
                            "/COMPROMISOS_GENERADOS/" +
                            response.data.documento_nombre;
                        downloadURI(direccion, response.data.documento_nombre);
                    }
                })
                .catch((error) => {
                    this.cargando = false;
                    alertToast("Error, recargue la página", 3500);
                });
        },
        //EXPORTAR REPORTE COMPROMISOS PRESIDENCIALES GABINETE
        limpiarCompromisoGabinete: function() {
            $("#filtro_gab").val(null).change();
        },
        limpiarCompromisoGabineteResumen: function(btnExportar) {
            this.botonResumenGabinete = btnExportar;
            $("#filtro_gab").val(null).change();
        },
        consultaEstadoTab: function() {
            let clase_filtros = "info-box-text h6 estados_gestiones";
            let arregloClases = [];
            $(".estados_gestiones").each(function(key, value) {
                let arregloClases_ = value.className.split(" ");
                let clase = value.className.replace(clase_filtros, "").replace(" link_seleccionado", "").replaceAll(" ", "");
                if (arregloClases_.includes("link_seleccionado")) {
                    console.log(arregloClases_);
                    console.log(clase);
                    this.claseSeleccionadaGestion = clase;
                }

            });
            return this.claseSeleccionadaGestion != "" ? this.claseSeleccionadaGestion : "ACT";
        },
        async exportarExcelResumenGabinete() {
            var gabinete_filtro =
                $("#filtro_gab").val() == "" || $("#filtro_gab").val() == null ? [] : $("#filtro_gab").val();
            let fecha_inicio =
                $("#fecha_inicio_gabinete").val() == "" || $("#fecha_inicio_gabinete").val() == null ? null : $("#fecha_inicio_gabinete").val();
            let fecha_fin =
                $("#fecha_fin_gabinete").val() == "" || $("#fecha_fin_gabinete").val() == null ? null : $("#fecha_fin_gabinete").val();
            let error = validarFechasEntradas(fecha_inicio, fecha_fin, null);
            if (!error) return false;

            if (gabinete_filtro == []) {
                alertToast("Debe seleccionar un Gabinete Sectorial", 3500);
                return false;
            }

            var urlKeeps = "exportarExcelResumenGabinete";
            var fill = {
                gabinete: gabinete_filtro, //busca el id y lo almacena en la variable
                fecha_inicio: fecha_inicio,
                fecha_fin: fecha_fin,
                tipo: this.botonResumenGabinete,
            };

            this.cargando = true;
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status == 200) {
                        alertToastSuccess("Reporte Generado", 3500);
                        var direccion =
                            document.querySelector("#direccionDocumentos")
                            .value +
                            "/COMPROMISOS_GENERADOS/" +
                            response.data.documento_nombre;
                        downloadURI(direccion, response.data.documento_nombre);
                        //document.querySelector("#hrefGabineteGenerado").click();
                    }
                })
                .catch((error) => {
                    this.cargando = false;
                    alertToast("Error, recargue la página", 3500);
                });
        },
        async exportarExcelGabinete() {
            var gabinete_filtro =
                $("#filtro_gab").val() == "" || $("#filtro_gab").val() == null ? [] : $("#filtro_gab").val();
            let fecha_inicio =
                $("#fecha_inicio_gabinete").val() == "" || $("#fecha_inicio_gabinete").val() == null ? null : $("#fecha_inicio_gabinete").val();
            let fecha_fin =
                $("#fecha_fin_gabinete").val() == "" || $("#fecha_fin_gabinete").val() == null ? null : $("#fecha_fin_gabinete").val();
            let error = validarFechasEntradas(fecha_inicio, fecha_fin, null);
            if (!error) return false;

            if (gabinete_filtro == []) {
                alertToast("Debe seleccionar un Gabinete Sectorial", 3500);
                return false;
            }

            var urlKeeps = "exportarExcelGabinete";
            var fill = {
                gabinete: gabinete_filtro, //busca el id y lo almacena en la variable
                fecha_inicio: fecha_inicio,
                fecha_fin: fecha_fin,
            };

            this.cargando = true;
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status == 200) {
                        alertToastSuccess("Reporte Generado", 3500);
                        var direccion =
                            document.querySelector("#direccionDocumentos")
                            .value +
                            "/COMPROMISOS_GENERADOS/" +
                            response.data.documento_nombre;
                        downloadURI(direccion, response.data.documento_nombre);
                        //document.querySelector("#hrefGabineteGenerado").click();
                    }
                })
                .catch((error) => {
                    this.cargando = false;
                    alertToast("Error, recargue la página", 3500);
                });
        },
        async exportarExcelIndividual() {
            var gestion_filtro =
                $("#filtro_gestion").val() == "" ||
                $("#filtro_gestion").val() == null ?
                "--" :
                $("#filtro_gestion").val();
            var compromiso =
                $("#filtro_compromiso_individual").val() == "" ||
                $("#filtro_compromiso_individual").val() == null ?
                "--" :
                $("#filtro_compromiso_individual").val();
            var ubicacion =
                $("#filtro_ubicacion").val() == "" ||
                $("#filtro_ubicacion").val() == null ?
                "--" :
                $("#filtro_ubicacion").val();

            if (gestion_filtro == "--") {
                alertToast("Debe seleccionar un Estado de Gestión", 3500);
                return false;
            }

            var urlKeeps = "exportarExcelIndividual";
            var fill = {
                gestion: gestion_filtro, //busca el id y lo almacena en la variable
                nombre_gabinete: nombre_gabinete,
            };

            this.cargando = true;
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status == 200)
                        document.querySelector("#hrefGabineteGenerado").click();
                })
                .catch((error) => {
                    this.cargando = false;
                    alertToast("Error, recargue la página", 3500);
                });
        },
        //MAPA CALOR INDIVIDUAL
        //AL SELECCIONAR LA GESTION
        limpiarMapaCompromisoIndividual: function() {
            this.arrayCompromiso = [];
            this.arrayGabinete = [];
            this.arrayInstitucion = [];
            this.habilitarCompromiso = true;
            this.institucion_id_temp = 0;
            this.gabinete_id_temp = 0;
            $("#filtro_gabinete_ind").val(null).change();
            $("#filtro_institucion_ind").val(null).change();
            //this.mostrarMapa = false;
            $("#filtro_compromiso_individual").val(null).change();
        },
        limpiarCompromisoIndividual: function() {
            this.arrayCompromiso = [];
            this.habilitarCompromiso = true;
            $("#filtro_gabinete_ind").val(null).change();
            $("#filtro_institucion_ind").val(null).change();
            //this.mostrarMapa = false;
            $("#filtro_compromiso_individual").val(null).change();
        },
        async filtro_compromiso_cc() {
            this.limpiarCompromisoIndividual();
            //this.mostrarMapa = true;
            this.cargando = true;
            var urlKeeps = "filtro_compromiso_consulta";
            var fill = {
                gestion_id: this.gestion_filtro,
            };
            iniciar_modal_espera();
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    if (this.gestion_filtro != null) {
                        this.habilitarCompromiso = false;
                        this.arrayCompromiso =
                            response.data.datos != null ?
                            response.data.datos : [];
                    }
                    this.cargando = false;
                    parar_modal_espera();
                })
                .catch((error) => {
                    alertToast("Error..., recargue la página", 3500);
                    this.cargando = false;
                });
        },
        //MAPA CALOR2 TODOS LOS COMPROMISOS

        async reporteDinamico_tc() {
            var nombre_ = document.getElementById("filtro_institucion_ind")
                .options[
                    document.getElementById("filtro_institucion_ind").selectedIndex
                ].text;

            console.log("ACA" + nombre_);
            this.cargarGraficoEstado();
            var filtro_ubicacion = $("#filtro_ubicacion").val();
            var filtro_gestion = $("#filtro_gestion").val();
            var filtro_institucion = this.institucion_id_temp;
            var filtro_gabinete = this.gabinete_id_temp;
            var fill = {
                filtro_ubicacion: filtro_ubicacion,
                filtro_gestion: filtro_gestion,
                filtro_institucion: filtro_institucion,
                filtro_gabinete: filtro_gabinete,
            };
            var urlKeeps = "reporteDinamico_tc"; //setea la ruta
            app.cargando = true; //mientras busca la data, el indicador se activa "cargando"
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    this.habilitaImprimir = true;
                    this.nacional_count = response.data.nacional;
                    this.provincia_count = response.data.provincia;
                    this.cantonal_count = response.data.cantonal;
                    //grafico pastel
                    this.grafico2 = formarGrafico_tc(
                        "Gráfico",
                        this.nacional_count,
                        this.provincia_count,
                        this.cantonal_count
                    );
                    $("#grafico1").append(this.grafico1.getSVG());
                    //calculo de porcentajes
                    var total_compromisos =
                        this.nacional_count +
                        this.provincia_count +
                        this.cantonal_count;
                    $("#total_nacional").html(this.nacional_count);
                    $("#total_compromisos").html(parseInt($("#total_nacional").html()) + parseInt($("#total_provincias").html()) + parseInt($("#total_cantones").html()));


                    //aqui va el response data
                })
                .catch((error) => {
                    app.cargando = false; //desaparece indicador de cargando
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },
        async exportarReporteIndividual() {
            var doc = new jsPDF({
                orientation: "horizontal",
                unit: "pt",
            });
            doc.setFont("courier");
            doc.setFontType("normal");
            doc.setFontSize(10);

            var elementHTML = $("#page-pdf").html();
            var specialElementHandlers = {
                "#elementH": function(element, renderer) {
                    return true;
                },
            };
            doc.fromHTML(elementHTML, 0, 0, {
                width: 100,
                elementHandlers: specialElementHandlers,
            });
            // Save the PDF
            setTimeout(function() {
                doc.save(`sample-document.pdf`);
            }, 2000);
            //doc.save('sample-document.pdf');
        },
        async consultaDatosUbicacion() {
            let filtro_compromiso = 0,
                filtro_ubicacion = 0,
                filtro_gestion = 0;

            if ($("#filtro_compromiso_individual").val())
                filtro_compromiso = $("#filtro_compromiso_individual").val();
            if ($("#filtro_ubicacion").val())
                filtro_ubicacion = $("#filtro_ubicacion").val();
            let filtro_gabinete =
                $("#filtro_gabinete_ind").val() == "" ||
                $("#filtro_gabinete_ind").val() == null ? [] :
                $("#filtro_gabinete_ind").val();
            let filtro_institucion =
                $("#filtro_institucion_ind").val() == "" ||
                $("#filtro_institucion_ind").val() == null ? [] :
                $("#filtro_institucion_ind").val();
            if ($("#filtro_gestion").val())
                filtro_gestion = $("#filtro_gestion").val();
            filtro_compromiso = app.filtro_compromiso_individual;
            if (app.filtro_compromiso_individual.length == 0)
                filtro_compromiso = 0;
            if ($("#filtro_gestion").val())
                filtro_gestion = $("#filtro_gestion").val();
            let fecha_inicio = $("#fecha_inicio_ubicacion").val() == "" || $("#fecha_inicio_ubicacion").val() == null ? null : $("#fecha_inicio_ubicacion").val();
            let fecha_fin = $("#fecha_fin_ubicacion").val() == "" || $("#fecha_fin_ubicacion").val() == null ? null : $("#fecha_fin_ubicacion").val();
            let error = validarFechasEntradas(fecha_inicio, fecha_fin, null);
            if (!error) return false;
            let fill = {
                filtro_gestion: filtro_gestion,
                filtro_compromiso: filtro_compromiso,
                filtro_ubicacion: filtro_ubicacion,
                filtro_gabinete: filtro_gabinete,
                filtro_institucion: filtro_institucion,
                tipo_detalle: true,
                fecha_inicio: fecha_inicio,
                fecha_fin: fecha_fin,
            };
            this.arregloDatosUbicacionGenerados = fill;
            let urlKeeps = "consultaDatosUbicacionDashboard";
            this.cargando = true;
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status == 200) {
                        //console.log("AQUI consultaDatosUbicacion->", response.data.datatable.original.data);
                        //console.log(filtro_ubicacion, "filtro_ubicacion->", filtro_ubicacion.length);
                        if (filtro_ubicacion != '') {
                            //response.data.datatable.original.data.forEach(element => cargarMapaCantonal($('#filtro_ubicacion option:selected').text().substring(5).toLowerCase()))
                            //response.data.datatable.original.data.forEach(element => cargarMapaCantonal(element['clase'].substring(7)))
                            //cargarMapaCantonal(response.data.datatable.original.data[0]['clase'].substring(7));
                            //
                            filtro_ubicacion_seleccionado = true;
                            ubicacion = $('#filtro_ubicacion option:selected').text();
                            if (ubicacion.indexOf(" - ") != -1) {
                                ubicacion = ubicacion.split(" - ");
                                ubicacion = ubicacion[1];
                                cargarMapaCantonal(ubicacion.toLowerCase());
                            }
                            /*valor = $('#filtro_ubicacion option:selected').text().replace("01 ", " ").replace("02 ", " ").replace("03 ", " ").replace("04 ", " ").replace("05 ", " ").replace("06 ", " ");
                            valor = valor.split(" - ");
                            for (var i = 0; i < valor.length; i++) {
                                if (valor[i] != '')
                                    cargarMapaCantonal(valor[i].toLowerCase());
                            } */
                        } else {
                            filtro_ubicacion_seleccionado = false;
                            agregaDatosMapa(response.data.mapa);
                        }
                        app.arregloDatosCompromisosBasico = response.data.compromisos.original.data;
                        cargarUbicacionesMapaSVG(response.data.datatable.original.data);
                        generaDataTableMapa(
                            response.data.datatable.original.data
                        );
                        //Genera listado de tabla Detalle de Ubicaciones de Compromisos
                        generarDatatableCompromisos(
                            response.data.compromisos.original.data
                        );
                        generarImagenCanvas();
                    }
                })
                .catch((error) => {
                    this.cargando = false;
                    alertToast("Error, recargue la página", 3500);
                });
        },
        async exportChartAsPNG(chart) {
            var svg = chart.getSVG({
                chart: {
                    width: chart.chartWidth,
                    height: chart.chartHeight
                }
            });
            var canvas = document.createElement('canvas');
            canvas.width = chart.chartWidth;
            canvas.height = chart.chartHeight;
            var ctx = canvas.getContext('2d');

            return new Promise((resolve) => {
                var img = new Image();
                img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svg)));

                img.onload = function() {
                    ctx.drawImage(img, 0, 0);
                    var base64Image = canvas.toDataURL('image/png');
                    resolve(base64Image);
                };
            });
        },
        async convertirImagenMapaSvg() {

            const svgImage = document.getElementById('Capa_1');
            const svg = new XMLSerializer().serializeToString(svgImage);
            var canvas = document.createElement('canvas');
            var ctx = canvas.getContext('2d');

            const viewBox = svgImage.getAttribute('viewBox');
            const [x, y, width, height] = viewBox.split(' ');
            canvas.width = `${width}`;
            canvas.height = `${height}`;
            canvas.width = "470.967";
            canvas.height = "470.967";

            return new Promise((resolve) => {
                var img = new Image();
                img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svg)));
                //  resolve(img.src);
                /*    var base64Image = canvas.toDataURL('image/png');
                    resolve(base64Image);*/
                img.onload = function() {
                    ctx.drawImage(img, 0, 0);
                    var base64Image = canvas.toDataURL('image/png');
                    resolve(base64Image);
                };
            });
        },

        async generarPdf() {

            let chart = $('#containerAmbito').highcharts();
            // let image = await this.exportChartAsBase64PNG(chart);
            var image = await this.exportChartAsPNG(chart);
            var image_svg = await this.convertirImagenMapaSvg();
            var urlKeeps = "generarPdf";
            this.cargando = true;
            if (this.arregloDatosUbicacionGenerados == null) {
                alertToast("Aun no ha filtrado la información que requiere", 3500);
                return false;
            }
            this.arregloDatosUbicacionGenerados.imagen = image;
            this.arregloDatosUbicacionGenerados.imagen_mapa_svg = image_svg;

            await axios
                .post(urlKeeps, this.arregloDatosUbicacionGenerados)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status == 200) {
                        $url = response.data.message;
                        var url_inicia = document.querySelector("#inicializacion").value + '/storage' + $url;
                        downloadURI(url_inicia)
                    }
                })
                .catch((error) => {
                    alertToast("Error..., recargue la página", 3500);
                    this.cargando = false;
                });
        },
        async cargarGraficoEstado() {
            let filtro_gabinete =
                $("#filtro_gabinete_ind").val() == "" ||
                $("#filtro_gabinete_ind").val() == null ? [] :
                $("#filtro_gabinete_ind").val();
            let filtro_institucion =
                $("#filtro_institucion_ind").val() == "" ||
                $("#filtro_institucion_ind").val() == null ? [] :
                $("#filtro_institucion_ind").val();

            this.cargando = true;
            this.graficos_estados = true;
            var urlKeeps = "consultaMostrarGraficoEstado";
            let ubicacion_filtro = $("#filtro_ubicacion").val();
            let gestion_filtro = $("#filtro_gestion").val();
            let fecha_inicio = $("#fecha_inicio_ubicacion").val() == "" || $("#fecha_inicio_ubicacion").val() == null ? null : $("#fecha_inicio_ubicacion").val();
            let fecha_fin = $("#fecha_fin_ubicacion").val() == "" || $("#fecha_fin_ubicacion").val() == null ? null : $("#fecha_fin_ubicacion").val();
            let error = validarFechasEntradas(fecha_inicio, fecha_fin, null);
            if (!error) return false;
            //console.log("AQUI_" + ubicacion_filtro + $("filtro_institucion_ind").val());
            /* var institucion_filtro = this.institucion_id_temp;
            var gabinete_filtro = this.gabinete_id_temp;*/
            let fill = {
                filtro_ubicacion: ubicacion_filtro,
                filtro_gestion: gestion_filtro,
                filtro_institucion: filtro_institucion,
                filtro_gabinete: filtro_gabinete,
                fecha_inicio: fecha_inicio,
                fecha_fin: fecha_fin,
            };
            axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
                    this.grafico1 = formarGraficoEstado(
                        response.data.datos,
                        "grafico_estado"
                    );
                })
                .catch((error) => {
                    app.cargando = false;
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },
        async reporteDinamico_tc() {
            $("#total_compromisos").html(0);
            $("#total_nacional").html(0);
            $("#total_provincias").html(0);
            $("#total_cantones").html(0);
            let filtro_ubicacion = $("#filtro_ubicacion").val();
            let filtro_gabinete =
                $("#filtro_gabinete_ind").val() == "" ||
                $("#filtro_gabinete_ind").val() == null ? [] :
                $("#filtro_gabinete_ind").val();
            let filtro_institucion =
                $("#filtro_institucion_ind").val() == "" ||
                $("#filtro_institucion_ind").val() == null ? [] :
                $("#filtro_institucion_ind").val();

            let filtro_gestion = $("#filtro_gestion").val();
            let fecha_inicio = $("#fecha_inicio_ubicacion").val() == "" || $("#fecha_inicio_ubicacion").val() == null ? null : $("#fecha_inicio_ubicacion").val();
            let fecha_fin = $("#fecha_fin_ubicacion").val() == "" || $("#fecha_fin_ubicacion").val() == null ? null : $("#fecha_fin_ubicacion").val();
            let error = validarFechasEntradas(fecha_inicio, fecha_fin, null);
            if (!error) return false;

            this.cargarGraficoEstado();
            /*
            var institucion_filtro = this.institucion_id_temp;
            var gabinete_filtro = this.gabinete_id_temp;
            */
            var fill = {
                filtro_ubicacion: filtro_ubicacion,
                filtro_gestion: filtro_gestion,
                filtro_institucion: filtro_institucion,
                filtro_gabinete: filtro_gabinete,
                fecha_inicio: fecha_inicio,
                fecha_fin: fecha_fin,
            };
            var urlKeeps = "reporteDinamico_tc"; //setea la ruta
            app.cargando = true; //mientras busca la data, el indicador se activa "cargando"
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    app.cargando = false;
                    this.habilitaImprimir = true;
                    this.nacional_count = response.data.nacional;
                    this.provincia_count = response.data.provincia;
                    this.cantonal_count = response.data.cantonal;
                    //grafico pastel
                    this.grafico2 = formarGrafico_tc(
                        "Gráfico",
                        this.nacional_count,
                        this.provincia_count,
                        this.cantonal_count
                    );
                    $("#grafico1").append(this.grafico1.getSVG());
                    //calculo de porcentajes
                    var total_compromisos =
                        this.nacional_count +
                        this.provincia_count +
                        this.cantonal_count;
                    $("#total_nacional").html(this.nacional_count);
                    $("#total_compromisos").html(parseInt($("#total_nacional").html()) + parseInt($("#total_provincias").html()) + parseInt($("#total_cantones").html()));

                    //aqui va el response data
                })
                .catch((error) => {
                    app.cargando = false; //desaparece indicador de cargando
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },
        async cargarHtmlLugares(lugar) {
            var urlKeeps = document.querySelector("#inicializacion").value + "/cargarHtmlLugares";
            let fill = {
                lugar: lugar
            }
            this.cargando = true;
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.cargando = false;
                    this.botonRetornoHabilitado = lugar;
                    $("#mapa_svg").html(response.data);
                    habilitarUbicacionesMapa();
                })
                .catch((error) => {
                    alertToast("Error al cargar lugar, recargue la página", 3500);
                    this.cargando = false;
                });
        },
    },
});