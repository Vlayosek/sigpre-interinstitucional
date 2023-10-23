var app = new Vue({
    el: '#main',
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
                'id': '',
                'nombre_compromiso': '',
                'institucion_responsable': '',
                'institucion_corresponsable': [],
                'gabinete_sectorial': '',
                'fecha_inicio': '',
                'fecha_fin': '',
                'canton': '',
                'tipo_ubicacion': '',
                'objetivo': [],
                'tipo_reporte': ''
            },
            arrayCompromisos: [],
            arrayPeriodo: [],
            arrayCompromiso: [],
            mostrarFiltroPeriodo: false,
            mostrarFiltroCompromiso: false,
            hoy: '',
            habilitarCompromiso: true,
            filtro_compromiso_individual: [],
            filtro_compromiso_individual_join: [],
            institucion_anterior: null,
            graficos_estados: false,
            grafico1: null,
            grafico2: null,
            habilitaImprimir: false,
	    institucion_id_temp: 0,
            gabinete_id_temp: 0
        }
    },
    created: function() {
        this.limpiarReporteEjecutivo();
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
            var fecha_inicio = $("#fecha_inicio_cc").val();
            var fecha_fin = $("#fecha_fin_cc").val();
            var filtro_institucion = $("#filtro_institucion").val();
            var filtro_gabinete = $("#filtro_gabinete").val();
            var nombre_ = document.getElementById('filtro_gabinete').options[document.getElementById('filtro_gabinete').selectedIndex].text;

            var errores = "";

            if (fecha_inicio == "" || fecha_inicio == null)
                errores += '\n Escoja una fecha de inicio';
            if (fecha_fin == "" || fecha_fin == null)
                errores += '\n Escoja una fecha de fin';

            if (errores.length > 0) {
                swal("Errores", errores, "error");
                return false;
            }

            var urlKeeps = 'exportarExcelCumplidos';
            var fill = {
                'fecha_inicio': fecha_inicio, //busca el id y lo almacena en la variable
                'fecha_fin': fecha_fin, //busca el id y lo almacena en la variable
                'filtro_institucion': filtro_institucion, //busca el id y lo almacena en la variable
                'filtro_gabinete': filtro_gabinete,
                'nombre_gabinete': nombre_,
            }

            this.cargando = true;
            await axios.post(urlKeeps, fill).then(response => {
                this.cargando = false;
                if (response.data.status == 200) {
                    alertToastSuccess("Reporte Generado", 3500);
                    var direccion = document.querySelector("#direccionDocumentos").value + '/COMPROMISOS_GENERADOS/' + response.data.documento_nombre;
                    downloadURI(direccion, response.data.documento_nombre);
                    //document.querySelector("#hrefCumplidosGenerado").click();
                }
            }).catch(error => {
                this.cargando = false;
                alertToast("Error, recargue la página", 3500);
            });
        },

        //AL SELECCIONAR EL GABINETE
        async filtro_institucion_cc() {
            if (this.gabinete_id != 0 && this.institucion_anterior == null)
                this.arrayInstitucion = [];
            this.cargando = true;
            var urlKeeps = 'filtro_institucion_cc';
            var fill = {
                'gabinete_id': this.gabinete_id
            }
            iniciar_modal_espera();
            await axios.post(urlKeeps, fill).then(response => {
                this.arrayInstitucion = response.data.datos != null ? response.data.datos : [];
                if (this.institucion_anterior != null) {
                    $("#filtro_institucion").val(this.institucion_anterior).change();
                }
                this.cargando = false;
                parar_modal_espera();
            }).catch(error => {
                alertToast("Error..., recargue la página", 3500);
                this.cargando = false;
            });
        },
        //AL SELECCIONAR INSTITUCION
        limpiarFiltroGabinete: function() {
            this.arrayGabinete = [];
            $("#filtro_gabinete").val(null).change();
        },
        async filtro_gabinete_cc(limpiar = null) {
            this.institucion_anterior = limpiar;
            if (this.institucion_id == 0)
                this.limpiarFiltroGabinete();
            this.cargando = true;
            var urlKeeps = 'filtro_gabinete_cc';
            var fill = {
                'institucion_id': this.institucion_id
            }
            iniciar_modal_espera();
            await axios.post(urlKeeps, fill).then(response => {
                if (this.institucion_id == 0 || this.institucion_id == "")
                    this.arrayGabinete = response.data.datos != null ? response.data.datos : [];
                else {
                    $("#filtro_gabinete").val(response.data.datos.id).change();
                }
                this.cargando = false;
                parar_modal_espera();
            }).catch(error => {
                alertToast("Error..., recargue la página", 3500);
                this.cargando = false;
            });
        },
        //REPORTE EJECUTIVO
        limpiarReporteEjecutivo: function() {
            this.limpiarFormCrear();
            this.mostrarFiltroPeriodo = false;
            this.mostrarFiltroCompromiso = false;
            this.arrayPeriodo = [];
            this.arrayCompromisos = [];
            this.hoy = '';
            $("#filtro_periodos").val(null).change();
            $("#filtro_periodos option:first-child").attr("disabled", "disabled");
            $("#chk_periodo_actual").prop("checked", false);
            $("#filtro_compromisos_detallado").val(null).change();
            $("#filtro_compromisos_detallado option:first-child").attr("disabled", "disabled");
            $("#filtro_compromisos").val(null).change();
            $("#filtro_compromisos option:first-child").attr("disabled", "disabled");
        },
        limpiarPeriodo: function() {
            this.limpiarFormCrear();
            this.mostrarFiltroPeriodo = false;
            this.arrayPeriodo = [];
            this.hoy = '';
            $("#filtro_periodos").val(null).change();
            $("#chk_periodo_actual").prop("checked", false);
        },
        limpiarCompromiso: function() {
            this.limpiarFormCrear();
            this.mostrarFiltroCompromiso = false;
            this.arrayCompromisos = [];
            $("#filtro_compromisos").val(null).change();
            $("#filtro_compromisos option:first-child").attr("disabled", "disabled");
        },
        limpiarFormCrear: function() {
            this.formConsultar.id = '';
            this.formConsultar.nombre_compromiso = '';
            this.formConsultar.institucion_responsable = '';
            this.formConsultar.institucion_corresponsable = [];
            this.formConsultar.gabinete_sectorial = '';
            this.formConsultar.fecha_inicio = '';
            this.formConsultar.fecha_fin = '';
            this.formConsultar.canton = '';
            this.formConsultar.tipo_ubicacion = '';
            this.formConsultar.objetivo = [];
            this.formConsultar.tipo_reporte = '';
        },
        async mostrar_periodo() {
            this.mostrarFiltroPeriodo = true;
            this.limpiarCompromiso();
            if (document.getElementById('chk_periodo_actual').checked == false) {
                this.consulta_periodo_actual();
            }
        },
        async mostrar_compromiso() {
            this.mostrarFiltroCompromiso = true;
            this.limpiarPeriodo();
            this.consulta_compromiso();
        },
        async mostrar_periodo_actual() {
            if (document.getElementById('chk_periodo_actual').checked == true) {
                this.hoy = new Date().toISOString().slice(0, 10);
                this.consulta_periodo_actual();
            } else {
                this.hoy = '';
                this.consulta_periodo_actual();
            }
        },
        async consulta_periodo_actual() {
            this.cargando = true;
            var urlKeeps = 'consulta_periodo_actual';
            var fill = {
                'hoy': this.hoy,
            }
            iniciar_modal_espera();
            await axios.post(urlKeeps, fill).then(response => {
                this.arrayPeriodo = response.data.datos != null ? response.data.datos : [];
                this.cargando = false;
                parar_modal_espera();
            }).catch(error => {
                alertToast("Error..., recargue la página", 3500);
                this.cargando = false;
            });
        },
        async consulta_compromiso() {
            this.cargando = true;
            var urlKeeps = 'consulta_compromiso';
            iniciar_modal_espera();
            var fill = {
                'fecha_inicio_re': $("#fecha_inicio_ejecutivo").val(),
                'fecha_fin_re': $("#fecha_fin_ejecutivo").val()
            }
            await axios.post(urlKeeps, fill).then(response => {
                this.arrayCompromisos = response.data.datos != null ? response.data.datos : [];
                this.cargando = false;
                parar_modal_espera();
            }).catch(error => {
                alertToast("Error..., recargue la página", 3500);
                this.cargando = false;
            });
        },
        async consultaReporteEjecutivo(ejecutivo = null) {
            var errores = '';
            if (this.formConsultar.id == null || this.formConsultar.id == 0)
                errores += '\n Debe seleccionar un compromiso';
            if (errores.length > 0) {
                swal("Errores", errores, "error");
                return false;
            }
            var urlKeeps = 'consultaReporteEjecutivo';
            var fill = {
                'id': this.formConsultar.id,
                'tipo_periodo': this.mostrarFiltroPeriodo,
                'tipo_compromiso': this.mostrarFiltroCompromiso,
            }
            this.cargando = true;
            await axios.post(urlKeeps, fill).then(response => {
                this.cargando = false;
                if (response.data.datos != null) {
                    this.formConsultar = response.data.datos;
                    this.formConsultar.institucion_corresponsable = response.data.corresponsables != null ? response.data.corresponsables : [];
                    this.formConsultar.objetivo = response.data.objetivos != null ? response.data.objetivos : [];
                    this.formConsultar.tipo_reporte = ejecutivo;
                    this.generaReporteEjecutivo();
                } else {
                    alertToast("Error, No hay datos", 3500);
                }
            }).catch(error => {
                this.cargando = false;
                alertToast("Error, recargue la página", 3500);
            });
        },
        async generaReporteEjecutivo() {
            var urlKeeps = 'generaReporteEjecutivo';
            this.cargando = true;
            await axios.post(urlKeeps, this.formConsultar).then(response => {
                this.cargando = false;
                if (response.data.status == 200) {
                    alertToastSuccess("Reporte Generado", 3500);
                    var direccion = document.querySelector("#direccionDocumentos").value + '/COMPROMISOS_GENERADOS/' + response.data.documento_nombre;
                    downloadURI(direccion, response.data.documento_nombre);
                    //document.querySelector("#hrefGenerado").click();
                    if (this.formConsultar.tipo_reporte == true)
                        $("#cerrar_reporte_ejecutivo").click();
                    else
                        $("#cerrar_reporte_detallado").click();

                }
            }).catch(error => {
                this.cargando = false;
                alertToast("Error, recargue la página", 3500);
            });
        },
        //REPORTE COMPROMISOS MINISTERIO
        limpiarCompromisoMinisterio: function() {
            $("#filtro_inst").val(null).change();
        },
        async exportarExcelMinisterio() {

            var institucion_filtro = $("#filtro_inst").val() == "" || $("#filtro_inst").val() == null ? "--" : $("#filtro_inst").val();
            var nombre_ = document.getElementById('filtro_inst').options[document.getElementById('filtro_inst').selectedIndex].text;

            if (institucion_filtro == "--") {
                alertToast("Debe seleccionar una institución", 3500);
                return false;
            }

            var urlKeeps = 'exportarExcelMinisterio';
            var fill = {
                'institucion': institucion_filtro, //busca el id y lo almacena en la variable
                'nombre_institucion': nombre_,
            }

            this.cargando = true;
            await axios.post(urlKeeps, fill).then(response => {
                this.cargando = false;
                if (response.data.status == 200) {
                    alertToastSuccess("Reporte Generado", 3500);
                    var direccion = document.querySelector("#direccionDocumentos").value + '/COMPROMISOS_GENERADOS/' + response.data.documento_nombre;
                    downloadURI(direccion, response.data.documento_nombre);
                    //document.querySelector("#hrefMinisterioGenerado").click();
                }
            }).catch(error => {
                this.cargando = false;
                alertToast("Error, recargue la página", 3500);
            });
        },
        //EXPORTAR REPORTE COMPROMISOS PRESIDENCIALES GABINETE
        limpiarCompromisoGabinete: function() {
            $("#filtro_gab").val(null).change();
        },
        async exportarExcelGabinete() {

            var gabinete_filtro = $("#filtro_gab").val() == "" || $("#filtro_gab").val() == null ? "--" : $("#filtro_gab").val();
            var nombre_gabinete = document.getElementById('filtro_gab').options[document.getElementById('filtro_gab').selectedIndex].text;

            if (gabinete_filtro == "--") {
                alertToast("Debe seleccionar un Gabinete Sectorial", 3500);
                return false;
            }

            var urlKeeps = 'exportarExcelGabinete';
            var fill = {
                'gabinete': gabinete_filtro, //busca el id y lo almacena en la variable
                'nombre_gabinete': nombre_gabinete,
            }

            this.cargando = true;
            await axios.post(urlKeeps, fill).then(response => {
                this.cargando = false;
                if (response.data.status == 200) {
                    alertToastSuccess("Reporte Generado", 3500);
                    var direccion = document.querySelector("#direccionDocumentos").value + '/COMPROMISOS_GENERADOS/' + response.data.documento_nombre;
                    downloadURI(direccion, response.data.documento_nombre);
                    //document.querySelector("#hrefGabineteGenerado").click();
                }
            }).catch(error => {
                this.cargando = false;
                alertToast("Error, recargue la página", 3500);
            });
        },
        async exportarExcelIndividual() {

            var gestion_filtro = $("#filtro_gestion").val() == "" || $("#filtro_gestion").val() == null ? "--" : $("#filtro_gestion").val();
            var compromiso = $("#filtro_compromiso_individual").val() == "" || $("#filtro_compromiso_individual").val() == null ? "--" : $("#filtro_compromiso_individual").val();
            var ubicacion = $("#filtro_ubicacion").val() == "" || $("#filtro_ubicacion").val() == null ? "--" : $("#filtro_ubicacion").val();

            if (gestion_filtro == "--") {
                alertToast("Debe seleccionar un Estado de Gestión", 3500);
                return false;
            }

            var urlKeeps = 'exportarExcelIndividual';
            var fill = {
                'gestion': gestion_filtro, //busca el id y lo almacena en la variable
                'nombre_gabinete': nombre_gabinete,
            }

            this.cargando = true;
            await axios.post(urlKeeps, fill).then(response => {
                this.cargando = false;
                if (response.data.status == 200)
                    document.querySelector("#hrefGabineteGenerado").click();
            }).catch(error => {
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
            var urlKeeps = 'filtro_compromiso_consulta';
            var fill = {
                'gestion_id': this.gestion_filtro,
            }
            iniciar_modal_espera();
            await axios.post(urlKeeps, fill).then(response => {
                if (this.gestion_filtro != null) {
                    this.habilitarCompromiso = false;
                    this.arrayCompromiso = response.data.datos != null ? response.data.datos : [];
                }
                this.cargando = false;
                parar_modal_espera();
            }).catch(error => {
                alertToast("Error..., recargue la página", 3500);
                this.cargando = false;
            });
        },
        //MAPA CALOR2 TODOS LOS COMPROMISOS
        async cargarGraficoEstado() {
            this.cargando = true;
            this.graficos_estados = true;
            var urlKeeps = 'consultaMostrarGraficoEstado';
            let ubicacion_filtro = $("#filtro_ubicacion").val();
            let gestion_filtro = $("#filtro_gestion").val();
            //console.log("AQUI_" + ubicacion_filtro + $("filtro_institucion_ind").val());
            var institucion_filtro = this.institucion_id_temp;
            var gabinete_filtro = this.gabinete_id_temp;
            let fill = {
                'ubicacion_filtro': ubicacion_filtro,
                'gestion_filtro': gestion_filtro,
                'institucion_filtro': institucion_filtro,
                'gabinete_filtro': gabinete_filtro,
            }
            axios.post(urlKeeps, fill).then(response => {
                this.cargando = false;
                this.grafico1 = formarGraficoEstado(response.data.datos, 'grafico_estado');
            }).catch(error => {
                app.cargando = false;
                swal("Cancelado!", "Error al grabar...", "error");
            });
        },
        async reporteDinamico_tc() {
            var nombre_ = document.getElementById('filtro_institucion_ind').options[document.getElementById('filtro_institucion_ind').selectedIndex].text;

            console.log("ACA" + nombre_);
            this.cargarGraficoEstado();
            var ubicacion_filtro = $("#filtro_ubicacion").val();
            var gestion_filtro = $("#filtro_gestion").val();
            var institucion_filtro = this.institucion_id_temp;
            var gabinete_filtro = this.gabinete_id_temp;
            var fill = {
                'ubicacion_filtro': ubicacion_filtro,
                'gestion_filtro': gestion_filtro,
                'institucion_filtro': institucion_filtro,
                'gabinete_filtro': gabinete_filtro,
            }
            var urlKeeps = 'reporteDinamico_tc'; //setea la ruta
            app.cargando = true; //mientras busca la data, el indicador se activa "cargando"
            await axios.post(urlKeeps, fill)
                .then(response => {
                    app.cargando = false;
                    this.habilitaImprimir = true;
		    this.nacional_count = response.data.nacional;
                    this.provincia_count = response.data.provincia;
                    this.cantonal_count = response.data.cantonal;
                    //grafico pastel
                    this.grafico2 = formarGrafico_tc("Gráfico", this.nacional_count, this.provincia_count, this.cantonal_count);
                    $("#grafico1").append(this.grafico1.getSVG());
                    //calculo de porcentajes
                    var total_compromisos = this.nacional_count + this.provincia_count + this.cantonal_count;
		    $("#total_compromisos").html(total_compromisos);
                    $("#total_nacional").html(this.nacional_count);
                    ////$("#total_provincias").html(this.provincia_count);
                    ////$("#total_cantones").html(this.cantonal_count);
                    ////this.nacional_porcentaje = ((this.nacional_count * 100) / total_compromisos).toFixed(1) + '%';
                    ////this.provincia_porcentaje = ((this.provincia_count * 100) / total_compromisos).toFixed(1) + '%';
                    ////this.cantonal_porcentaje = ((this.cantonal_count * 100) / total_compromisos).toFixed(1) + '%';

                    //aqui va el response data
                }).catch(error => {
                    app.cargando = false; //desaparece indicador de cargando
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },
        async exportarReporteIndividual() {
            var doc = new jsPDF({
                orientation: 'horizontal',
                unit: 'pt'
            });
            doc.setFont("courier");
            doc.setFontType("normal");
            doc.setFontSize(10);

            var elementHTML = $('#page-pdf').html();
            var specialElementHandlers = {
                '#elementH': function(element, renderer) {
                    return true;
                }
            };
            doc.fromHTML(elementHTML, 0, 0, {
                'width': 100,
                'elementHandlers': specialElementHandlers
            });
            // Save the PDF
            setTimeout(function() {
                doc.save(`sample-document.pdf`);
            }, 2000);
            //doc.save('sample-document.pdf');
        },
    }
})
