var app = new Vue({
    el: "#main",
    data: {
        abrirFiltro: false,
        btnArchivos: true,
        btn: true,
        deshabilitarPorDesbloqueo: false,
        currentTab_: 1,
        cargando: false,
        asignaciones: true,
        btnTemporal: false,
        btnPendientes: false,
        rolMinistro: 0,
        dataCargar: [],
        arregloObjetivos: [],
        form: [],
        currentTab: 0,
        optimo: 0,
        bueno: 0,
        leve: 0,
        moderado: 0,
        grave: 0,
        planificacion: 0,
        cumplido: 0,
        cerrado: 0,
        standby: 0,
        registrados: 0,
        asignaciones_: 0,
        temporales_: 0,
        pendientes_: 0,
        ejecucion: 0,
        visibleNotificar: false,
        visibleRechazar: false,
        disableDatePicker: false,
        gestiones: true,
        calendario: false,
        calendario_finalizacion: false,
        busquedas: false,
        exportaciones: false,
        arrayGabineteBusqueda: [],
        arrayInstitucionBusqueda: [],
        institucion_anterior: null,
        mensajes_busqueda: 0,
        avances_busqueda: 0,
        archivos_busqueda: 0,
        objetivos_busqueda: 0,
        formCronograma: {
            caracterizacion: "",
            cumplimiento_acumulado: 0,
            cumplimiento_periodo: 0,
            cumplimiento_periodo_porcentaje: 0,
            descripcion_meta: "",
            fecha_fin_periodo: "",
            fecha_inicio_periodo: "",
            id: 0,
            meta_acumulada: 0,
            meta_periodo: 0,
            numero: "--",
            objetivo_id: 0,
            observaciones: "",
            pendiente_acumulado: 0,
            pendiente_periodo: 0,
            periodo: "",
            temporalidad: "",
            valor_anterior_meta_acumulada: 0,
            valor_anterior_cumplimiento_acumulado: 0,
        },
        formCrear: {
            id: "0",
            fecha_inicio_compromiso: "",
            fecha_fin_compromiso: "",
            nombre_compromiso: "",
            detalle_compromiso: "",
            avance_compromiso: "",
            avance_id: "0",
            notas_compromiso: "",
            cumplimiento: "0",
            avance: "0",
            tipo_compromiso_id: "",
            origen_id: "",
            monitor_id: "",
            estado_porcentaje_id: "1",
            estado_id: "1",
            responsable_id: "",
            delegado_id: "",
            gabinete_id: "",
            institucion_id: "",
            instituciones_corresponsables: [],
            codigo: "",
            cerrado: "false",
        },
        formAntecedente: {
            idAntecedente: "0",
            antecedente: "",
            fecha_antecedente: "",
        },
        formMensaje: {
            id: 0,
            descripcion: "",
        },
        formAvance: {
            idAvance: "0",
            descripcion: "",
        },
        formNegar: {
            id: "0",
            motivo: "",
        },
        formObjetivo: {
            idObjetivo: "0",
            objetivo: "",
            temporalidad_id: "",
            descripcion_meta: "",
            fecha_inicio_objetivo: "",
            fecha_fin_objetivo: "",
            meta: "0",
            tipo_objetivo_id: 1,
        },
        cargarGestiones: function() {
            this.currentTab_ = 1;
            this.gestiones = true;
            this.calendario = false;
            this.busquedas = false;
            this.exportaciones = false;
            this.calendario_finalizacion = false;

            // this.limpiarReporteFiltro();
            // datatableSeguridadInformacion('PENDIENTE');
        },
        verificaExistePaginaCalendario: function() {
            //$('.pagina-calendar').empty();
        },
        cargarPaginaCalendario: async function(ruta) {
            const html = await axios.get(ruta);
            const { data } = html;
            $('.pagina-calendar').html(data);
        },
        cargarCalendario: async function() {
            this.currentTab_ = 4;
            this.gestiones = false;
            this.calendario = true;
            this.busquedas = false;
            this.exportaciones = false;
            this.calendario_finalizacion = false;
            this.verificaExistePaginaCalendario();
            await this.cargarPaginaCalendario('calendarios');
        },
        cargarCalentarioFinalizaciones: async function() {
            this.currentTab_ = 30;
            this.gestiones = false;
            this.calendario = false;
            this.busquedas = false;
            this.exportaciones = false;
            this.calendario_finalizacion = true;
            this.verificaExistePaginaCalendario();
            await this.cargarPaginaCalendario('calendario_finalizacion');
        },
        cargarBusquedas: function() {
            this.currentTab_ = 25;
            this.gestiones = false;
            this.calendario = false;
            this.busquedas = true;
            this.exportaciones = false;
            this.calendario_finalizacion = false;

            //this.limpiarBusqueda();
            this.gabineteBusqueda();
            this.institucionBusqueda();
            datatableCompromisosBusquedas("MENSAJES");
        },
        objEditar: { id: 0 },
        objEditarCronograma: { id: 0 },

        tableHistorico: "",

        arregloProvincias: [],
        arregloProvinciasCheckeadas: [],
        arregloCantones: [],
        arregloCantonesCheckeadas: [],
        arregloParroquias: [],
        arragloNombreCodigoCompromisos: [],
        arragloCodigosCompromisos: [],


        checkUbicacion: false,
        modifica_responsable: false,
        modifica_corresponsable: false,
        customFile: "",
        linkNav: 0,
        myDate: new Date().toISOString().slice(0, 10),
        fecha: null,
        crear: true,
        tabla: null,
        tipoActual: null,
        filtro: false, //FC
        datos: [],
        datos_instituciones: [],
        pagination: {
            total: 0,
            current_page: 0,
            per_page: 0,
            last_page: 0,
            from: 0,
            to: 0,
        },
        offset: 4,
        filtro_busqueda: false,
        habilitarFechaInicio: true,
        habilitarFechaFin: false,
        habilitarFechaCumplido: false,
        habilitarFechaAntecedente: false,
        habilitarFechaUltimoAvance: false,
        inicializarPorDefector: 0,
        tipo_busqueda: null,
        arregloEstadoGestion: [],
        claseSeleccionadaGestion: 'ACT',
    },

    created: function() {
        this.getKeeps();
        this.getProvincias();
        this.linkNav = 0;
        this.formCrear.codigo = "";
        this.formCrear.id = "0";
        this.formCrear.cerrado = "false";

        this.rolMinistro = document
            .querySelector("#rolMinistro")
            .getAttribute("content");
    },
    mounted: function() {},
    computed: {
        isActived: function() {
            return this.pagination.current_page;
        },
        pagesNumber: function() {
            if (!this.pagination.to) {
                return [];
            }

            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }

            var to = from + this.offset * 2;
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }

            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        },
    },
    methods: {
        descargarCompromisoGenerado: function(archivo) {
            let link =
                document.querySelector("#inicializacion").value +
                "/storage/COMPROMISOS/EXPORTS/" +
                archivo;
            descargarURL(link);
        },
        async getKeeps() {
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/compromisos/consultaEstados";
            let fill = this.llenarDatosEnviar(true);
            await axios.post(urlKeeps, fill).then((response) => {
                this.dataCargar = response.data.datos;
                this.registrados = this.dataCargar.registrados;
                this.optimo = this.dataCargar.optimo;
                this.bueno = this.dataCargar.bueno;
                this.leve = this.dataCargar.leve;
                this.moderado = this.dataCargar.moderado;
                this.grave = this.dataCargar.grave;
                this.planificacion = this.dataCargar.planificacion;
                this.cumplido = this.dataCargar.cumplido;
                this.cerrado = this.dataCargar.cerrado;
                this.standby = this.dataCargar.standby;
                this.ejecucion = this.dataCargar.ejecucion;
                this.asignaciones_ = this.dataCargar.asignaciones_;
                this.pendientes_ = this.dataCargar.pendientes_;
                this.temporales_ = this.dataCargar.temporales_;
            });
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

        llenarDatosEnviar: function(filable = false) {
            let corresponsable = location.href.includes("corresponsable") ?
                "true" :
                "false";
            var data = new FormData();
            let fill = {};
            $(".exportar_monitor").each(function(key, value) {
                let id = value.id;
                let valor = $("#" + id + "").val();
                if (filable) fill[id] = valor;
                else data.append(id, valor);
            });
            if (filable) {

                //  fill["estado"] = this.consultaEstadoporTab(this.tipoActual);
                fill["estado"] = this.tipoActual;
                fill["tabla"] = this.tabla;
                fill["asignaciones"] =
                    this.asignaciones == true ? "true" : "false";
                fill["temporales"] =
                    this.btnTemporal == true ? "true" : "false";
                fill["pendientes"] =
                    this.btnPendientes == true ? "true" : "false";
                fill["habilitarFechaInicio"] =
                    this.habilitarFechaInicio == true ? "true" : "false";
                fill["habilitarFechaFin"] =
                    this.habilitarFechaFin == true ? "true" : "false";
                fill["habilitarFechaCumplido"] =
                    this.habilitarFechaCumplido == true ? "true" : "false";
                fill["habilitarFechaUltimoAvance"] =
                    this.habilitarFechaUltimoAvance == true ? "true" : "false";
                fill["habilitarFechaAntecedente"] =
                    this.habilitarFechaAntecedente == true ? "true" : "false";
                fill["filtro"] = this.filtro == true ? "true" : "false";
                fill["corresponsable"] = corresponsable;
            } else {

                // data.append("estado", this.claseSeleccionadaGestion!=""?this.claseSeleccionadaGestion:"ACT");
                data.append("estado", this.tipoActual);
                data.append("tabla", this.tabla);
                data.append(
                    "asignaciones",
                    this.asignaciones == true ? "true" : "false"
                );
                data.append(
                    "temporales",
                    this.btnTemporal == true ? "true" : "false"
                );
                data.append(
                    "pendientes",
                    this.btnPendientes == true ? "true" : "false"
                );

                data.append(
                    "habilitarFechaInicio",
                    this.habilitarFechaInicio == true ? "true" : "false"
                );
                data.append(
                    "habilitarFechaFin",
                    this.habilitarFechaFin == true ? "true" : "false"
                );
                data.append(
                    "habilitarFechaCumplido",
                    this.habilitarFechaCumplido == true ? "true" : "false"
                );
                data.append(
                    "habilitarFechaAntecedente",
                    this.habilitarFechaAntecedente == true ? "true" : "false"
                );
                data.append(
                    "habilitarFechaUltimoAvance",
                    this.habilitarFechaUltimoAvance
                );
                data.append("corresponsable", corresponsable);
            }
            if (filable) return fill;
            else return data;
        },
        async exportarExcelAvanzado() {
            this.cargando = true;
            let data = this.llenarDatosEnviar();
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/reportes/compromisos/exportarExcelAvanzado";
            this.limpiarMensajes();
            this.cargando = true;
            await axios
                .post(urlKeeps, data)
                .then((response) => {
                    if (response.data.status == 200) {
                        $("#cerrar_modal_filtro_monitor").trigger("click");
                        this.cargando = false;
                        swal(
                            "Generación de Archivos",
                            "Por favor revise su exportación",
                            "success"
                        );
                    } else swal("Cancelado!", "Error al cargar...", "error");
                })
                .catch((error) => {
                    this.cargando = false;
                    swal("Cancelado!", "Error al cargar...", "error");
                });
        },
        filtrarDatosAvanzado: function() {
            this.filtro = true;
            app.resetearBotones();
            //   datatableCargar();
            $("#cerrar_modal_filtro_monitor").trigger("click");
            this.btnPendientes = false;
            this.asignaciones = false;
            this.btnTemporal = false;
            $("#estado_inicial").trigger("click");
            this.currentTab = 0;
        },

        exportarExcel: function() {
            var fecha_inicio =
                $("#fecha_inicio_exportar").val() == "" ||
                $("#fecha_inicio_exportar").val() == null ?
                "null" :
                $("#fecha_inicio_exportar").val();
            var fecha_fin =
                $("#fecha_fin_exportar").val() == "" ||
                $("#fecha_fin_exportar").val() == null ?
                "null" :
                $("#fecha_fin_exportar").val();
            var institucion_id_exportar =
                $("#institucion_id_exportar").val() == "" ||
                $("#institucion_id_exportar").val() == null ?
                "null" :
                $("#institucion_id_exportar").val();
            var gabinete_id_exportar =
                $("#gabinete_id_exportar").val() == "" ||
                $("#gabinete_id_exportar").val() == null ?
                "null" :
                $("#gabinete_id_exportar").val();
            if (fecha_inicio == "null" || fecha_fin == "null") {
                alertToast("Debe colocar un rango de fecha", 3500);
                return false;
            }
            var fecha1 = moment(fecha_inicio);
            var fecha2 = moment(fecha_fin);
            var fecha3 = fecha2.diff(fecha1, "days");
            if (fecha3 > 1460) {
                alertToast(
                    "Los rangos de fechas no pueden extender 4 años",
                    3500
                );
                return false;
            }
            // this.getDatatableCompromisosGETServerSide();

            var url =
                "/reportes/compromisos/exportarExcelGET/" +
                fecha_inicio +
                "/" +
                fecha_fin +
                "/" +
                app.tipoActual +
                "/" +
                app.tabla +
                "/" +
                app.asignaciones +
                "/" +
                app.btnTemporal +
                "/" +
                app.btnPendientes +
                "/" +
                this.filtro +
                "/" +
                institucion_id_exportar +
                "/" +
                gabinete_id_exportar;
            swal({
                title: "Loading...",
                text: "Please wait",
                icon: "/images/loading.gif",
                button: false,
                closeOnClickOutside: false,
                closeOnEsc: false,
                showConfirmButton: false,
            });
            axios
                .get(url, {
                    responseType: "blob", //Change the responseType to blob
                })
                .then((resp) => {
                    if (resp.status == 200) {
                        $("#cerrar_modal_filtro").click();
                        $(".confirm").click();
                        let blob = new Blob([resp.data], {
                            type: "application/vnd.ms-excel",
                        });
                        let link = URL.createObjectURL(blob);
                        let name =
                            "compromisos_" +
                            fecha_inicio +
                            "_" +
                            fecha_fin +
                            "_" +
                            ".xlsx";
                        descargarURL(link, name);
                    } else {
                        $(".confirm").click();
                        alertToast("error al generar excel", 2500);
                    }
                })
                .catch((error) => {
                    $(".confirm").click();
                    alertToast("error al generar excel", 2500);
                })
                .finally(() => {
                    $(".confirm").click();
                });
        },

        filtrarDatos: function() {
            this.filtro = true;
            this.getKeeps();
            app.resetearBotones();
            datatableCargar();
        },

        resetearFiltroBusqueda: function() {
            this.filtro_busqueda = false;
            $("#gabinete_id_busqueda").val(null).change();
            $("#institucion_id_busqueda").val(null).change();
            $("#monitor_busqueda").val(null).change();
            datatableCompromisosBusquedas();
        },
        quitarFiltro: function() {
            this.filtro = false;
            $("#institucion_id_exportar").val(null).trigger("change");
            $("#gabinete_id_exportar").val(null).change();
            this.getKeeps();
            datatableCargar();
        },
        changePage: function(page) {
            this.pagination.current_page = page;
            this.getDatatableCompromisosGETServerSide(page);
        },

        getDatatableCompromisosGETServerSide: function(page) {
            var fecha_inicio =
                $("#fecha_inicio_exportar").val() == "" ||
                $("#fecha_inicio_exportar").val() == null ?
                "null" :
                $("#fecha_inicio_exportar").val();
            var fecha_fin =
                $("#fecha_fin_exportar").val() == "" ||
                $("#fecha_fin_exportar").val() == null ?
                "null" :
                $("#fecha_fin_exportar").val();
            var institucion_id_exportar =
                $("#institucion_id_exportar").val() == "" ||
                $("#institucion_id_exportar").val() == null ?
                "null" :
                $("#institucion_id_exportar").val();
            var gabinete_id_exportar =
                $("#gabinete_id_exportar").val() == "" ||
                $("#gabinete_id_exportar").val() == null ?
                "null" :
                $("#gabinete_id_exportar").val();
            if (fecha_inicio == "null" || fecha_fin == "null") {
                alertToast("Debe colocar un rango de fecha", 3500);
                return false;
            }
            var fecha1 = moment(fecha_inicio);
            var fecha2 = moment(fecha_fin);
            var fecha3 = fecha2.diff(fecha1, "days");
            if ((fecha3 < 0) & this.filtro) {
                alertToast(
                    "Las fechas fin no puede ser menor a la fecha de inicio",
                    3500
                );
                return false;
            }
            if (app.tipoActual == "data") this.currentTab = 0;

            iniciar_modal_espera();

            var urlKeeps = "getDatatableCompromisosGETServerSide?page=" + page;
            urlKeeps +=
                "&&estado=" +
                app.tipoActual +
                "&&tabla=" +
                app.tabla +
                "&&asignaciones=" +
                app.asignaciones;
            urlKeeps +=
                "&&temporales=" +
                app.btnTemporal +
                "&&pendientes=" +
                app.btnPendientes;
            urlKeeps +=
                "&&filtro=" + this.filtro + "&&fecha_inicio=" + fecha_inicio;
            urlKeeps +=
                "&&fecha_fin=" +
                fecha_fin +
                "&&institucion_id_exportar=" +
                institucion_id_exportar;
            urlKeeps += "&&gabinete_id_exportar=" + gabinete_id_exportar;
            axios
                .get(urlKeeps)
                .then((response) => {
                    if (this.filtro) $("#cerrar_modal_filtro").click();
                    parar_modal_espera();
                    this.datos = response.data.tasks.data;
                    this.pagination = response.data.pagination;
                })
                .catch((error) => {
                    parar_modal_espera();
                    swal("Cancelado!", "Error al cargar los datos...", "error");
                });
        },
        resetearBotones: function() {
            /*   this.btnTemporal = false;
               this.asignaciones = false;
               this.btnPendientes = false;
               this.currentTab = 0;*/
        },
        agregarNegarAvances: function(id) {
            this.formNegar.id = id;
            this.formNegar.motivo = "";
        },
        buscarAsignaciones: function() {
            this.asignaciones =
                this.asignaciones == true ?
                (this.asignaciones = false) :
                (this.asignaciones = true);
            datatableCargar();
        },
        buscarTemporales: function() {
            this.btnTemporal =
                this.btnTemporal == true ?
                (this.btnTemporal = false) :
                (this.btnTemporal = true);
            datatableCargar();
        },
        buscarPendientes: function() {
            this.btnPendientes =
                this.btnPendientes == true ?
                (this.btnPendientes = false) :
                (this.btnPendientes = true);
            datatableCargar();
        },

        changeInstitucion: function(e) {
            console.log(e);
        },

        editarAvances: function(id, avance) {
            this.limpiarFormularios();
            this.formAvance.idAvance = id;
            this.formAvance.descripcion = avance;
        },

        editarObjetivo: function(
            idO,
            objetivo,
            descripcion,
            temporalidad_id,
            fi,
            ff,
            meta,
            tipo_objetivo_id,
            deshabilitar = "0"
        ) {
            this.limpiarFormularios();
            this.deshabilitarPorDesbloqueo =
                deshabilitar == "0" || deshabilitar == "" ? false : true;
            this.formObjetivo.idObjetivo = idO;
            this.formObjetivo.objetivo = objetivo;
            this.formObjetivo.descripcion_meta = descripcion;
            this.formObjetivo.temporalidad_id = temporalidad_id;
            this.formObjetivo.fecha_inicio_objetivo = fi;
            this.formObjetivo.fecha_fin_objetivo = ff;
            this.formObjetivo.meta = meta;
            //this.formObjetivo.tipo_objetivo_id = tipo_objetivo_id;
            this.formObjetivo.tipo_objetivo_id = 1;
            this.disableDatePicker = true;
        },
        editarAntecedente: function(idA, antecedente, fecha) {
            this.limpiarFormularios();

            this.formAntecedente.idAntecedente = idA;
            this.formAntecedente.fecha_antecedente = fecha;
            this.formAntecedente.antecedente = antecedente;
        },
        async editarMensaje(id) {
            this.limpiarMensajes();
            var data = new FormData();
            data.append("id", id);
            var urlKeeps = "editarMensaje";
            this.limpiarMensajes();
            this.cargando = true;
            await axios
                .post(urlKeeps, data)
                .then((response) => {
                    this.cargando = false;
                    if (response.data.status == 200)
                        this.formMensaje = response.data.datos;
                    else swal("Cancelado!", "Error al cargar...", "error");
                })
                .catch((error) => {
                    this.cargando = false;
                    swal("Cancelado!", "Error al cargar...", "error");
                });
        },

        async editar(idCompromiso) {
            this.visibleNotificar = false;
            resetear();
            this.formCrear.id = idCompromiso;
            this.limpiarFormularios();
            this.cargando = true;
            var urlKeeps = "editar/compromiso/" + this.formCrear.id;
            await axios
                .get(urlKeeps)
                .then((response) => {
                    this.cargando = false;
                    this.objEditar = response.data[0];
                    this.formCrear.id = this.formCrear.id;
                    this.formCrear.fecha_reporte = this.objEditar.fecha_reporte;
                    this.formCrear.fecha_inicio_compromiso =
                        this.objEditar.fecha_inicio;
                    this.formCrear.fecha_fin_compromiso =
                        this.objEditar.fecha_fin;
                    this.formCrear.nombre_compromiso =
                        this.objEditar.nombre_compromiso;
                    this.formCrear.detalle_compromiso =
                        this.objEditar.detalle_compromiso != null ?
                        this.objEditar.detalle_compromiso :
                        "";
                    this.formCrear.avance_compromiso =
                        this.objEditar.avance_compromiso;
                    this.formCrear.avance_id = this.objEditar.avance_id;
                    this.formCrear.notas_compromiso =
                        this.objEditar.notas_compromiso != null ?
                        this.objEditar.notas_compromiso :
                        "";
                    this.formCrear.cumplimiento =
                        this.objEditar.cumplimiento != null ?
                        this.objEditar.cumplimiento :
                        "";
                    this.formCrear.avance =
                        this.objEditar.avance != null ?
                        this.objEditar.avance :
                        "0";
                    this.formCrear.tipo_compromiso_id =
                        this.objEditar.tipo_compromiso_id;
                    this.formCrear.origen_id = this.objEditar.origen_id;
                    this.formCrear.codigo = this.objEditar.codigo;
                    this.formCrear.cerrado = this.objEditar.cerrado.trim();
                    var resp_id = this.objEditar.responsables;
                    var corresp_id = this.objEditar.corresponsables;
                    if (resp_id.length > 0)
                        getCargaDatosInstitucion(
                            "institucion",
                            resp_id[0].institucion_id,
                            this.objEditar.monitor_id
                        );
                    if (corresp_id.length > 0)
                        getCargaDatosInstitucionCorresponsables(
                            "institucion",
                            idCompromiso
                        );

                    this.formCrear.estado_porcentaje_id =
                        this.objEditar.estado_porcentaje_id;
                    this.formCrear.estado_id = this.objEditar.estado_id;
                    this.formCrear.monitor_id = this.objEditar.monitor_id;
                    this.crear = false;

                    if (
                        this.linkNav == 0 &&
                        (this.formCrear.codigo == "" ||
                            this.formCrear.codigo == null) &&
                        this.formCrear.id != "0"
                    )
                        this.visibleNotificar = true;

                    this.arregloObjetivos = this.objEditar.objetivos;
                    this.formCrear.avance =
                        parseFloat(this.formCrear.avance)
                        .toFixed(2)
                        .toString() + "%";
                })
                .catch((error) => {
                    this.cargando = false;

                    $(".confirm").click();
                    swal("Cancelado!", "Error al aprobar...", "error");
                });
        },
        //////CARGAR SELECTORES DE RESPONSABLES
        async onChangeInstitucion() {
            cargarLoading();
            var url = "cargarInstituciones";
            var fill = {
                id: $("#gabinete_id_exportar_monitor").val(),
            };
            axios
                .post(url, fill)
                .then((response) => {
                    if (response.data.status == 200)
                        this.cargarSelectoresExportacion(
                            response.data.datos,
                            "institucion_id_exportar_monitor"
                        );
                    $(".confirm").click();
                })
                .catch((error) => {
                    $(".confirm").click();
                });
        },
        cerrarFiltroDatosMonitor: function() {
            this.abrirFiltro = false;

        },
        cargarDatosPorDefecto: function() {
            this.abrirFiltro = true;
            if (this.inicializarPorDefector == 0) {
                this.onChangeParroquias();
                this.onChangeCantones();
                this.onChangeInstitucion();
                this.onChangeInstitucionCorresponsable();
                this.inicializarPorDefector = 1;
                $("#codigo_compromiso_exportar_monitor").val("").change();
                $("#nombre_compromiso_exportar_monitor").val("").change();
                $("#monitor_id_exportar_monitor").val("").change(); //ojo
                resetearSelectores();
            }
        },
        //////CARGAR SELECTORES DE CORRESPONSABLES
        async onChangeInstitucionCorresponsable() {
            cargarLoading();
            var url = "cargarInstituciones";
            var fill = {
                id: $("#gabinete_id_corresponsable_exportar_monitor").val(),
            };
            axios
                .post(url, fill)
                .then((response) => {
                    if (response.data.status == 200)
                        this.cargarSelectoresExportacion(
                            response.data.datos,
                            "institucion_id_corresponsable_exportar_monitor"
                        );
                    $(".confirm").click();
                })
                .catch((error) => {
                    $(".confirm").click();
                });
        },
        async onChangeCantones() {
            cargarLoading();
            var url = "cargarCantones";
            var fill = {
                id: $("#provincia_id_exportar_monitor").val(),
            };
            axios
                .post(url, fill)
                .then((response) => {
                    if (response.data.status == 200)
                        this.cargarSelectoresExportacion(
                            response.data.datos,
                            "canton_id_exportar_monitor",
                            "TODOS LOS CANTONES"
                        );
                    $(".confirm").click();
                })
                .catch((error) => {
                    $(".confirm").click();
                });
        },
        //Cargar Parroquias FC agos23
        async onChangeParroquias() {
            cargarLoading();
            var url = "cargarParroquias";
            var fill = {
                id: $("#canton_id_exportar_monitor").val(),
            };
            axios
                .post(url, fill)
                .then((response) => {
                    if (response.data.status == 200)
                        this.cargarSelectoresExportacion(
                            response.data.datos,
                            "parroquia_id_exportar_monitor",
                            "TODAS LAS PARROQUIAS"
                        );
                    $(".confirm").click();
                })
                .catch((error) => {
                    $(".confirm").click();
                });
        },

        //////CARGAR SELECTORES DE CORRESPONSABLES
        cargarSelectoresExportacion: function(
            datos,
            id,
            placeholder = "TODAS LAS INSTITUCIONES"
        ) {
            $("#" + id + "").html("");
            getResponsable(id, "", placeholder);
            $.each(datos, function(key, value) {
                getResponsable(id, key, value);
            });
            $("#" + id + "")
                .val("")
                .change();
        },
        async aprobarObjetivo(id) {
            cargarLoading();
            var url = "aprobarObjetivo";
            var fill = {
                id: id,
            };
            axios
                .post(url, fill)
                .then((response) => {
                    if (response.data.status == 200) {
                        $(".confirm").click();
                        datatableCargarObjetivos();
                        alertToastSuccess(
                            "Registro : " + id + " aprobado exitosamente",
                            3500
                        );
                    }
                })
                .catch((error) => {
                    $(".confirm").click();
                    swal("Cancelado!", "Error al aprobar...", "error");
                });
        },
        //El monitor puede desbloquear objetivos aprobados
        //para que el ministro modifique temporalidad, meta final y fecha fin
        async desbloquearObjetivo(id) {
            cargarLoading();
            var url = "desbloquearObjetivo";
            var fill = {
                id: id,
            };
            axios
                .post(url, fill)
                .then((response) => {
                    if (response.data.status == 200) {
                        $(".confirm").click();
                        datatableCargarObjetivos();
                        alertToastSuccess(
                            "Registro : " + id + " desbloqueado exitosamente",
                            3500
                        );
                    }
                })
                .catch((error) => {
                    $(".confirm").click();
                    swal("Cancelado!", "Error al desbloquear...", "error");
                });
        },
        /*    async rechazarObjetivo(id) {
                    var url = 'rechazarObjetivo';
                    cargarLoading();
                    var fill={
                        'id':id
                    }
                    axios.post(url, fill).then(response => {
                        $(".confirm").click()
                        BotonDatatableAvances.click();
                        alertToastSuccess("Registro : " + id + " negado exitosamente", 3500)
                    }).catch(error => {
                        $(".confirm").click()
                        swal("Cancelado!", "Error al negar...", "error");
                    });
            },*/
        async rechazarObjetivo(id) {
            swal({
                    title: "Objetivo Rechazado",
                    // text: "Escriba el motivo del rechazo:",
                    html: true,
                    text: "<textarea id='text_rechazo_objetivo' class='form-control'></textarea>",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    inputPlaceholder: "Escriba el motivo",
                },
                function(inputValue) {
                    if (inputValue === false) return false;
                    if (inputValue === "") {
                        swal.showInputError("Necesita escribir motivo");
                        return false;
                    }
                    app.editar = true;
                    var urlKeeps = "rechazarObjetivo";
                    var fill = {
                        id: id,
                        observacion: document.getElementById(
                            "text_rechazo_objetivo"
                        ).value,
                    };
                    axios
                        .post(urlKeeps, fill)
                        .then((response) => {
                            if (response.data.status == 200) {
                                swal(
                                    "Rechazo del objetivo!",
                                    response.data.datos,
                                    "success"
                                );
                                datatableCargarObjetivos();
                            } else {
                                alertToast("Error, recargue la página", 3500);
                            }
                        })
                        .catch((error) => {
                            alertToast("Error, recargue la página", 3500);
                        });
                }
            );
        },
        async rechazarAvance() {
            var url = "negar/avance";
            cargarLoading();
            axios
                .post(url, this.formNegar)
                .then((response) => {
                    if (response.data.status == 200) {
                        $(".confirm").click();

                        datatableCargarAvances();
                        alertToastSuccess(
                            "Registro : " +
                            this.formNegar.id +
                            " negado exitosamente",
                            3500
                        );
                    } else alertToast(response.data.datos, 3500);
                })
                .catch((error) => {
                    $(".confirm").click();
                    swal("Cancelado!", "Error al negar...", "error");
                });
        },

        async aprobarAvances(id, descripcion) {
            //this.formCrear.avance_compromiso = descripcion;
            let result = await swal({
                    title: "Estás seguro de realizar esta accion",
                    text: "Al confirmar se grabaran los datos exitosamente",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Si!",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: false,
                },
                function(isConfirm) {
                    if (isConfirm) {
                        var url = "aprobar/avance";
                        fill = { id: id };
                        cargarLoading();
                        axios
                            .post(url, fill)
                            .then((response) => {
                                $(".confirm").click();
                                app.formCrear.avance_compromiso =
                                    response.data.descripcion;
                                datatableCargarHistorico();
                                datatableCargarAvances();
                                alertToastSuccess(
                                    "Registro : " +
                                    id +
                                    " aprobado exitosamente",
                                    3500
                                );
                            })
                            .catch((error) => {
                                $(".confirm").click();
                                swal(
                                    "Cancelado!",
                                    "Error al aprobar...",
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

        async eliminarCompromiso(id) {
            swal({
                    title: "Estás seguro de eliminar el compromiso",
                    // text: "Escriba el motivo del rechazo:",
                    html: true,
                    text: "<textarea id='text_rechazo_compromiso' class='form-control'></textarea>",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    inputPlaceholder: "Escriba el motivo",
                },
                function(inputValue) {
                    if (inputValue === false) return false;
                    if (inputValue === "") {
                        swal.showInputError("Necesita escribir motivo");
                        return false;
                    }
                    app.editar = true;
                    var urlKeeps = "eliminarCompromiso";
                    var fill = {
                        id: id,
                        observacion: document.getElementById(
                            "text_rechazo_compromiso"
                        ).value,
                    };
                    cargarLoading();

                    axios
                        .post(urlKeeps, fill)
                        .then((response) => {
                            $(".confirm").click();

                            if (response.data.status == 200) {
                                datatableCargar();
                                app.getKeeps();
                                alertToastSuccess(response.data.datos, 3500);
                            } else alertToast(response.data.datos, 3500);
                        })
                        .catch((error) => {
                            alertToast("Error, recargue la página", 3500);
                        });
                }
            );
        },
        async eliminarAvance(id) {
            let result = await swal({
                    title: "Estás seguro de realizar esta accion",
                    text: "Al confirmar se grabaran los datos exitosamente",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Si!",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: false,
                },
                function(isConfirm) {
                    if (isConfirm) {
                        var url = "eliminar/avance";
                        fill = { id: id };
                        cargarLoading();
                        axios
                            .post(url, fill)
                            .then((response) => {
                                $(".confirm").click();
                                datatableCargarMensajes();
                                alertToastSuccess(
                                    "Registro : " +
                                    id +
                                    " eliminado exitosamente",
                                    3500
                                );
                            })
                            .catch((error) => {
                                $(".confirm").click();
                                swal(
                                    "Cancelado!",
                                    "Error al eliminar...",
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
        async eliminarMensaje(id) {
            let result = await swal({
                    title: "Estás seguro de realizar esta accion",
                    text: "Al confirmar se grabaran los datos exitosamente",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Si!",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: false,
                },
                function(isConfirm) {
                    if (isConfirm) {
                        var url = "eliminar/mensaje";
                        fill = { id: id };
                        cargarLoading();
                        axios
                            .post(url, fill)
                            .then((response) => {
                                $(".confirm").click();
                                datatableCargarMensajes();
                                alertToastSuccess(
                                    "Registro : " +
                                    id +
                                    " eliminado exitosamente",
                                    3500
                                );
                            })
                            .catch((error) => {
                                $(".confirm").click();
                                swal(
                                    "Cancelado!",
                                    "Error al eliminar...",
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
        async eliminarArchivo(id) {
            let result = await swal({
                    title: "Estás seguro de realizar esta accion",
                    text: "Al confirmar se grabaran los datos exitosamente",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Si!",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: false,
                },
                function(isConfirm) {
                    if (isConfirm) {
                        var url = "eliminar/archivo";
                        fill = { id: id };
                        cargarLoading();
                        axios
                            .post(url, fill)
                            .then((response) => {
                                $(".confirm").click();
                                datatableCargarArchivos();
                                alertToastSuccess(
                                    "Registro : " +
                                    id +
                                    " eliminado exitosamente",
                                    3500
                                );
                            })
                            .catch((error) => {
                                $(".confirm").click();
                                swal(
                                    "Cancelado!",
                                    "Error al eliminar...",
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
        async eliminarAntecedente(id) {
            this.limpiarFormularios();
            let result = await swal({
                    title: "Estás seguro de realizar esta accion",
                    text: "Al confirmar se grabaran los datos exitosamente",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Si!",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: false,
                },
                function(isConfirm) {
                    if (isConfirm) {
                        var url = "eliminar/antecedente";
                        fill = { id: id };
                        cargarLoading();
                        axios
                            .post(url, fill)
                            .then((response) => {
                                $(".confirm").click();
                                datatableCargarAntecedentes();
                                alertToastSuccess(
                                    "Registro : " +
                                    id +
                                    " eliminado exitosamente",
                                    3500
                                );
                            })
                            .catch((error) => {
                                $(".confirm").click();
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
        async eliminarObjetivo(id) {
            this.limpiarFormularios();

            let result = await swal({
                    title: "Estás seguro de realizar esta accion",
                    text: "Al confirmar se grabaran los datos exitosamente",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Si!",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: false,
                },
                function(isConfirm) {
                    if (isConfirm) {
                        var url = "eliminar/objetivo";
                        fill = { id: id };
                        cargarLoading();
                        axios
                            .post(url, fill)
                            .then((response) => {
                                if (response.data.status == 300) {
                                    alertToast(response.data.message, 3500);
                                } else {
                                    $(".confirm").click();
                                    datatableCargarObjetivos();
                                    app.arregloObjetivos =
                                        response.data.objetivos;
                                    destroyPeriodos();
                                    alertToastSuccess(
                                        "Registro : " +
                                        id +
                                        " eliminado exitosamente",
                                        3500
                                    );
                                }
                            })
                            .catch((error) => {
                                $(".confirm").click();
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
        async eliminar(id) {
            let result = await swal({
                    title: "Estás seguro de realizar esta accion",
                    text: "Al confirmar se grabaran los datos exitosamente",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Si!",
                    cancelButtonText: "No",
                    closeOnConfirm: true,
                    closeOnCancel: false,
                },
                function(isConfirm) {
                    if (isConfirm) {
                        var url = "eliminar/compromiso";
                        fill = { id: id };
                        cargarLoading();
                        axios
                            .post(url, fill)
                            .then((response) => {
                                $(".confirm").click();
                                datatableCargar();
                                alertToastSuccess(
                                    "Registro : " +
                                    id +
                                    " eliminado exitosamente",
                                    3500
                                );
                            })
                            .catch((error) => {
                                $(".confirm").click();
                                swal(
                                    "Cancelado!",
                                    "Error al eliminar...",
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

        descargarArchivo: function(id, descripcion, leido) {
            if (leido == "NO") {
                var error = "";
                if (id == "0") {
                    ////this.formCrear.id ////FC
                    error += "\n Debe crear el compromiso para guardar";
                }
                if (error.length > 0) {
                    swal(" ", error, "error");
                    return false;
                }
                var url = "descargarArchivo";
                var data = new FormData();

                data.append("id", id);

                cargarLoading();
                axios
                    .post(url, data)
                    .then((response) => {
                        $(".confirm").click();
                        datatableCargarArchivos();
                        datatableCargarHistorico();

                        alertToastSuccess(
                            "Registro descargado exitosamente",
                            3500
                        );
                    })
                    .catch((error) => {
                        $(".confirm").click();
                        swal("Cancelado!", "Error al grabar...", "error");
                    });
            }
            let direccion = document.querySelector(
                "#direccionDocumentos"
            ).value;
            direccion = direccion + "/COMPROMISOS/" + descripcion;
            downloadURI(direccion);
            //document.querySelector("#botonImprimir").href=direccion;
            //document.querySelector("#botonImprimir").click();
        },
        leerMensaje: function(id) {
            ////console.log("leerMensaje->", id);
            var error = "";
            if (id == "0") {
                ////this.formCrear.id ////FC
                error += "\n Debe crear el compromiso para guardar";
            }
            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }
            var url = "leerMensaje";
            var data = new FormData();
            data.append("id", id);

            cargarLoading();
            axios
                .post(url, data)
                .then((response) => {
                    $(".confirm").click();
                    if (response.data.status == 200) {
                        datatableCargarMensajes();
                        datatableCargarHistorico();

                        alertToastSuccess(
                            "Registro grabado exitosamente",
                            3500
                        );
                    } else alertToastSuccess(response.data.datos, 3500);
                })
                .catch((error) => {
                    $(".confirm").click();
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },
        guardarAvance: function() {
            var error = "";
            if (this.formCrear.id == "0") {
                error += "\n Debe crear el compromiso para guardar";
            }
            if (this.formAvance.descripcion.length < 5)
                error += '\n El avance  debe tener por lo menos 5 caracteres';
            if (this.formAvance.descripcion.length > 500)
                error += '\n El avance  debe tener hasta 500 caracteres';

            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }
            var url = "guardarAvance";
            var data = new FormData();

            data.append("id", this.formCrear.id);
            data.append("idAvance", this.formAvance.idAvance);
            data.append("descripcion", this.formAvance.descripcion);
            app.cargando = true;
            axios
                .post(url, data)
                .then((response) => {
                    $(".confirm").click();
                    app.cargando = false;
                    if (response.data.status == 200) {
                        this.id = response.data.datos;
                        datatableCargarAvances();
                        datatableCargarHistorico();
                        this.limpiarFormularios();
                        alertToastSuccess(
                            "Registro grabado exitosamente",
                            3500
                        );
                    } else alertToast(response.data.datos, 3500);
                })
                .catch((error) => {
                    app.cargando = false;
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },
        guardarAntecedente: function() {
            var error = "";
            if (this.formCrear.id == "0") {
                error += "\n Debe crear el compromiso para guardar";
            }
            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }
            var url = "guardarAntecedente";
            var data = new FormData();

            data.append("id", this.formCrear.id);
            data.append("idAntecedente", this.formAntecedente.idAntecedente);
            data.append("antecedente", this.formAntecedente.antecedente);
            data.append(
                "fecha_antecedente",
                this.formAntecedente.fecha_antecedente
            );

            cargarLoading();
            axios
                .post(url, data)
                .then((response) => {
                    this.id = response.data.datos;
                    $(".confirm").click();
                    datatableCargarAntecedentes();
                    datatableCargarHistorico();
                    this.limpiarFormularios();

                    alertToastSuccess("Registro grabado exitosamente", 3500);
                })
                .catch((error) => {
                    $(".confirm").click();
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },

        guardarUbicacion: function() {
            var list = $("input[name='ubicacion_']:checked")
                .map(function() {
                    return this.value;
                })
                .get();
            var error = "";
            if (this.formCrear.id == "0") {
                error += "\n Debe crear el compromiso para guardar";
            }
            if (list.length == 0)
                error += "\nNo agrego ninguna ubicación para guardar";

            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }
            var url = "guardarUbicacion";
            var data = new FormData();

            data.append("id", this.formCrear.id);
            data.append("ubicacion", list);

            cargarLoading();
            axios
                .post(url, data)
                .then((response) => {
                    this.formCrear.id = response.data.datos;
                    $(".confirm").click();
                    datatableCargarHistorico();
                    datatableCargarUbicaciones();

                    this.limpiarFormularios();
                    alertToastSuccess("Registro grabado exitosamente", 3500);
                })
                .catch((error) => {
                    $(".confirm").click();

                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },
        guardarObjetivo: function() {
            var error = "";
            if (this.formCrear.id == "0") {
                error += "\n Debe crear el compromiso para guardar";
            }
            if (
                this.formObjetivo.temporalidad_id == "" ||
                this.formObjetivo.temporalidad_id == null
            )
                error += "\n Debe ingresar la temporalidad";
            if (
                this.formObjetivo.fecha_inicio_objetivo == "" ||
                this.formObjetivo.fecha_inicio_objetivo == null
            )
                error += "\n Debe ingresar la fecha de inicio del objetivo ";
            if (
                this.formObjetivo.fecha_fin_objetivo == "" ||
                this.formObjetivo.fecha_fin_objetivo == null
            )
                error += "\n Debe ingresar la fecha de fin del objetivo ";

            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }
            var url = "guardarObjetivos";
            var data = new FormData();

            data.append("id", this.formCrear.id);
            data.append("idObjetivo", this.formObjetivo.idObjetivo);
            data.append("temporalidad_id", this.formObjetivo.temporalidad_id);
            data.append("objetivo", this.formObjetivo.objetivo);
            data.append("descripcion_meta", this.formObjetivo.descripcion_meta);
            data.append(
                "fecha_inicio_objetivo",
                this.formObjetivo.fecha_inicio_objetivo
            );
            data.append(
                "fecha_fin_objetivo",
                this.formObjetivo.fecha_fin_objetivo
            );
            // data.append('tipo_objetivo_id', this.formObjetivo.tipo_objetivo_id);
            data.append("tipo_objetivo_id", 1);
            data.append("meta", this.formObjetivo.meta);

            cargarLoading();
            axios
                .post(url, data)
                .then((response) => {
                    $(".confirm").click();

                    if (response.data.status == 200) {
                        datatableCargarObjetivos();
                        this.arregloObjetivos = response.data.objetivos;
                        this.limpiarObjetivos();
                        destroyPeriodos();
                        alertToastSuccess(
                            "Registro grabado exitosamente",
                            3500
                        );
                    } else alertToast(response.data.datos, 3500);
                })
                .catch((error) => {
                    $(".confirm").click();
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },

        guardarArchivo: function() {
            var error = "";
            if (this.formCrear.id == "0") {
                error += "\n Debe crear el compromiso para guardar";
            }
            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }
            var data = new FormData();
            var errores = "";

            data.append("id", this.formCrear.id);

            $("#customFile").each(function(a, array) {
                if (array.files.length > 0) {
                    $.each(array.files, function(k, file) {
                        data.append("archivo[" + k + "]", file);
                    });
                } else errores += "\n No se encuentra ningun archivo agregado";
            });
            if (errores == "") {
                app.cargando = true;
                url = "grabarArchivos";
                axios
                    .post(url, data)
                    .then((response) => {
                        app.cargando = false;
                        if (response.data.status == 200) {
                            datatableCargarArchivos();
                            datatableCargarHistorico();

                            this.formCrear.id = response.data.datos;
                            this.limpiarFormularios();
                            alertToastSuccess(
                                "Registro grabado exitosamente",
                                3500
                            );
                        } else alertToast(response.data.datos, 3500);
                    })
                    .catch((error) => {
                        app.cargando = false;

                        console.log("error al grabar los archivos");
                    });
            } else swal("Cancelado!", errores, "error");
        },
        guardarMensaje: function() {
            var error = "";
            if (this.formCrear.id == "0") {
                error += "\n Debe crear el compromiso para guardar";
            }
            if (this.formMensaje.descripcion.length < 5)
                error += '\n El mensaje debe tener por lo menos 5 caracteres';
            if (this.formMensaje.descripcion.length > 500)
                error += '\n El mensaje debe tener hasta 500 caracteres';
            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }


            var data = new FormData();

            data.append("compromiso_id", this.formCrear.id);
            data.append("descripcion", this.formMensaje.descripcion);
            data.append("id", this.formMensaje.id);
            data.append("chk_corresponsable", $('#chk_corresponsable').is(":checked"));
            app.cargando = true;

            url = "grabarMensaje";
            axios
                .post(url, data)
                .then((response) => {
                    app.cargando = false;
                    if (response.data.status == 200) {
                        datatableCargarMensajes();
                        datatableCargarHistorico();

                        this.formCrear.id = response.data.datos;
                        this.limpiarFormularios();
                        alertToastSuccess(
                            "Registro grabado exitosamente",
                            3500
                        );
                    } else alertToast(response.data.datos, 3500);
                })
                .catch((error) => {
                    app.cargando = false;

                    console.log("error al grabar los archivos");
                });
        },
        crearCodigo: function() {
            var error = "";
            if (this.formCrear.id == "0") {
                error += "\n Debe crear el compromiso";
            }
            var monitor_id = this.formCrear.monitor_id;
            if (monitor_id == "" || monitor_id == null) {
                error += "\nDebe  asignar un monitor al compromiso";
            }
            if (
                this.formCrear.institucion_id == "" ||
                this.formCrear.institucion_id == null
            ) {
                error += "\n Debe asignar a un responsable el compromiso";
            }
            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }
            var data = new FormData();

            data.append("id", this.formCrear.id);
            data.append("institucion_id", this.formCrear.institucion_id);

            url = "crearCodigo";
            axios
                .post(url, data)
                .then((response) => {
                    if (response.data.status == 200) {
                        datatableCargar();
                        datatableCargarHistorico();
                        this.formCrear.codigo = response.data.datos;
                        this.visibleNotificar = false;
                        document.querySelector("#link_inicial").click();
                    } else {
                        alertToast(response.data.datos, 3500);
                        return false;
                    }
                })
                .catch((error) => {
                    console.log("error al grabar los archivos");
                });
        },
        async confirmar() {
            var error = this.verificarCambiosResponsables();
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
                        closeOnCancel: false,
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            var url = "guardarCompromiso";
                            var data = new FormData();

                            app.cargando = true;
                            data.append("id", app.formCrear.id);
                            data.append(
                                "fecha_reporte",
                                app.formCrear.fecha_reporte
                            );
                            data.append(
                                "fecha_inicio",
                                app.formCrear.fecha_inicio_compromiso
                            );
                            data.append(
                                "fecha_fin",
                                app.formCrear.fecha_fin_compromiso
                            );
                            data.append(
                                "nombre_compromiso",
                                app.formCrear.nombre_compromiso
                            );
                            data.append(
                                "detalle_compromiso",
                                app.formCrear.detalle_compromiso != null ?
                                app.formCrear.detalle_compromiso :
                                " "
                            );
                            data.append(
                                "avance_compromiso",
                                app.formCrear.avance_compromiso != null ?
                                app.formCrear.avance_compromiso :
                                " "
                            );
                            data.append("avance_id", app.formCrear.avance_id);
                            data.append(
                                "notas_compromiso",
                                app.formCrear.notas_compromiso != null ?
                                app.formCrear.notas_compromiso :
                                " "
                            );
                            data.append(
                                "cumplimiento",
                                app.formCrear.cumplimiento != null ?
                                app.formCrear.cumplimiento :
                                0
                            );
                            data.append(
                                "avance",
                                app.formCrear.avance != null ?
                                app.formCrear.avance :
                                0
                            );
                            data.append(
                                "tipo_compromiso_id",
                                app.formCrear.tipo_compromiso_id
                            );
                            data.append("origen_id", app.formCrear.origen_id);
                            data.append(
                                "monitor_id",
                                app.formCrear.monitor_id == null ?
                                0 :
                                app.formCrear.monitor_id
                            );
                            data.append(
                                "estado_porcentaje_id",
                                app.formCrear.estado_porcentaje_id
                            );
                            data.append("estado_id", app.formCrear.estado_id);
                            data.append(
                                "institucion_id",
                                app.formCrear.institucion_id
                            );
                            data.append(
                                "instituciones_corresponsables",
                                app.formCrear.instituciones_corresponsables
                            );

                            data.append(
                                "modifica_corresponsable",
                                app.modifica_corresponsable
                            );
                            data.append(
                                "modifica_responsable",
                                app.modifica_responsable
                            );
                            data.append("asignaciones", app.asignaciones);
                            app.cargando = true;

                            axios
                                .post(url, data)
                                .then((response) => {
                                    app.cargando = false;

                                    app.dataCargar = response.data.conteo;
                                    app.registrados =
                                        app.dataCargar.registrados;
                                    app.optimo = app.dataCargar.optimo;
                                    app.bueno = app.dataCargar.bueno;
                                    app.leve = app.dataCargar.leve;
                                    app.moderado = app.dataCargar.moderado;
                                    app.grave = app.dataCargar.grave;
                                    app.planificacion =
                                        app.dataCargar.planificacion;
                                    app.cumplido = app.dataCargar.cumplido;
                                    app.cerrado = app.dataCargar.cerrado;
                                    app.standby = app.dataCargar.standby;
                                    app.ejecucion = app.dataCargar.ejecucion;
                                    app.formCrear.cerrado =
                                        response.data.cerrado.trim();

                                    app.crear = false;
                                    if (app.formCrear.id == "0")
                                        app.visibleNotificar = true;
                                    app.formCrear.id = response.data.datos;
                                    datatableCargarHistorico();
                                    datatableCargar();
                                    alertToastSuccess(
                                        "Registro grabado exitosamente",
                                        3500
                                    );
                                })
                                .catch((error) => {
                                    app.cargando = false;

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
            }
        },
        verificarCambiosResponsables: function() {
            var errores = "";
            if (
                this.formCrear.tipo_compromiso_id == "" ||
                this.formCrear.tipo_compromiso_id == null
            ) {
                errores += "\nDebe llenar el tipo de compromiso";
            }

            if (
                this.formCrear.estado_porcentaje_id == "" ||
                this.formCrear.estado_porcentaje_id == null
            ) {
                errores += "\nDebe llenar el estado de cumplimiento";
            }
            if (
                this.formCrear.estado_id == "" ||
                this.formCrear.estado_id == null
            ) {
                errores += "\nDebe llenar el estado de avances ";
            }
            var _institucion_id = this.formCrear.institucion_id;
            if (_institucion_id == "" || _institucion_id == null) {
                errores += "\nDebe llenar el responsable";
            }

            var _fecha_inicio = this.formCrear.fecha_inicio_compromiso;
            if (_fecha_inicio == "" || _fecha_inicio == null) {
                errores += "\nDebe llenar la fecha de inicio";
            }
            var fecha_reporte = this.formCrear.fecha_reporte;
            if (fecha_reporte == "" || fecha_reporte == null) {
                errores += "\nDebe llenar la fecha del reporte";
            }
            var _origen = this.formCrear.origen_id;
            if (_origen == "" || _origen == null) {
                errores += "\nDebe llenar el origen ";
            }
            var _nombre_compromiso = this.formCrear.nombre_compromiso;
            if (
                _nombre_compromiso == "" ||
                _nombre_compromiso == null ||
                _nombre_compromiso.length < 6
            ) {
                errores +=
                    "\nDebe llenar el nombre del compromiso mayor a 5 caracteres para guardar";
            }
            if (
                this.formCrear.detalle_compromiso == "" ||
                this.formCrear.detalle_compromiso == null
            ) {
                errores += "\nDebe llenar el detalle del compromiso";
            }
            var __instituciones_corresponsables =
                this.formCrear.instituciones_corresponsables;
            if (errores == "") {
                if (this.objEditar.id != 0) {
                    var resp_id = this.objEditar.responsables;
                    var corresp_id = this.objEditar.corresponsables;
                    this.modifica_responsable = false;
                    this.modifica_corresponsable = false;
                    if (resp_id.length > 0) {
                        if (_institucion_id != resp_id[0].institucion_id)
                            this.modifica_responsable = true;
                    } else {
                        this.modifica_responsable = true;
                    }
                    if (__instituciones_corresponsables == "")
                        this.modifica_corresponsable = true;
                    else {
                        var arregloCorresponsable = [];
                        var encontradoCorresponsables = true;
                        $.each(corresp_id, function(key, value) {
                            arregloCorresponsable.push(
                                value.institucion_corresponsable_id
                            );
                        });
                        if (!Array.isArray(__instituciones_corresponsables) &&
                            corresp_id.length == 1
                        ) {
                            if (
                                arregloCorresponsable.indexOf(
                                    __instituciones_corresponsables
                                ) == -1
                            )
                                this.modifica_corresponsable = false;
                        } else {
                            if (arregloCorresponsable.length == 1) {
                                if (arregloCorresponsable[0] == null)
                                    arregloCorresponsable[0] = 0;
                            }

                            encontradoCorresponsables = arrayEquals(
                                arregloCorresponsable,
                                __instituciones_corresponsables
                            );
                        }
                        if (encontradoCorresponsables == false)
                            this.modifica_corresponsable = true;
                    }
                } else {
                    this.modifica_responsable = true;
                    this.modifica_corresponsable = true;
                }
            }
            return errores;
        },
        limpiarForm: function() {
            this.formCrear.id = "0";
            this.formCrear.fecha_inicio_compromiso = new Date()
                .toISOString()
                .slice(0, 10);
            this.formCrear.fecha_fin_compromiso = null;
            this.formCrear.nombre_compromiso = "";
            this.formCrear.detalle_compromiso = "";
            this.formCrear.avance_compromiso = "";
            this.formCrear.avance_id = "0";
            this.formCrear.notas_compromiso = "";
            this.formCrear.cumplimiento = "0";
            this.formCrear.avance = "0";
            this.formCrear.tipo_compromiso_id = "";
            this.formCrear.origen_id = "";
            this.formCrear.monitor_id = "";
            this.formCrear.estado_porcentaje_id = "1";
            this.formCrear.estado_id = "1";
            this.formCrear.responsable_id = "";
            this.formCrear.delegado_id = "";
            this.formCrear.gabinete_id = "";
            this.formCrear.institucion_id = "";
            this.formCrear.instituciones_corresponsables = [];
            this.formCrear.codigo = "";
            this.crear = true;
            this.formCrear.cerrado = "false";
            this.formCrear.fecha_reporte = "";

            document.querySelector("input[name='ubicacion_']").checked = false;
            document.querySelector("#link_inicial").click();

            limpiarJQUERY.click();
            resetearDatatable();
            this.linkNav = 0;
            $("[name='ubicacion_']").each(function() {
                //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"
            });
            $("[name='ul_ubicaciones']").addClass("hidden");
            $(".abrirplus").removeClass("minus");
            $(".abrirplus").addClass("plus");
            this.visibleNotificar = false;
        },
        limpiarMensajes: function() {
            this.formMensaje.id = "0";
            this.formMensaje.descripcion = "";
            $("input[name*='chk_corresponsable']").prop("checked", false);
        },
        limpiarObjetivos: function() {
            this.formObjetivo.idObjetivo = "0";
            this.formObjetivo.objetivo = "";
            this.formObjetivo.temporalidad_id = "";
            this.formObjetivo.descripcion_meta = "";
            this.formObjetivo.fecha_inicio_objetivo = "";
            this.formObjetivo.fecha_fin_objetivo = "";
            this.formObjetivo.meta = "0";
            this.formObjetivo.tipo_objetivo_id = 1;
        },
        limpiarFormularios: function() {
            this.deshabilitarPorDesbloqueo = false;
            var f = new Date();
            this.fecha =
                f.getFullYear() + "-" + (f.getMonth() + 1) + "-" + f.getDate();
            this.formAntecedente.idAntecedente = "0";
            this.formAntecedente.antecedente = "";
            this.formAntecedente.fecha_antecedente = "";
            this.limpiarMensajes();

            this.limpiarObjetivos();
            this.disableDatePicker = false;

            this.formAvance.idAvance = "0";
            this.formAvance.descripcion = "";

            this.formCronograma.caracterizacion = "";
            this.formCronograma.cumplimiento_acumulado = 0;
            this.formCronograma.cumplimiento_periodo = 0;
            this.formCronograma.cumplimiento_periodo_porcentaje = 0;
            this.formCronograma.descripcion_meta = "";
            this.formCronograma.fecha_fin_periodo = "";
            this.formCronograma.fecha_inicio_periodo = "";
            this.formCronograma.id = 0;
            this.formCronograma.meta_acumulada = 0;
            this.formCronograma.meta_periodo = 0;
            this.formCronograma.numero = "--";
            this.formCronograma.objetivo_id = 0;
            this.formCronograma.observaciones = "";
            this.formCronograma.pendiente_acumulado = 0;
            this.formCronograma.pendiente_periodo = 0;
            this.formCronograma.periodo = "";
            this.formCronograma.temporalidad = "";
            this.formCronograma.valor_anterior_meta_acumulada = 0;
            this.formCronograma.valor_anterior_cumplimiento_acumulado = 0;

            customFile.value = null;
            customfilelabel.textContent = "Archivo";
        },
        calcular: function() {
            this.formCronograma.meta_periodo =
                this.formCronograma.meta_periodo != "" &&
                this.formCronograma.meta_periodo != null ?
                this.formCronograma.meta_periodo :
                "0";
            this.formCronograma.cumplimiento_periodo =
                this.formCronograma.cumplimiento_periodo != "" &&
                this.formCronograma.cumplimiento_periodo != null ?
                this.formCronograma.cumplimiento_periodo :
                0;
            if (this.formCronograma.numero == "1") {
                this.formCronograma.meta_acumulada =
                    this.formCronograma.meta_periodo;
                this.formCronograma.cumplimiento_acumulado =
                    this.formCronograma.cumplimiento_periodo;
            } else {
                this.formCronograma.meta_acumulada = (
                    parseFloat(
                        this.formCronograma.valor_anterior_meta_acumulada
                    ) + parseFloat(this.formCronograma.meta_periodo)
                ).toFixed(2);
                this.formCronograma.cumplimiento_acumulado = (
                    parseFloat(
                        this.formCronograma
                        .valor_anterior_cumplimiento_acumulado
                    ) + parseFloat(this.formCronograma.cumplimiento_periodo)
                ).toFixed(2);
            }
            this.formCronograma.pendiente_periodo = (
                parseFloat(this.formCronograma.meta_periodo) -
                parseFloat(this.formCronograma.cumplimiento_periodo)
            ).toFixed(2);
            this.formCronograma.pendiente_acumulado = (
                parseFloat(this.formCronograma.meta_acumulada) -
                parseFloat(this.formCronograma.cumplimiento_acumulado)
            ).toFixed(2);
        },

        getProvincias: function() {
            var url = "provincias/";
            var fill = { busqueda: null };

            axios
                .get(url, fill)
                .then((response) => {
                    this.arregloProvincias = response.data.message;
                })
                .catch((error) => {
                    console.log("error al cargar pronvincias");
                    // swal("Cancelado!", "Error al grabar...", "error");
                });
        },
        //PESTAÑA PARA BUSQUEDAS AVANZADAS
        getBusquedas: function() {
            var urlKeeps = "busquedaAvanzadaCompromisos";
            let gabinete_id_busqueda =
                $("#gabinete_id_busqueda").val() == "" ||
                $("#gabinete_id_busqueda").val() == null ?
                0 :
                $("#gabinete_id_busqueda").val();
            let institucion_id_busqueda =
                $("#institucion_id_busqueda").val() == "" ||
                $("#institucion_id_busqueda").val() == null ?
                0 :
                $("#institucion_id_busqueda").val();
            let monitor_busqueda =
                $("#monitor_busqueda").val() == "" ||
                $("#monitor_busqueda").val() == null ?
                0 :
                $("#monitor_busqueda").val();

            let fill = {
                gabinete_id_busqueda: gabinete_id_busqueda,
                institucion_id_busqueda: institucion_id_busqueda,
                monitor_busqueda: monitor_busqueda,
            };
            axios.post(urlKeeps, fill).then((response) => {
                this.mensajes_busqueda = response.data.mensajes_busqueda;
                this.archivos_busqueda = response.data.archivos_busqueda;
                this.avances_busqueda = response.data.avances_busqueda;
                this.objetivos_busqueda = response.data.objetivos_busqueda;
            });
        },
        async editaPeriodo(id, $edicion) {
            if ($edicion == false || $edicion == "false") {
                swal(
                    "Cancelado!",
                    "No ha registrado el periodo anterior",
                    "error"
                );
                return false;
            }

            this.formCronograma.id = id;
            var data = new FormData();
            data.append("id", id);
            var urlKeeps = "editaPeriodo";
            await axios.post(urlKeeps, data).then((response) => {
                this.objEditarCronograma = response.data.datos;
                this.formCronograma.caracterizacion =
                    this.objEditarCronograma.caracterizacion;
                this.formCronograma.cumplimiento_acumulado =
                    this.objEditarCronograma.cumplimiento_acumulado != 0 ?
                    this.objEditarCronograma.cumplimiento_acumulado :
                    this.objEditarCronograma
                    .valor_anterior_cumplimiento_acumulado;
                this.formCronograma.cumplimiento_periodo =
                    this.objEditarCronograma.cumplimiento_periodo;
                this.formCronograma.cumplimiento_periodo_porcentaje =
                    this.objEditarCronograma.cumplimiento_periodo_porcentaje;
                this.formCronograma.descripcion_meta =
                    this.objEditarCronograma.descripcion_meta;
                this.formCronograma.fecha_fin_periodo =
                    this.objEditarCronograma.fecha_fin_periodo;
                this.formCronograma.fecha_inicio_periodo =
                    this.objEditarCronograma.fecha_inicio_periodo;
                this.formCronograma.id = this.objEditarCronograma.id;
                this.formCronograma.meta_acumulada =
                    this.objEditarCronograma.meta_acumulada != 0 ?
                    this.objEditarCronograma.meta_acumulada :
                    this.objEditarCronograma
                    .valor_anterior_meta_acumulada;
                this.formCronograma.meta_periodo =
                    this.objEditarCronograma.meta_periodo;
                this.formCronograma.numero = this.objEditarCronograma.numero;
                this.formCronograma.objetivo_id =
                    this.objEditarCronograma.objetivo_id;
                this.formCronograma.observaciones =
                    this.objEditarCronograma.observaciones;
                this.formCronograma.pendiente_acumulado =
                    this.objEditarCronograma.pendiente_acumulado;
                this.formCronograma.pendiente_periodo =
                    this.objEditarCronograma.pendiente_periodo;
                this.formCronograma.periodo = this.objEditarCronograma.periodo;
                this.formCronograma.temporalidad =
                    this.objEditarCronograma.temporalidad;
                this.formCronograma.valor_anterior_meta_acumulada =
                    this.objEditarCronograma.valor_anterior_meta_acumulada;
                this.formCronograma.valor_anterior_cumplimiento_acumulado =
                    this.objEditarCronograma.valor_anterior_cumplimiento_acumulado;
            });
        },
        async guardarPeriodo() {
            this.cargando = true;

            var urlKeeps = "guardarPeriodo";
            await axios
                .post(urlKeeps, this.formCronograma)
                .then((response) => {
                    this.cargando = false;

                    if (response.data.status == 200) {
                        this.limpiarFormularios();
                        alertToastSuccess("Grabado Exitosamente", 3500);
                        app.formCronograma.objetivo_id = response.data.datos;
                        datatableCargarPeriodos();
                        this.formCrear.cumplimiento =
                            response.data.cumplimiento;
                    } else {
                        swal("Cancelado!", "Error al grabar...", "error");
                    }
                })
                .catch((error) => {
                    this.cargando = false;
                    console.log("error al cargar provincias");
                    swal("Cancelado!", "Error al grabar...", "error");
                });
        },
        //
        limpiarBusqueda: function() {
            this.gabinete_id_busqueda = 0;
            this.institucion_id_busqueda = 0;
            this.arrayInstitucionBusqueda = [];
            this.arrayGabineteBusqueda = [];
            this.institucion_anterior = null;
            $("#gabinete_id_busqueda").val(null).change();
            $("#institucion_id_busqueda").val(null).change();
        },
        //AL SELECCIONAR EL GABINETE
        async institucionBusqueda() {
            if (
                this.gabinete_id_busqueda != 0 &&
                this.institucion_anterior == null
            )
                this.arrayInstitucionBusqueda = [];
            this.cargando = true;
            var urlKeeps = "filtroInstitucionBusqueda";
            var fill = {
                gabinete_id_busqueda: this.gabinete_id_busqueda,
            };
            iniciar_modal_espera();
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    this.arrayInstitucionBusqueda =
                        response.data.datos != null ? response.data.datos : [];
                    if (this.institucion_anterior != null) {
                        $("#institucion_id_busqueda")
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
        limpiarGabineteBusqueda: function() {
            this.arrayGabineteBusqueda = [];
            $("#gabinete_id_busqueda").val(null).change();
        },


        async gabineteBusqueda(limpiar = null) {
            this.institucion_anterior = limpiar;
            if (this.institucion_id_busqueda == 0)
                this.limpiarGabineteBusqueda();
            this.cargando = true;
            var urlKeeps = "filtroGabineteBusqueda";
            var fill = {
                institucion_id_busqueda: this.institucion_id_busqueda,
            };
            iniciar_modal_espera();
            await axios
                .post(urlKeeps, fill)
                .then((response) => {
                    if (
                        this.institucion_id_busqueda == 0 ||
                        this.institucion_id_busqueda == "" ||
                        this.institucion_id_busqueda == null
                    )
                        this.arrayGabineteBusqueda =
                        response.data.datos != null ?
                        response.data.datos : [];
                    else {
                        $("#gabinete_id_busqueda")
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

        async verBusqueda(id, tipo) {
            ////FC
            datatableCargarBusquedas(id, tipo);
            //   this.getKeeps();
            ////$("#cerrar_modal_filtro").click();
            /*var urlKeeps = 'verBusqueda'; //setea la ruta
            var fill = {
                'id': id //busca el id y lo almacena en la var 'id'
            }
            app.cargando = true; //mientras busca la data, el indicador se activa "cargando"
            await axios.post(urlKeeps, fill)
                .then(response => {
                    this.limpiarFormularios();
                    app.cargando = false; //desaparece indicador de cargando
                    datatableCargarMensajes();
                    this.formMensaje = response.data.datos; //llena el formulario con los datos
                }).catch(error => {
                    app.cargando = false; //desaparece indicador de cargando
                    swal("Cancelado!", "Error al grabar...", "error");
                }); */
        },
        cerrarRechazo: function() {
            this.visibleRechazar = false;
            document.getElementById("idObj").value = 0;
            document.getElementById("text_rechazo").value = "";
        },
        habilitarRechazarObjetivo: function(id) {
            this.visibleRechazar = true;
            document.getElementById("idObj").value = id;
        },
        async consultaNombreCodigoCompromisos() {
            this.cargando = true;

            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/compromisos/consultaNombreCodigoCompromisos";
            let fill = this.llenarDatosEnviar(true);
            await axios.post(urlKeeps, fill).then((response) => {
                this.cargando = false;

                this.arragloNombreCodigoCompromisos = [];
                this.arragloCodigosCompromisos = [];
                if (response.data.status == 200) {
                    this.arragloNombreCodigoCompromisos = response.data.nombresCodigos;
                    this.arragloCodigosCompromisos = response.data.codigos;
                }
            });
        },
    },
});