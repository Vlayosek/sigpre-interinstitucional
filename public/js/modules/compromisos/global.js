let arregloDatosExportaciones = [{
        data: "id",
        width: "5%",
        searchable: true,
    },
    {
        data: "created_at",
        width: "5%",
        searchable: true,
    },
    {
        data: "inicio",
        width: "12%",
        searchable: true,
    },
    {
        data: "fin",
        width: "12%",
        searchable: true,
    },

    {
        data: "",
        width: "12%",
        searchable: true,
    },
];
let arregloDatosMensajes = [{
        data: "created_at",
        width: "5%",
        searchable: true,
    },
    {
        data: "descripcion",
        width: "12%",
        searchable: true,
    },
    {
        data: "nombres",
        width: "12%",
        searchable: true,
    },
    {
        data: "institucion",
        width: "12%",
        searchable: true,
    },

    {
        data: "fecha_revisa",
        width: "12%",
        searchable: true,
        render: function(data, type, row) {
            var html = "--";
            if (row.fecha_revisa != null) html = row.fecha_revisa;
            return html;
        },
    },
    {
        data: "usuario_leido",
        width: "12%",
        searchable: true,
        render: function(data, type, row) {
            var html = "--";
            if (row.usuario_leido != null) html = row.usuario_leido;
            return html;
        },
    },
    {
        data: "institucion_leida",
        width: "12%",
        searchable: true,
    },
    {
        data: "",
        width: "12%",
        searchable: true,
    },
];
let arregloDatosAvances = [{
        data: "numero",
        width: "5%",
        searchable: true,
    },
    {
        data: "created_at",
        width: "5%",
        searchable: true,
    },

    {
        data: "descripcion",
        width: "5%",
        searchable: true,
    },
    {
        data: "nombres",
        width: "5%",
        searchable: true,
    },
    {
        data: "institucion",
        width: "5%",
        searchable: true,
    },

    {
        data: "fecha_revisa",
        width: "12%",
        searchable: true,
        render: function(data, type, row) {
            var html = "--";
            if (row.fecha_revisa != null) html = row.fecha_revisa;
            return html;
        },
    },
    {
        data: "usuario_leido",
        width: "12%",
        searchable: true,
        render: function(data, type, row) {
            var html = "--";
            if (row.usuario_leido != null) html = row.usuario_leido;
            return html;
        },
    },
    {
        data: "institucion_leida",
        width: "5%",
        searchable: true,
    },
    {
        data: "motivo",
        width: "5%",
        searchable: true,
        render: function(data, type, row) {
            html = row.motivo != null ? row.motivo : "--";
            return html;
        },
    },
    {
        data: "",
        width: "10%",
        searchable: true,
    },
];
let arregloDatosObjetivos = [{
        data: "numero",
        width: "5%",
        searchable: true,
    },
    {
        data: "fecha_inicio",
        width: "5%",
        searchable: true,
    },
    {
        data: "fecha_fin",
        width: "5%",
        searchable: true,
    },
    {
        data: "meta",
        width: "5%",
        searchable: true,
    },
    {
        data: "temporalidad",
        width: "7%",
        searchable: true,
    },

    {
        data: "objetivo",
        width: "10%",
        searchable: true,
    },
    {
        data: "descripcion",
        width: "10%",
        searchable: true,
    },
    {
        data: "",
        width: "20%",
        searchable: true,
    },
];
let arregloDatosArchivos = [{
        data: "created_at",
        width: "5%",
        searchable: true,
    },

    {
        data: "nombre",
        width: "12%",
        searchable: true,
    },
    {
        data: "nombres",
        width: "12%",
        searchable: true,
    },
    {
        data: "institucion",
        width: "12%",
        searchable: true,
    },

    {
        data: "fecha_revisa",
        width: "12%",
        searchable: true,
        render: function(data, type, row) {
            var html = "--";
            if (row.fecha_revisa != null) html = row.fecha_revisa;
            return html;
        },
    },
    {
        data: "usuario_leido",
        width: "12%",
        searchable: true,
        render: function(data, type, row) {
            var html = "--";
            if (row.usuario_leido != null) html = row.usuario_leido;
            return html;
        },
    },
    {
        data: "institucion_leida",
        width: "12%",
        searchable: true,
    },
    {
        data: "",
        width: "15%",
        searchable: true,
    },
];
let arregloDatosCompromisosBasico = [{
        title: "Reg",
        data: "reg_",
        name: "compromisos.codigo",
        "render": function(data, type, row) { //FCago2023 si viene de calendario
            var html = '--';
            if (window.location.pathname == '/compromisos/gestion') {
                html = '<a href="#" onclick="app.editar(\'' + row.id + '\')"';
                html += ' data-toggle="modal" data-target="#modal-default"';
                html += ' data-backdrop="static" data-keyboard="false">' + row.reg_ + '</a>';
            } else
                html = row.reg_;
            return html;
        }
    },
    {
        title: "Nombre del Compromiso",
        data: "nombre_",
        name: "compromisos.nombre_compromiso",
    }, {
        title: "Institución",
        data: "institucion_",
        name: "institucion.descripcion",
    },

    {
        title: "Fecha Inicio",
        data: "fecha_inicio_",
        name: "compromisos.fecha_inicio",
    }, {
        title: "Fecha Fin",
        data: "fecha_fin_",
        name: "compromisos.fecha_fin",
    }, {
        title: "Fecha Reporte",
        data: "fecha_reporte",
        name: "compromisos.fecha_reporte",
    }, { title: "Provincia", data: "provincias", searchable: false }, { title: "Cantón", data: "cantones", searchable: false }, { title: "Fecha Avance Aprobado", data: "fecha_revisa", searchable: false }, {
        title: "Ultimo Avance Aprobado",
        data: "ultimo_avance_aprobado",
        searchable: false,
    }, {
        title: "Estado / Gestión",
        data: "estado_porcentaje_",
        name: "estado_porcentaje.descripcion",
    }, {
        title: "Estado / Compromiso",
        data: "estado_",
        name: "estado.descripcion",
    },
];
let arregloDatosCompromisosFinalizacion = [{
        data: "reg_",
        // name: "compromisos.codigo",
    },
    {
        data: "nombre_",
        // name: "compromisos.nombre_compromiso",
    },
    {
        data: "institucion_",
        // name: "institucion.descripcion",
    },
    {
        data: "fecha_inicio_",
        // name: "compromisos.fecha_inicio",
    },
    {
        data: "provincias",
        searchable: false,
    },
    {
        data: "cantones",
        searchable: false,
    },

    {
        data: "fecha_revisa",
        searchable: false,
        render: function(data, type, row) {
            return row.fecha_revisa != null ? row.fecha_revisa : "--";
        },
    },

    {
        data: "ultimo_avance_aprobado",
        searchable: false,
        render: function(data, type, row) {
            return row.ultimo_avance_aprobado != null ?
                row.ultimo_avance_aprobado :
                "--";
        },
    },
    {
        data: "estado_porcentaje_",
        // name: "estado_porcentaje.descripcion",
    },
    {
        data: "estado_",
        // name: "estado.descripcion",
    },
];
let arregloDatosCompromisos = [
    { title: "Acciones", data: "" },
    { title: "Reg", data: "reg_", name: "compromisos.codigo" },
    {
        title: "Nombre del Compromiso",
        data: "nombre_",
        name: "compromisos.nombre_compromiso",
    },
    {
        title: "Institución",
        data: "institucion_",
        name: "institucion.descripcion",
    },
    { title: "Gabinete", data: "gabinete_", name: "gabinete.descripcion" },
    {
        title: "Fecha Inicio",
        data: "fecha_inicio_",
        name: "compromisos.fecha_inicio",
    },
    { title: "Fecha Fin", data: "fecha_fin_", name: "compromisos.fecha_fin" },
    { title: "Provincia", data: "provincias", searchable: false },
    { title: "Cantón", data: "cantones", searchable: false },
    { title: "Parroquia", data: "parroquias", searchable: false },
    { title: "Fecha Avance Aprobado", data: "fecha_revisa", searchable: false },
    {
        title: "Estado / Gestión",
        data: "estado_porcentaje_",
        name: "estado_porcentaje.descripcion",
    },
    {
        title: "Estado / Compromiso",
        data: "estado_",
        name: "estado.descripcion",
    },
];
/*Arreglo reportes visualizacion*/
let arregloReportesVisualizacion = [{
        title: "Reg",
        data: "reg_",
        name: "compromisos.codigo"
    },
    {
        title: "Nombre del Compromiso",
        data: "nombre_",
        name: "compromisos.nombre_compromiso",
    },
    {
        title: "Gabinete",
        data: "gabinete_",
        name: "gabinete.descripcion",
    },
    {
        title: "Responsable",
        data: "institucion_",
        name: "institucion.descripcion",
    },
    {
        title: "Corresponsable",
        data: "corresponsble_",
        name: "institucion.descripcion",
    },
    { title: "Provincia", data: "provincias", searchable: false },
    { title: "Cantón", data: "cantones", searchable: false },
    {
        title: "Fecha Inicio",
        data: "fecha_inicio_",
        name: "compromisos.fecha_inicio",
    },
    {
        title: "Fecha Fin",
        data: "fecha_fin_",
        name: "compromisos.fecha_fin",
    },
    {
        title: "Estado de Gestión",
        data: "estado_porcentaje_",
        name: "estado_porcentaje.descripcion",
    },
    {
        title: "Estado de Compromiso",
        data: "estado_",
        name: "estado.descripcion",
    },
    {
        title: "% de Avance",
        data: "porcentaje_avance",
        searchable: false,
    },
    {
        title: "Ultimo Avance",
        data: "ultimo_avance_aprobado",
        searchable: false,
    },
    { title: "Fecha de Último Avance", data: "fecha_revisa", searchable: false },
    { title: "Observción", data: "observaciona", searchable: false },
    {
        title: "Fecha Reporte",
        data: "fecha_reporte",
        name: "compromisos.fecha_reporte",
    },
];

let CabeceraArchivos =
    "<th>Fecha</th>" +
    "<th>Nombre</th>" +
    "<th>Emisor</th>" +
    "<th>Instituci&oacute;n Emisor</th>" +
    "<th>Fecha leido</th>" +
    "<th>Receptor</th>" +
    "<th>Instituci&oacute;n Receptor</th>" +
    '<th width="20%"></th>';
let CabeceraMensajes =
    " <th>Fecha de Envio</th>" +
    "<th>Descripci&oacute;n</th>" +
    "<th>Emisor</th>" +
    "<th>Instituci&oacute;n Emisor</th>" +
    "<th>Fecha leido</th>" +
    "<th>Receptor</th>" +
    "<th>Instituci&oacute;n Receptor</th>" +
    "<th> </th>";
let CabeceraAvances =
    "<th>N&uacute;mero</th>" +
    "<th>Fecha</th>" +
    "<th>Avance</th>" +
    "<th>Emisor</th>" +
    "<th>Instituci&oacute;n Emisor</th>" +
    "<th>Fecha leido</th>" +
    "<th>Receptor</th>" +
    "<th>Instituci&oacute;n Receptor</th>" +
    "<th>Motivo</th>" +
    "<th></th>";


/* CHANGE DE FILTROS */
let inicializador = false;
let arregloSelectoresInicializado = [];
$(".selectores_exportar_monitor").on('change', function() {
    let id = this.id;
    let valor = $("#" + id + "").val();
    if (valor.indexOf("") != -1 && valor.length == 1) return false;
    if (isKeyExists(arregloSelectoresInicializado, id)) {
        let anterior = arregloSelectoresInicializado[id];
        let actual = valor;
        if (anterior.indexOf("") == -1 && actual.indexOf("") != -1) {
            let arregloData = [""];

            arregloSelectoresInicializado[id] = arregloData;
            $("#" + id + "").val(arregloData).change();
            return false;
        }
    }

    arregloSelectoresInicializado[id] = valor;
    if (valor.indexOf("") != -1 && valor.length > 1) {
        const filteredLibraries = valor.filter((item) => item !== '');
        $("#" + id + "").val(filteredLibraries).change();
    }

});
$(".exportar_monitor").on('change', function() {
    if (app.abrirFiltro) app.consultaNombreCodigoCompromisos();
});

function resetearSelectores() {
    $(".selectores_exportar_monitor").val("").change();
}

function isKeyExists(obj, key) {
    if (obj[key] == undefined) {
        return false;
    } else {
        return true;
    }
}