var app = new Vue({
    el: '#main',
    data: {

        asignaciones: false,
        btnTemporal: false,
        btnPendientes: false,
        rolMinistro: 0,
        dataCargar: [],
        arregloObjetivos: [],
        form: [],
        currentTab: 0,
        planificacion: 0,
        cumplido: 0,
        agendado: 0,
        descartado: 0,
        registrados: 0,
        asignaciones_: 0,
        visibleNotificar: false,
        disableDatePicker: false,
        formCronograma: {
            'caracterizacion': ''
            , 'cumplimiento_acumulado': 0
            , 'cumplimiento_periodo': 0
            , 'cumplimiento_periodo_porcentaje': 0
            , 'descripcion_meta': ''
            , 'fecha_fin_periodo': ''
            , 'fecha_inicio_periodo': ''
            , 'id': 0
            , 'meta_acumulada': 0
            , 'meta_periodo': 0
            , 'numero': '--'
            , 'objetivo_id': 0
            , 'observaciones': ''
            , 'pendiente_acumulado': 0
            , 'pendiente_periodo': 0
            , 'periodo': ''
            , 'temporalidad': ''
            , 'valor_anterior_meta_acumulada': 0
            , 'valor_anterior_cumplimiento_acumulado': 0

        },
        formCrear: {
            'responsable_id': '',
            'delegado_id': '',
            'gabinete_id': '',
            'institucion_id': '',
            'instituciones_corresponsables': [],
            'codigo': '',
            'cerrado': 'false',
            'id': '0',
            'codigo': '',
            'estado_id': '',
            'estado_porcentaje_id': '',
            'fecha_inicio': '',
            'fecha_fin': '',
            'origen_id': '',
            'monitor_id': '',
            'justificacion': '',
            'lugar': '',
            'antecedente': '',
            'avance_id': '',
            'pendientes': '',
            'cerrado': '',
            'tipo_id': '',
            'tema': '',
            'objetivo': '',
            'descripcion': '',
            'duracion': '',
            'contacto_delegado': '',
            'descripcion_participantes_archivo':'',
            'nombre_participantes_archivo':'',
            'impacto':false,
            'coyuntura':false,
            'observacion':''

        },
        formAntecedente: {
            'idAntecedente': '0',
            'antecedente': '',
            'fecha_antecedente': '',
        },
        formMensaje: {
            'mensaje': '',
        },
        formAvance: {
            'idAvance': '0',
            'descripcion': '',
        },
        formNegar: {
            'id': '0',
            'motivo': '',
        },
        formObjetivo: {

            'idObjetivo': '0',
            'objetivo': '',
            'temporalidad_id': '',
            'descripcion_meta': '',
            'fecha_inicio_objetivo': '',
            'fecha_fin_objetivo': '',
            'meta': '0',
            'tipo_objetivo_id': '',
        },
        formObraComplementaria: {
            'descripcion': '',
            'porcentaje_avance': '5',
            'responsable': '',
            'agenda_territorial_id': ''

        },
        formObraPrincipal: {
            'visita_presidente': '',
            'fecha_ultima_visita': '',
            'situacion_actual': '',
            'ejecutor_proyecto': '',
            'constructor_obra': '',
            'numero_beneficiarios_directos': '',
            'numero_beneficiarios_indirectos': '',
            'fecha_inicio': '',
            'fecha_fin': '',
            'porcentaje_avance': '',
            'costo_proyecto': '',
            'fuente_financiamiento': '',
            'agenda_territorial_id': ''
        },
        formOrdenDia: {
            'tema': '',
            'expositor': '',
            'cargo': '',
            'entidad': '',
            'tiempo': '',
            'informacion_complementaria': '',
            'participantes_archivo': '',
            'agenda_territorial_id': ''
        },
        formMinistro: {
            'id': '',
            'nombres': '',
        },
        objEditar: { 'id': 0 },
        objEditarCronograma: { 'id': 0 },

        tableHistorico: '',

        arregloProvincias: [],
        arregloProvinciasCheckeadas: [],
        arregloCantones: [],
        arregloCantonesCheckeadas: [],
        arregloParroquias: [],

        checkUbicacion: false,
        modifica_responsable: false,
        modifica_corresponsable: false,
        customFile: '',
        linkNav: 0,
        myDate: new Date().toISOString().slice(0, 10),
        fecha: null,
        crear: true,
        tabla: null,
        tipoActual: null,
        filtro: false,
        datos: [],
        datos_instituciones: [],
        pagination: {
            'total': 0,
            'current_page': 0,
            'per_page': 0,
            'last_page': 0,
            'from': 0,
            'to': 0,
        },
        offset: 4,
        cargando: false,
        inauguracion_complementaria: false,
        inauguracion_principal: false,
        arregloParroquia_: [],
        arregloCanton_: []
    },

    created: function () {
        this.getKeeps();
        this.getProvincias();
        this.linkNav = 0;
        this.formCrear.codigo = '';
        this.formCrear.id = '0';
        this.formCrear.cerrado = 'false';

        this.rolMinistro = document.querySelector("#rolMinistro").getAttribute('content');


    },
    mounted: function () {

    },
    computed: {
        isActived: function () {
            return this.pagination.current_page;
        },
        pagesNumber: function () {
            if (!this.pagination.to) {
                return [];
            }

            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }

            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }

            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
    },
    methods: {

        async eliminarOrdenDia(id) { //guardar un registro 
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
                function (isConfirm) {
                    if (isConfirm) {
                        var urlKeeps = 'eliminarOrdenDia'; //setea la ruta del controlador
                        app.cargando = true; //mientras busca la data, el indicador se activa "cargando"
                        var fill = {
                            'id': id
                        }
                        axios.post(urlKeeps, fill) //elimino registro
                            .then(response => {
                                datatableCargarOrdenDia();
                                alertToastSuccess("Eliminado exitosamente", 3500);
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
        
        async guardarArchivoParticipantes() { //guardar un registro 
    
               await swal({
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
                    function (isConfirm) {
                        if (isConfirm) {
                            var urlKeeps = 'guardarArchivoParticipantes'; //setea la ruta del controlador
                            app.cargando = true; //mientras busca la data, el indicador se activa "cargando"
                            var data = new FormData();
                        
                            data.append('agenda_territorial_id',app.formCrear.id);
                            
                            $('#participantes_archivo').each(function (a, array) {
                                if (array.files.length > 0) {
                                    $.each(array.files, function (k, file) {
                                        data.append('archivos[' + k + ']', file);
                                    })
                                } else
                                    data.append('archivos', null);
                            });
                            axios.post(urlKeeps, data) //elimino registro
                                .then(response => {
                                
                                    $("#participantes_archivo").val(null);
                                    $("#participantes_archivo_label").text("Seleccione el archivo");
                                    app.limpiarFormularios();
                                    app.cargando = false; //desaparece indicador de cargando
                                  //  datatableCargarOrdenDia();
                                    alertToastSuccess("Grabado exitosamente", 3500);
                                    app.formCrear.descripcion_participantes_archivo=response.data.descripcion_participantes_archivo;
                                    app.formCrear.nombre_participantes_archivo=response.data.nombre_participantes_archivo;
                                })
                                .catch(error => {
                                    app.cargando = false; //desaparece indicador de cargando
                                    swal("Cancelado!", "Error al grabar...", "error");
                                });
                        } else {
                            swal("Cancelado!", "No se registraron cambios...", "error");
                            return false;
                        }
                    });
        },
        async guardarOrdenDia() { //guardar un registro 
            var errores="";
            if (this.formOrdenDia.tema == ''
                || this.formOrdenDia.tema == null) {
                errores += "\nDebe llenar el campo tema ";
            }
            if (this.formOrdenDia.expositor == ''
                || this.formOrdenDia.expositor == null) {
                errores += "\nDebe llenar el campo expositor ";
            }
            if (this.formOrdenDia.cargo == ''
                || this.formOrdenDia.cargo == null) {
                errores += "\nDebe llenar el campo cargo ";
            }
            if (this.formOrdenDia.entidad == ''
                || this.formOrdenDia.entidad == null) {
                errores += "\nDebe llenar el campo entidad ";
            }
            if (this.formOrdenDia.tiempo == ''
                || this.formOrdenDia.tiempo == null) {
                errores += "\nDebe llenar el campo tiempo ";
            }
           /* if (this.formOrdenDia.informacion_complementaria == ''
                || this.formOrdenDia.informacion_complementaria == null) {
                errores += "\nDebe llenar el campo informacion complementaria ";
            }*/
            
            if (errores!="") {
                swal(" ", errores, "error");
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
                },
                    function (isConfirm) {
                        if (isConfirm) {
                            var urlKeeps = 'guardarOrdenDia'; //setea la ruta del controlador
                            app.cargando = true; //mientras busca la data, el indicador se activa "cargando"
                            var data = new FormData();
                        
                            app.formOrdenDia.agenda_territorial_id = app.formCrear.id;
                            for (const property in app.formOrdenDia) {
                                let atributo = `${property}`;
                                let valor = `${app.formOrdenDia[property]}`;
                                data.append(atributo, valor);
                            }
                            axios.post(urlKeeps, data) //elimino registro
                                .then(response => {
                                

                                    app.limpiarFormularios();
                                    app.cargando = false; //desaparece indicador de cargando
                                    datatableCargarOrdenDia();
                                    alertToastSuccess("Grabado exitosamente", 3500);
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
            }
        },
        async guardarObraPrincipal() { //eliminar un registro 
            var fecha_inicio = this.formObraPrincipal.fecha_inicio != null && this.formObraPrincipal.fecha_inicio != "" ? this.formObraPrincipal.fecha_inicio : "null";
            var fecha_fin = this.formObraPrincipal.fecha_fin != null && this.formObraPrincipal.fecha_fin != "" ? this.formObraPrincipal.fecha_fin : "null";


            var fecha1 = moment(fecha_inicio);
            var fecha2 = moment(fecha_fin);
            var fecha3 = fecha2.diff(fecha1, 'days');
            var errores = "";
            if (fecha_inicio == "null" || fecha_fin == "null") {
                errores += "\nLas Debe colocar un rango de fecha";
            }
            if (fecha3 < 0) {
                errores += "\nLas fechas fin no puede ser menor a la fecha de inicio";
            }
            if (this.formObraPrincipal.situacion_actual == ''
                || this.formObraPrincipal.situacion_actual == null) {
                errores += "\nDebe llenar el campo Situacion actual ";
            }
            if (this.formObraPrincipal.ejecutor_proyecto == ''
                || this.formObraPrincipal.ejecutor_proyecto == null) {
                errores += "\nDebe llenar el campo ejecutor de obra ";
            }
            if (this.formObraPrincipal.constructor_obra == ''
                || this.formObraPrincipal.constructor_obra == null) {
                errores += "\nDebe llenar el campo constructor de obra ";
            }
            if (errores.length > 0) {
                swal(" ", errores, "error");
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
                },
                    function (isConfirm) {
                        if (isConfirm) {
                            var urlKeeps = 'guardarObraPrincipal'; //setea la ruta del controlador
                            app.cargando = true; //mientras busca la data, el indicador se activa "cargando"
                            app.formObraPrincipal.agenda_territorial_id = app.formCrear.id;
                            app.formObraPrincipal.costo_proyecto=$("#costo_proyecto").val();
                            axios.post(urlKeeps, app.formObraPrincipal) //elimino registro
                                .then(response => {
                                    // app.limpiarFormularios();
                                //    app.limpiarObras();
                                    datatableCargar();
                                    app.cargando = false; //desaparece indicador de cargando
                                    alertToastSuccess("Grabado exitosamente", 3500);
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
            }
        },
        async eliminarObraComplementaria(id) { //guardar un registro 
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
                function (isConfirm) {
                    if (isConfirm) {
                        var urlKeeps = 'eliminarObraComplementaria'; //setea la ruta del controlador
                        app.cargando = true; //mientras busca la data, el indicador se activa "cargando"
                        var fill = {
                            'id': id
                        }
                        axios.post(urlKeeps, fill) //elimino registro
                            .then(response => {
                                datatableCargarObraComplementaria();
                                alertToastSuccess("Eliminado exitosamente", 3500);
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
        async guardarObraComplementaria() { //eliminar un registro 
            var errores="";
            if (this.formObraComplementaria.descripcion == ''
                || this.formObraComplementaria.descripcion == null) {
                errores += "\nDebe llenar el campo obra complementaria";
            }
            if (this.formObraComplementaria.responsable == ''
                || this.formObraComplementaria.responsable == null) {
                errores += "\nDebe llenar el campo responsable de ejecucion";
            }
            
            if (errores!="") {
                swal(" ", errores, "error");
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
                },
                    function (isConfirm) {
                        if (isConfirm) {
                            var urlKeeps = 'guardarObraComplementaria'; //setea la ruta del controlador
                            app.cargando = true; //mientras busca la data, el indicador se activa "cargando"
                            app.formObraComplementaria.agenda_territorial_id = app.formCrear.id;

                            axios.post(urlKeeps, app.formObraComplementaria) //elimino registro
                                .then(response => {
                                   // app.limpiarObras();
                                    datatableCargar();
                                    app.cargando = false; //desaparece indicador de cargando
                                    datatableCargarObraComplementaria();
                                    alertToastSuccess("Grabado exitosamente", 3500);
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
            }
        },

        quitarFiltro: function () {
            this.filtro = false;
            $("#institucion_id_exportar").val(null).change();
            $("#provincia_id_exportar").val(null).change();
            $("#estado_id_exportar").val(null).change();
            datatableCargarAgenda();
            $("#cerrar_modal_filtro").click();

        },
        changePage: function (page) {
            this.pagination.current_page = page;
            this.getDatatableAgendaServerSide(page);
        },
        exportarExcel: function () {
            var fecha_inicio = $("#fecha_inicio_exportar").val() == "" || $("#fecha_inicio_exportar").val() == null ? "null" : $("#fecha_inicio_exportar").val();
            var fecha_fin = $("#fecha_fin_exportar").val() == "" || $("#fecha_fin_exportar").val() == null ? "null" : $("#fecha_fin_exportar").val();
            var institucion_id_exportar = $("#institucion_id_exportar").val() == "" || $("#institucion_id_exportar").val() == null ? "null" : $("#institucion_id_exportar").val();
            var gabinete_id_exportar = $("#gabinete_id_exportar").val() == "" || $("#gabinete_id_exportar").val() == null ? "null" : $("#gabinete_id_exportar").val();
            if (fecha_inicio == "null" || fecha_fin == "null") {
                alertToast("Debe colocar un rango de fecha", 3500);
                return false;
            }
            var fecha1 = moment(fecha_inicio);
            var fecha2 = moment(fecha_fin);
            var fecha3 = fecha2.diff(fecha1, 'days');
            if (fecha3 > 365) {
                alertToast("Los rangos de fechas no pueden extender un año", 3500);
                return false;
            }
            // this.getDatatableAgendaServerSide();

            var url = '/reportes/agenda_territorial/exportarExcelGET/' + fecha_inicio + '/' + fecha_fin + '/' + app.tipoActual + '/' + app.tabla + '/' + app.asignaciones + '/' + app.btnTemporal + '/' + app.btnPendientes + '/' + this.filtro + '/' + institucion_id_exportar + '/' + gabinete_id_exportar;
            swal({
                title: "Loading...",
                text: "Please wait",
                icon: "/images/loading.gif",
                button: false,
                closeOnClickOutside: false,
                closeOnEsc: false,
                showConfirmButton: false

            });
            axios
                .get(
                    url, {
                    responseType: 'blob' //Change the responseType to blob
                }
                )
                .then(resp => {
                    if (resp.status == 200) {
                        $("#cerrar_modal_filtro").click();

                        $(".confirm").click();
                        let blob = new Blob([resp.data], { type: "application/vnd.ms-excel" });
                        let link = URL.createObjectURL(blob);
                        let a = document.createElement("a");
                        a.download = "compromisos_" + fecha_inicio + "_" + fecha_fin + "_";
                        a.href = link;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    }
                    else {
                        $(".confirm").click();
                        alertToast("error al generar excel", 2500);
                    }
                })
                .catch(error => {
                    $(".confirm").click();
                    alertToast("error al generar excel", 2500);

                })
                .finally(() => {
                    $(".confirm").click();
                });
        },

        filtrarDatos: function () {
            this.filtro = true;
            datatableCargarAgenda();
            $("#cerrar_modal_filtro").click();
        },
        getDatatableAgendaServerSide: function (page) {
            var fecha_inicio = $("#fecha_inicio_exportar").val() == "" || $("#fecha_inicio_exportar").val() == null ? "null" : $("#fecha_inicio_exportar").val();
            var fecha_fin = $("#fecha_fin_exportar").val() == "" || $("#fecha_fin_exportar").val() == null ? "null" : $("#fecha_fin_exportar").val();
            var institucion_id_exportar = $("#institucion_id_exportar").val() == "" || $("#institucion_id_exportar").val() == null ? "null" : $("#institucion_id_exportar").val();
            var gabinete_id_exportar = $("#gabinete_id_exportar").val() == "" || $("#gabinete_id_exportar").val() == null ? "null" : $("#gabinete_id_exportar").val();
            if (fecha_inicio == "null" || fecha_fin == "null") {
                alertToast("Debe colocar un rango de fecha", 3500);
                return false;
            }
            var fecha1 = moment(fecha_inicio);
            var fecha2 = moment(fecha_fin);
            var fecha3 = fecha2.diff(fecha1, 'days');
            if (fecha3 < 0 & this.filtro) {
                alertToast("Las fechas fin no puede ser menor a la fecha de inicio", 3500);
                return false;
            }

           
            var urlKeeps = 'getDatatableAgendaServerSide?page=' + page;
            urlKeeps += '&&estado=' + app.tipoActual + '&&tabla=' + app.tabla + '&&asignaciones=' + app.asignaciones;
            urlKeeps += '&&temporales=' + app.btnTemporal + '&&pendientes=' + app.btnPendientes;
            urlKeeps += '&&filtro=' + this.filtro + '&&fecha_inicio=' + fecha_inicio;
            urlKeeps += '&&fecha_fin=' + fecha_fin + '&&institucion_id_exportar=' + institucion_id_exportar;
            urlKeeps += '&&gabinete_id_exportar=' + gabinete_id_exportar;

            axios.get(urlKeeps).then(response => {
                if (this.filtro)
                    $("#cerrar_modal_filtro").click();
               
                this.datos = response.data.tasks.data,
                    this.pagination = response.data.pagination
            }).catch(error => {
               
                swal("Cancelado!", "Error al cargar los datos...", "error");
            });
        },
        agregarNegarAvances: function (id) {
            this.formNegar.id = id;
            this.formNegar.motivo = '';
        },
        buscarAsignaciones: function () {
            this.asignaciones = this.asignaciones == true ? this.asignaciones = false : this.asignaciones = true;
            this.getKeeps();
            BotonDatatable.click();

        },
        buscarTemporales: function () {
            this.btnTemporal = this.btnTemporal == true ? this.btnTemporal = false : this.btnTemporal = true;
            this.getKeeps();
            BotonDatatable.click();

        },
        buscarPendientes: function () {
            this.btnPendientes = this.btnPendientes == true ? this.btnPendientes = false : this.btnPendientes = true;
            this.getKeeps();
            BotonDatatable.click();
        },


        changeInstitucion: function (e) {
            console.log(e);
        },

        editarAvances: function (
            id,
            avance,
        ) {
            this.limpiarFormularios();
            this.formAvance.idAvance = id;
            this.formAvance.descripcion = avance;
        },

        editarObjetivo: function (
            idO,
            objetivo,
            descripcion,
            temporalidad_id,
            fi,
            ff,
            meta,
            tipo_objetivo_id
        ) {
            this.limpiarFormularios();
            this.formObjetivo.idObjetivo = idO;
            this.formObjetivo.objetivo = objetivo;
            this.formObjetivo.descripcion_meta = descripcion;
            this.formObjetivo.temporalidad_id = temporalidad_id;
            this.formObjetivo.fecha_inicio_objetivo = fi;
            this.formObjetivo.fecha_fin_objetivo = ff;
            this.formObjetivo.meta = meta;
            this.formObjetivo.tipo_objetivo_id = tipo_objetivo_id;
            this.disableDatePicker = true;

        },
        editarAntecedente: function (
            idA,
            antecedente,
            fecha) {
            this.limpiarFormularios();

            this.formAntecedente.idAntecedente = idA;
            this.formAntecedente.fecha_antecedente = fecha;
            this.formAntecedente.antecedente = antecedente;
        },
        
        async editarOrdenDia(id) {
            var url = 'editarOrdenDia';
            var fill = {
                'id': id
            }
            axios.post(url, fill).then(response => {
                if (response.data.status == 200) {
                   
                    this.formOrdenDia= response.data.datos;
                    $(".confirm").click();
                }

            }).catch(error => {
                $(".confirm").click()
                swal("Cancelado!", "Error al aprobar...", "error");
            });
        },
        async editar(id) {
            this.visibleNotificar = false;

            $("[name='ubicacion_']").each(function () { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });
            $("[name='ul_ubicaciones']").addClass("hidden");
            $(".abrirplus").removeClass("minus");
            $(".abrirplus").addClass("plus");
            destroyPeriodos();
            document.querySelector("#link_inicial").click();
            this.linkNav = 0;
            resetear();
            this.formCrear.id = id;
            BotonDatatableAntecedentes.click();
            BotonDatatableHistorico.click();
            BotonDatatableArchivo.click();
            BotonDatatableMensaje.click();
            BotonDatatableUbicaciones.click();
            datatableCargarOrdenDia();
            datatableCargarObraComplementaria();
            this.limpiarFormularios();
            var urlKeeps = 'editar/agenda/' + this.formCrear.id;
            this.cargando = true;
            this.limpiarObras();
            await axios.get(urlKeeps).then(response => {
                this.objEditar = response.data[0];
                this.formCrear = this.objEditar;
                this.formCrear.id = this.formCrear.id;
                this.cargando = false;

                $("#impacto").prop("checked", true);
                $("#coyuntura").prop("checked", true);

                var resp_id = this.objEditar.responsables;
                if (resp_id.length > 0)
                    getCargaDatosInstitucion("institucion", resp_id[0].institucion_id, this.objEditar.monitor_id);

                this.formCrear.estado_porcentaje_id = this.objEditar.estado_porcentaje_id;
                this.formCrear.estado_id = this.objEditar.estado_id;
                this.formCrear.monitor_id = this.objEditar.monitor_id;
                this.crear = false;
                if (this.objEditar.obra_principal != null)
                    this.formObraPrincipal = this.objEditar.obra_principal;

                if (this.linkNav == 0 && (this.formCrear.codigo == '' || this.formCrear.codigo == null) && this.formCrear.id != '0')
                    this.visibleNotificar = true;

                document.querySelector("#link_inicial").click();
                this.arregloObjetivos = this.objEditar.objetivos;

                this.inauguracion_complementaria = false;
                this.inauguracion_principal = false;
                var valida_inaguracion_ = this.formCrear.tipo != null ? this.formCrear.tipo.abv : '--';
                this.valida_inauguracion(valida_inaguracion_);
            }).catch(error => {
                this.cargando = false;
                $(".confirm").click()
                document.querySelector("#cerrar_modal_actividad").click();
                swal("Cancelado!", "Error al cargar los datos...", "error");
            });
        },
        valida_inauguracion: function (valida_inaguracion_) {

            if (valida_inaguracion_ == 'PRI') {
                this.inauguracion_complementaria = false;
                this.inauguracion_principal = true;
            }

            if (valida_inaguracion_ == 'COM') {
                this.inauguracion_complementaria = true;
                this.inauguracion_principal = false;
            }

        },
        async onChangeInstitucion() {
            cargarLoading();
            var url = 'cargarInstituciones';
            var fill = {
                'id': $("#gabinete_id_exportar").val()
            }
            axios.post(url, fill).then(response => {
                if (response.data.status == 200) {
                    $("#institucion_id_exportar").html('');
                    getResponsable('institucion_id_exportar', '', 'TODAS LAS INSTITUCIONES');
                    $.each(response.data.datos, function (key, value) {
                        getResponsable('institucion_id_exportar', key, value);
                    });
                    $("#institucion_id_exportar").val('').change();

                    $(".confirm").click();
                    //   getResponsable();
                }

            }).catch(error => {
                $(".confirm").click()
                swal("Cancelado!", "Error al aprobar...", "error");
            });
        },
        async aprobarObjetivo(id) {
            cargarLoading();
            var url = 'aprobarObjetivo';
            var fill = {
                'id': id
            }
            axios.post(url, fill).then(response => {
                if (response.data.status == 200) {
                    $(".confirm").click();
                    datatableCargarObjetivos();
                    alertToastSuccess("Registro : " + id + " aprobado exitosamente", 3500);
                }

            }).catch(error => {
                $(".confirm").click()
                swal("Cancelado!", "Error al aprobar...", "error");
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
                text: "<textarea id='text_rechazo' class='form-control'></textarea>",
                showCancelButton: true,
                closeOnConfirm: false,
                inputPlaceholder: "Escriba el motivo"
            },
                function (inputValue) {
                    if (inputValue === false) return false;
                    if (inputValue === "") {
                        swal.showInputError("Necesita escribir motivo");
                        return false
                    }
                    app.editar = true;
                    var urlKeeps = 'rechazarObjetivo';
                    var fill = {
                        'id': id,
                        'observacion': document.getElementById('text_rechazo').value,
                    }
                    axios.post(urlKeeps, fill).then(response => {
                        if (response.data.status == 200) {
                            swal("Rechazo del objetivo!", response.data.datos, "success");
                            datatableCargarObjetivos();
                        } else {
                            alertToast("Error, recargue la página", 3500);
                        }

                    }).catch(error => {
                        alertToast("Error, recargue la página", 3500);
                    });

                });
        },
        async negarAvances(id) {
            var url = 'negar/avance';
            cargarLoading();
            axios.post(url, this.formNegar).then(response => {
                $(".confirm").click()
                BotonDatatableAvances.click();
                alertToastSuccess("Registro : " + id + " negado exitosamente", 3500)
            }).catch(error => {
                $(".confirm").click()
                swal("Cancelado!", "Error al negar...", "error");
            });
        },
        async aprobarAvances(id, descripcion) {
            this.formCrear.avance_compromiso = descripcion;
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
                    var url = 'aprobar/avance';
                    fill = { 'id': id };
                    cargarLoading();
                    axios.post(url, fill).then(response => {
                        $(".confirm").click()
                        BotonDatatableHistorico.click();
                        BotonDatatableAvances.click();
                        alertToastSuccess("Registro : " + id + " aprobado exitosamente", 3500)
                    }).catch(error => {
                        $(".confirm").click()
                        swal("Cancelado!", "Error al aprobar...", "error");
                    });

                } else {
                    swal("Cancelado!", "No se registraron cambios...", "error");
                    return false;
                }
            });
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
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    var url = 'eliminar/avance';
                    fill = { 'id': id };
                    cargarLoading();
                    axios.post(url, fill).then(response => {
                        $(".confirm").click()
                        BotonDatatableMensaje.click();
                        alertToastSuccess("Registro : " + id + " eliminado exitosamente", 3500)
                    }).catch(error => {
                        $(".confirm").click()
                        swal("Cancelado!", "Error al eliminar...", "error");
                    });

                } else {
                    swal("Cancelado!", "No se registraron cambios...", "error");
                    return false;
                }
            });
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
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    var url = 'eliminar/mensaje';
                    fill = { 'id': id };
                    cargarLoading();
                    axios.post(url, fill).then(response => {
                        $(".confirm").click()
                        BotonDatatableMensaje.click();
                        alertToastSuccess("Registro : " + id + " eliminado exitosamente", 3500)
                    }).catch(error => {
                        $(".confirm").click()
                        swal("Cancelado!", "Error al eliminar...", "error");
                    });

                } else {
                    swal("Cancelado!", "No se registraron cambios...", "error");
                    return false;
                }
            });
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
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    var url = 'eliminar/archivo';
                    fill = { 'id': id };
                    cargarLoading();
                    axios.post(url, fill).then(response => {
                        $(".confirm").click()
                        BotonDatatableArchivo.click();
                        alertToastSuccess("Registro : " + id + " eliminado exitosamente", 3500)
                    }).catch(error => {
                        $(".confirm").click()
                        swal("Cancelado!", "Error al eliminar...", "error");
                    });

                } else {
                    swal("Cancelado!", "No se registraron cambios...", "error");
                    return false;
                }
            });
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
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    var url = 'eliminar/antecedente';
                    fill = { 'id': id };
                    cargarLoading();
                    axios.post(url, fill).then(response => {
                        $(".confirm").click()
                        BotonDatatableAntecedentes.click();
                        alertToastSuccess("Registro : " + id + " eliminado exitosamente", 3500)
                    }).catch(error => {
                        $(".confirm").click()
                        swal("Cancelado!", "Error al grabar...", "error");
                    });

                } else {
                    swal("Cancelado!", "No se registraron cambios...", "error");
                    return false;
                }
            });
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
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    var url = 'eliminar/objetivo';
                    fill = { 'id': id };
                    cargarLoading();
                    axios.post(url, fill).then(response => {
                        $(".confirm").click()
                        BotonDatatableObjetivo.click();
                        app.arregloObjetivos = response.data.objetivos;
                        destroyPeriodos();
                        alertToastSuccess("Registro : " + id + " eliminado exitosamente", 3500)
                    }).catch(error => {
                        $(".confirm").click()
                        swal("Cancelado!", "Error al grabar...", "error");
                    });

                } else {
                    swal("Cancelado!", "No se registraron cambios...", "error");
                    return false;
                }
            });
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
                closeOnCancel: false
            }, function (isConfirm) {
                if (isConfirm) {
                    var url = 'eliminar/compromiso';
                    fill = { 'id': id };
                    cargarLoading();
                    axios.post(url, fill).then(response => {
                        $(".confirm").click()
                        BotonDatatable.click();
                        alertToastSuccess("Registro : " + id + " eliminado exitosamente", 3500)
                    }).catch(error => {
                        $(".confirm").click()
                        swal("Cancelado!", "Error al eliminar...", "error");
                    });

                } else {
                    swal("Cancelado!", "No se registraron cambios...", "error");
                    return false;
                }
            });
        },
        
        descargarArchivoParticipantesCargados: function () {

            let direccion = document.querySelector("#direccionDocumentos").value;
            direccion = direccion + '/AGENDA_TERRITORIAL/ORDEN_DIA/PARTICIPANTES/' + this.formCrear.descripcion_participantes_archivo;
            downloadURI(direccion, this.formCrear.nombre_participantes_archivo)
        },
        descargarArchivoParticipantes: function (descripcion, nombre) {

            let direccion = document.querySelector("#direccionDocumentos").value;
            direccion = direccion + '/AGENDA_TERRITORIAL/ORDEN_DIA/PARTICIPANTES/' + descripcion;
            downloadURI(direccion, nombre)
        },
        descargarArchivo: function (id, descripcion, nombre, leido) {
            if (leido == 'NO') {
                var error = "";
                if (this.formCrear.id == "0") {
                    error += '\n Debe crear el compromiso para guardar';
                }
                if (error.length > 0) {
                    swal(" ", error, "error");
                    return false;
                }
                var url = 'descargarArchivo';
                var data = new FormData();

                data.append('id', id);

                cargarLoading();
                axios.post(url, data).then(response => {

                    $(".confirm").click()
                    BotonDatatableArchivo.click();
                    BotonDatatableHistorico.click();

                    alertToastSuccess("Registro descargado exitosamente", 3500)
                }).catch(error => {
                    $(".confirm").click()
                    swal("Cancelado!", "Error al grabar...", "error");
                });
            }
            let direccion = document.querySelector("#direccionDocumentos").value;
            direccion = direccion + '/AGENDA_TERRITORIAL/' + descripcion;
            downloadURI(direccion, nombre)
            //document.querySelector("#botonImprimir").href=direccion;
            //document.querySelector("#botonImprimir").click();

        },
        leerMensaje: function (id) {

            var error = "";
            if (this.formCrear.id == "0") {
                error += '\n Debe crear el compromiso para guardar';
            }
            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }
            var url = 'leerMensaje';
            var data = new FormData();

            data.append('id', id);

            cargarLoading();
            axios.post(url, data).then(response => {

                $(".confirm").click()
                BotonDatatableMensaje.click();
                BotonDatatableHistorico.click();

                alertToastSuccess("Registro grabado exitosamente", 3500)
            }).catch(error => {
                $(".confirm").click()
                swal("Cancelado!", "Error al grabar...", "error");
            });
        },
        guardarAvance: function () {

            var error = "";
            if (this.formCrear.id == "0") {
                error += '\n Debe crear el compromiso para guardar';
            }
            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }
            var url = 'guardarAvance';
            var data = new FormData();

            data.append('id', this.formCrear.id);
            data.append('idAvance', this.formAvance.idAvance);
            data.append('descripcion', this.formAvance.descripcion);
            cargarLoading();
            axios.post(url, data).then(response => {
                this.id = response.data.datos;
                $(".confirm").click()
                BotonDatatableAvances.click();
                BotonDatatableHistorico.click();
                this.limpiarFormularios();

                alertToastSuccess("Registro grabado exitosamente", 3500)
            }).catch(error => {
                $(".confirm").click()
                swal("Cancelado!", "Error al grabar...", "error");
            });
        },
        guardarAntecedente: function () {

            var error = "";
            if (this.formCrear.id == "0") {
                error += '\n Debe crear la agenda para guardar';
            }
            if (this.formAntecedente.fecha_antecedente == "" || this.formAntecedente.fecha_antecedente == null) {
                error += '\n Debe llenar la fecha del antecedente';
            }
            if (this.formAntecedente.antecedente == "" || this.formAntecedente.antecedente == null) {
                error += '\n Debe llenar la descripcion del antecedente';
            }
            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }
            var url = 'guardarAntecedente';
            var data = new FormData();

            data.append('id', this.formCrear.id);
            data.append('idAntecedente', this.formAntecedente.idAntecedente);
            data.append('antecedente', this.formAntecedente.antecedente);
            data.append('fecha_antecedente', this.formAntecedente.fecha_antecedente);

            cargarLoading();
            axios.post(url, data).then(response => {
                this.id = response.data.datos;
                $(".confirm").click()
                BotonDatatableAntecedentes.click();
                BotonDatatableHistorico.click();
                this.limpiarFormularios();

                alertToastSuccess("Registro grabado exitosamente", 3500)
            }).catch(error => {
                $(".confirm").click()
                swal("Cancelado!", "Error al grabar...", "error");
            });
        },

        guardarUbicacion: function () {
            var list = $("input[name='ubicacion_']:checked").map(function () {
                return this.value;
            }).get();
            var error = "";
            if (this.formCrear.id == "0") {
                error += '\n Debe crear el compromiso para guardar';
            }
            if (list.length == 0)
                error += '\nNo agrego ninguna ubicación para guardar';

            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }
            var url = 'guardarUbicacion';
            var data = new FormData();

            data.append('id', this.formCrear.id);
            data.append('ubicacion', list);

            cargarLoading();
            axios.post(url, data).then(response => {
                this.formCrear.id = response.data.datos;
                $(".confirm").click()
                BotonDatatableHistorico.click();
                BotonDatatableUbicaciones.click();
                datatableCargar();
                this.limpiarFormularios();
                alertToastSuccess("Registro grabado exitosamente", 3500)
            }).catch(error => {
                $(".confirm").click()

                swal("Cancelado!", "Error al grabar...", "error");
            });
        },
        guardarObjetivo: function () {
            var error = "";
            if (this.formCrear.id == "0") {
                error += '\n Debe crear el compromiso para guardar';
            }
            if (this.formObjetivo.temporalidad_id == '' || this.formObjetivo.temporalidad_id == null)
                error += '\n Debe ingresar la temporalidad';
            if (this.formObjetivo.fecha_inicio_objetivo == '' || this.formObjetivo.fecha_inicio_objetivo == null)
                error += '\n Debe ingresar la fecha de inicio del objetivo ';
            if (this.formObjetivo.fecha_fin_objetivo == '' || this.formObjetivo.fecha_fin_objetivo == null)
                error += '\n Debe ingresar la fecha de fin del objetivo ';

            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }
            var url = 'guardarObjetivos';
            var data = new FormData();

            data.append('id', this.formCrear.id);
            data.append('idObjetivo', this.formObjetivo.idObjetivo);
            data.append('temporalidad_id', this.formObjetivo.temporalidad_id);
            data.append('objetivo', this.formObjetivo.objetivo);
            data.append('descripcion_meta', this.formObjetivo.descripcion_meta);
            data.append('fecha_inicio_objetivo', this.formObjetivo.fecha_inicio_objetivo);
            data.append('fecha_fin_objetivo', this.formObjetivo.fecha_fin_objetivo);
            data.append('tipo_objetivo_id', this.formObjetivo.tipo_objetivo_id);
            data.append('meta', this.formObjetivo.meta);

            cargarLoading();
            axios.post(url, data).then(response => {
                this.formCrear.id = response.data.datos;
                $(".confirm").click()
                BotonDatatableObjetivo.click();
                BotonDatatableHistorico.click();
                this.arregloObjetivos = response.data.objetivos;
                this.limpiarFormularios();
                destroyPeriodos();

                alertToastSuccess("Registro grabado exitosamente", 3500)
            }).catch(error => {
                $(".confirm").click()
                swal("Cancelado!", "Error al grabar...", "error");
            });
        },

        guardarArchivo: function () {
            var error = "";
            if (this.formCrear.id == "0") {
                error += '\n Debe crear el compromiso para guardar';
            }
            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }
            var data = new FormData();
            var errores = "";

            data.append('id', this.formCrear.id);

            $('#customFile').each(function (a, array) {
                if (array.files.length > 0) {
                    $.each(array.files, function (k, file) {
                        data.append('archivo[' + k + ']', file);
                    })
                } else
                    errores += "\n No se encuentra ningun archivo agregado";
            });
            if (errores == "") {
                url = "grabarArchivos"
                axios.post(url, data).then(response => {
                    BotonDatatableArchivo.click();
                    BotonDatatableHistorico.click();

                    this.formCrear.id = response.data.datos;
                    this.limpiarFormularios();
                    alertToastSuccess("Registro grabado exitosamente", 3500)

                }).catch(error => {
                    console.log("error al grabar los archivos");
                });
            } else
                swal("Cancelado!", errores, "error");

        },
        guardarMensaje: function () {
            var error = "";
            if (this.formCrear.id == "0") {
                error += '\n Debe crear la agenda para guardar';
            }
            if (this.formMensaje.mensaje != null) {
                if (this.formMensaje.mensaje < 1) {
                    error += '\n Debe llenar la descripcion del mensaje';
                }
                if (this.formMensaje.mensaje > 500) {
                    error += '\n El mensaje no puede tener mas de 500 caracteres';
                }
            }
            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }
            var data = new FormData();

            data.append('id', this.formCrear.id);
            data.append('descripcion', this.formMensaje.mensaje);

            url = "grabarMensaje"
            axios.post(url, data).then(response => {
                BotonDatatableMensaje.click();
                BotonDatatableHistorico.click();

                this.formCrear.id = response.data.datos;
                this.limpiarFormularios();
                alertToastSuccess("Registro grabado exitosamente", 3500)

            }).catch(error => {
                console.log("error al grabar los archivos");
            });

        },
        crearCodigo: function () {
            var error = "";
            if (this.formCrear.id == "0") {
                error += '\n Debe crear la agenda';
            }
            if (this.formCrear.institucion_id == "" || this.formCrear.institucion_id == null) {
                error += '\n Debe asignar a un responsable';
            }
            if (error.length > 0) {
                swal(" ", error, "error");
                return false;
            }
            var data = new FormData();

            data.append('id', this.formCrear.id);
            data.append('institucion_id', this.formCrear.institucion_id);

            url = "crearCodigo"
            axios.post(url, data).then(response => {
                BotonDatatable.click();
                BotonDatatableHistorico.click();
                this.formCrear.codigo = response.data;
                this.visibleNotificar = false;
            }).catch(error => {
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
                    closeOnCancel: false
                }, function (isConfirm) {
                    if (isConfirm) {
                        var url = 'guardarAgenda';
                        var data = new FormData();

                        app.formCrear.modifica_corresponsable = app.modifica_corresponsable;
                        app.formCrear.modifica_responsable = false;
                        app.formCrear.asignaciones = app.asignaciones;
                        app.cargando = true;
                        cargarLoading();
                        var impacto=false;
                        if($('#impacto').is(":checked"))
                         impacto=true;

                         var coyuntura=false;
                         if($('#coyuntura').is(":checked"))
                         coyuntura=true;

                        app.formCrear.coyuntura=coyuntura;
                        app.formCrear.impacto=impacto;
                        axios.post(url, app.formCrear).then(response => {
                            app.getKeeps();
                            app.dataCargar = response.data.conteo;
                            app.formCrear.cerrado = response.data.cerrado.trim();
                            app.cargando = false;
                            var valida_inaguracion_ = response.data.dato_completo;
                            valida_inaguracion_ = valida_inaguracion_.tipo != null ? valida_inaguracion_.tipo.abv : '--';
                            app.valida_inauguracion(valida_inaguracion_);

                            $(".confirm").click()
                            app.crear = false;
                            if (app.formCrear.id == '0'){
                                app.visibleNotificar = true;
                                app.limpiarObras();
                            }
                                
                            app.formCrear.id = response.data.datos;
                            BotonDatatable.click();
                            BotonDatatableHistorico.click();
                            datatableCargarOrdenDia();
                            datatableCargarObraComplementaria();
                            alertToastSuccess("Registro grabado exitosamente", 3500);
                            datatableCargar();
                        }).catch(error => {
                            $(".confirm").click()
                            app.cargando = false;

                            swal("Cancelado!", "Error al grabar...", "error");
                        });


                    } else {
                        swal("Cancelado!", "No se registraron cambios...", "error");
                        return false;
                    }
                });
            }



        },
        verificarCambiosResponsables: function () {
            var errores = "";
            if (this.formCrear.tipo_id == ''
                || this.formCrear.tipo_id == null) {
                errores += "\nDebe llenar el tipo ";
            }

            if (this.formCrear.estado_porcentaje_id == ''
                || this.formCrear.estado_porcentaje_id == null) {
                errores += "\nDebe llenar el estado";
            }
            if (this.formCrear.estado_id == ''
                || this.formCrear.estado_id == null) {
                errores += "\nDebe llenar la prioridad ";
            }
            var _institucion_id = this.formCrear.institucion_id;
            if (_institucion_id == ''
                || _institucion_id == null) {
                errores += "\nDebe llenar el responsable";
            }
            var _fecha_inicio = this.formCrear.fecha_inicio;
            if (_fecha_inicio == ''
                || _fecha_inicio == null) {
                errores += "\nDebe llenar la fecha sugerida";
            }

            var tema = this.formCrear.tema;
            if (tema == '' || tema == null || tema.length < 6) {
                errores += "\nDebe llenar el tema y ser mayor a 5 caracteres para guardar";
            }

            if (this.formCrear.descripcion == ''
                || this.formCrear.descripcion == null) {
                errores += "\nDebe llenar la descripcion";
            }
            if (this.formCrear.antecedente == ''
                || this.formCrear.antecedente == null) {
                errores += "\nDebe llenar el antecedente";
            }
          /*  if (this.formCrear.justificacion == ''
                || this.formCrear.justificacion == null) {
                errores += "\nDebe llenar la justificacion";
            }*/
            if (this.formCrear.duracion == ''
                || this.formCrear.duracion == null) {
                errores += "\nDebe llenar la duración";
           }
          /*  if (this.formCrear.objetivo == ''
                || this.formCrear.objetivo == null) {
                errores += "\nDebe llenar el objetivo";
            }*/
            if (this.formCrear.lugar == ''
                || this.formCrear.lugar == null) {
                errores += "\nDebe llenar el lugar";
            }
            if (errores == "") {
                if (this.objEditar.id != 0) {
                    var resp_id = this.objEditar.responsables;
                    this.modifica_responsable = false;
                    if (resp_id.length > 0) {
                        if (_institucion_id != resp_id[0].institucion_id)
                            this.modifica_responsable = true;
                    } else {
                        this.modifica_responsable = true;
                    }


                } else {
                    this.modifica_responsable = true;
                }
            }
         
      
            return errores;

        },
        limpiarForm: function () {
            this.formCrear.id = '0';
            this.formCrear.antecedente='';
            this.formCrear.fecha_inicio = new Date().toISOString().slice(0, 10);
            this.formCrear.tema = '';
            this.formCrear.objetivo = '';
            this.formCrear.descripcion = '';
            this.formCrear.observacion = '';
            this.formCrear.justificacion = '';
            this.formCrear.duracion = '';
            this.formCrear.lugar = '';
            this.formCrear.avance_id = '0';
            this.formCrear.notas_compromiso = '';
            this.formCrear.cumplimiento = '0';
            this.formCrear.avance = '0';
            this.formCrear.tipo_id = '';
            this.formCrear.origen_id = '';
            this.formCrear.monitor_id = '';
            this.formCrear.estado_porcentaje_id = '1';
            this.formCrear.estado_id = '';
            this.formCrear.responsable_id = '';
            this.formCrear.delegado_id = '';
            this.formCrear.gabinete_id = '';
            this.formCrear.institucion_id = '';
            this.formCrear.instituciones_corresponsables = [];
            this.formCrear.codigo = '';
            this.crear = true;
            this.formCrear.cerrado = 'false';
            this.formCrear.contacto_delegado = '';
            document.querySelector("#impacto").checked = false;
            document.querySelector("#coyuntura").checked = false;
            document.querySelector("input[name='ubicacion_']").checked = false;
            document.querySelector("#link_inicial").click();

            limpiarJQUERY.click();
            resetearDatatable();
            this.linkNav = 0;
            $("[name='ubicacion_']").each(function () { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });
            $("[name='ul_ubicaciones']").addClass("hidden");
            $(".abrirplus").removeClass("minus");
            $(".abrirplus").addClass("plus");
            this.visibleNotificar = false;

        },

        limpiarFormularios: function () {
            var f = new Date();
            this.fecha = f.getFullYear() + "-" + (f.getMonth() + 1) + "-" + f.getDate();
            this.formAntecedente.idAntecedente = '0';
            this.formAntecedente.antecedente = '';
            this.formAntecedente.fecha_antecedente = '';
            this.formMensaje.mensaje = '';

            this.formObjetivo.idObjetivo = '0';
            this.formObjetivo.objetivo = '';
            this.formObjetivo.objetivo = '';
            this.formObjetivo.temporalidad_id = '';
            this.formObjetivo.descripcion_meta = '';
            this.formObjetivo.fecha_inicio_objetivo = '';
            this.formObjetivo.fecha_fin_objetivo = '';
            this.formObjetivo.meta = '0';
            this.formObjetivo.tipo_objetivo_id = '';

            this.disableDatePicker = false;

            this.formAvance.idAvance = '0';
            this.formAvance.descripcion = '';

            this.formCronograma.caracterizacion = '';
            this.formCronograma.cumplimiento_acumulado = 0;
            this.formCronograma.cumplimiento_periodo = 0;
            this.formCronograma.cumplimiento_periodo_porcentaje = 0;
            this.formCronograma.descripcion_meta = '';
            this.formCronograma.fecha_fin_periodo = '';
            this.formCronograma.fecha_inicio_periodo = '';
            this.formCronograma.id = 0;
            this.formCronograma.meta_acumulada = 0;
            this.formCronograma.meta_periodo = 0;
            this.formCronograma.numero = '--';
            this.formCronograma.objetivo_id = 0;
            this.formCronograma.observaciones = '';
            this.formCronograma.pendiente_acumulado = 0;
            this.formCronograma.pendiente_periodo = 0;
            this.formCronograma.periodo = '';
            this.formCronograma.temporalidad = '';
            this.formCronograma.valor_anterior_meta_acumulada = 0;
            this.formCronograma.valor_anterior_cumplimiento_acumulado = 0;

            this.limpiarObras();

            customFile.value = null;
            customfilelabel.textContent = "Archivo";
        },
        limpiarObras:function(){
            this.formObraComplementaria.descripcion = '';
            this.formObraComplementaria.porcentaje_avance = '5';
            this.formObraComplementaria.responsable = '';
            this.formObraComplementaria.agenda_territorial_id = '';
            this.formObraPrincipal.visita_presidente = '';
            this.formObraPrincipal.fecha_ultima_visita = '';
            this.formObraPrincipal.situacion_actual = '';
            this.formObraPrincipal.ejecutor_proyecto = '';
            this.formObraPrincipal.constructor_obra = '';
            this.formObraPrincipal.numero_beneficiarios_directos = '';
            this.formObraPrincipal.numero_beneficiarios_indirectos = '';
            this.formObraPrincipal.fecha_inicio = '';
            this.formObraPrincipal.fecha_fin = '';
            this.formObraPrincipal.porcentaje_avance = '';
            this.formObraPrincipal.costo_proyecto = '';
            this.formObraPrincipal.fuente_financiamiento = '';
            this.formObraPrincipal.agenda_territorial_id = '';

            this.formOrdenDia.tema = '';
            this.formOrdenDia.expositor = '';
            this.formOrdenDia.cargo = '';
            this.formOrdenDia.entidad = '';
            this.formOrdenDia.tiempo = '';
            this.formOrdenDia.informacion_complementaria = '';
            this.formOrdenDia.participantes_archivo = '';
            this.formOrdenDia.agenda_territorial_id = '';
        },
        calcular: function () {
            this.formCronograma.meta_periodo = this.formCronograma.meta_periodo != '' && this.formCronograma.meta_periodo != null ? this.formCronograma.meta_periodo : 0;
            this.formCronograma.cumplimiento_periodo = this.formCronograma.cumplimiento_periodo != '' && this.formCronograma.cumplimiento_periodo != null ? this.formCronograma.cumplimiento_periodo : 0;
            if (this.formCronograma.numero == '1') {
                this.formCronograma.meta_acumulada = this.formCronograma.meta_periodo;
                this.formCronograma.cumplimiento_acumulado = this.formCronograma.cumplimiento_periodo;
            } else {
                this.formCronograma.meta_acumulada = parseInt(this.formCronograma.valor_anterior_meta_acumulada) + parseInt(this.formCronograma.meta_periodo);
                this.formCronograma.cumplimiento_acumulado = parseInt(this.formCronograma.valor_anterior_cumplimiento_acumulado) + parseInt(this.formCronograma.cumplimiento_periodo);
            }
            this.formCronograma.pendiente_periodo = parseInt(this.formCronograma.meta_periodo) - parseInt(this.formCronograma.cumplimiento_periodo);
            this.formCronograma.pendiente_acumulado = parseInt(this.formCronograma.meta_acumulada) - parseInt(this.formCronograma.cumplimiento_acumulado);
        },

        buscarResponsable: function () {
            var url = 'buscarResponsableMinistroAgenda';
            var fill = { 'busqueda': null };

            axios.post(url, fill).then(response => {
                this.formMinistro.id = response.data.datos.id;
                this.formMinistro.nombres = response.data.datos.nombres;
                $("#responsable_id").html('');
                getResponsable("responsable_id", this.formMinistro.id, this.formMinistro.nombres);
                getCargaDatosInstitucion("responsable", this.formMinistro.id);

            }).catch(error => {
                console.log("error al cargar");
                // swal("Cancelado!", "Error al grabar...", "error");
            });
        },
        
        async imprimirFicha(id) {
            var urlKeeps = 'imprimirFicha';
            var fill={
                'id':id
            }
            await axios.post(urlKeeps,fill).then(response => {
                if(response.data.status==300){
                    alertToast(response.data.message,3500);
                    
                }else{
                    alertToastSuccess("Documento Generado",3500);
                    var direccion = document.querySelector("#direccionDocumentos").value+'/AGENDA_TERRITORIAL_GENERADAS/'+response.data.nombre;
                    downloadURI(direccion, response.data.nombre);
                }
               
            })
        },
        async getKeeps() {
            var urlKeeps = 'consulta/' + this.asignaciones;
            await axios.get(urlKeeps).then(response => {
                this.dataCargar = response.data;

                this.registrados = this.dataCargar.registrados;
                this.planificacion = this.dataCargar.planificacion;
                this.cumplido = this.dataCargar.cumplido;
                this.descartado = this.dataCargar.descartado;
                this.agendado = this.dataCargar.agendado;
                this.asignaciones_ = this.dataCargar.asignaciones_;
            })
        },
        getProvincias: function () {
            var url = 'provincias/';
            var fill = { 'busqueda': null };

            axios.get(url, fill).then(response => {
                this.arregloProvincias = response.data.message;
            }).catch(error => {
                console.log("error al cargar pronvincias");
                // swal("Cancelado!", "Error al grabar...", "error");
            });
        },
        async editaPeriodo(id, $edicion) {
            if ($edicion == false || $edicion == "false") {
                swal("Cancelado!", "No ha registrado el periodo anterior", "error");
                return false;
            }

            this.formCronograma.id = id;
            var data = new FormData();
            data.append('id', id)
            var urlKeeps = 'editaPeriodo';
            await axios.post(urlKeeps, data).then(response => {
                this.objEditarCronograma = response.data.datos;
                this.formCronograma.caracterizacion = this.objEditarCronograma.caracterizacion;
                this.formCronograma.cumplimiento_acumulado = this.objEditarCronograma.cumplimiento_acumulado != 0 ? this.objEditarCronograma.cumplimiento_acumulado : this.objEditarCronograma.valor_anterior_cumplimiento_acumulado;
                this.formCronograma.cumplimiento_periodo = this.objEditarCronograma.cumplimiento_periodo;
                this.formCronograma.cumplimiento_periodo_porcentaje = this.objEditarCronograma.cumplimiento_periodo_porcentaje;
                this.formCronograma.descripcion_meta = this.objEditarCronograma.descripcion_meta;
                this.formCronograma.fecha_fin_periodo = this.objEditarCronograma.fecha_fin_periodo;
                this.formCronograma.fecha_inicio_periodo = this.objEditarCronograma.fecha_inicio_periodo;
                this.formCronograma.id = this.objEditarCronograma.id;
                this.formCronograma.meta_acumulada = this.objEditarCronograma.meta_acumulada != 0 ? this.objEditarCronograma.meta_acumulada : this.objEditarCronograma.valor_anterior_meta_acumulada;
                this.formCronograma.meta_periodo = this.objEditarCronograma.meta_periodo;
                this.formCronograma.numero = this.objEditarCronograma.numero;
                this.formCronograma.objetivo_id = this.objEditarCronograma.objetivo_id;
                this.formCronograma.observaciones = this.objEditarCronograma.observaciones;
                this.formCronograma.pendiente_acumulado = this.objEditarCronograma.pendiente_acumulado;
                this.formCronograma.pendiente_periodo = this.objEditarCronograma.pendiente_periodo;
                this.formCronograma.periodo = this.objEditarCronograma.periodo;
                this.formCronograma.temporalidad = this.objEditarCronograma.temporalidad;
                this.formCronograma.valor_anterior_meta_acumulada = this.objEditarCronograma.valor_anterior_meta_acumulada;
                this.formCronograma.valor_anterior_cumplimiento_acumulado = this.objEditarCronograma.valor_anterior_cumplimiento_acumulado;

            });
        },
        async guardarPeriodo() {

            var urlKeeps = 'guardarPeriodo';
            await axios.post(urlKeeps, this.formCronograma).then(response => {

                this.limpiarFormularios();
                alertToastSuccess("Grabado Exitosamente", 3500);
                app.formCronograma.objetivo_id = response.data.datos;
                BotonDatatablePeriodos.click();
            });
        }

    }
});


