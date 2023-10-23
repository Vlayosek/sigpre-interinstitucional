let arregloDatosMensajes = [
    {
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
        render: function (data, type, row) {
            var html = "--";
            if (row.fecha_revisa != null) html = row.fecha_revisa;
            return html;
        },
    },
    {
        data: "usuario_leido",
        width: "12%",
        searchable: true,
        render: function (data, type, row) {
            var html = "--";
            if (row.usuario_leido != null)
                html = row.usuario_leido;
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
let arregloDatosAvances = [
    {
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
        render: function (data, type, row) {
            var html = "--";
            if (row.fecha_revisa != null) html = row.fecha_revisa;
            return html;
        },
    },
    {
        data: "usuario_leido",
        width: "12%",
        searchable: true,
        render: function (data, type, row) {
            var html = "--";
            if (row.usuario_leido != null)
                html = row.usuario_leido;
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
        render: function (data, type, row) {
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

let arregloDatosArchivos = [
    {
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
        render: function (data, type, row) {
            var html = "--";
            if (row.fecha_revisa != null) html = row.fecha_revisa;
            return html;
        },
    },
    {
        data: "usuario_leido",
        width: "12%",
        searchable: true,
        render: function (data, type, row) {
            var html = "--";
            if (row.usuario_leido != null)
                html = row.usuario_leido;
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
let arregloDatosCompromisos =
    [
        { data: 'reg_' },
        { data: 'nombre_' },
        { data: 'institucion_' },
        { data: 'gabinete_' },
        { data: 'fecha_inicio_' },
        { data: 'fecha_fin_' },
        { data: 'estado_porcentaje_' },
        { data: 'estado_' },
        { data: '' },

    ];



let CabeceraArchivos =
    '<th>Fecha</th>' +
    '<th>Nombre</th>' +
    '<th>Emisor</th>' +
    '<th>Instituci&oacute;n Emisor</th>' +
    '<th>Fecha leido</th>' +
    '<th>Receptor</th>' +
    '<th>Instituci&oacute;n Receptor</th>' +
    '<th width="20%"></th>';
let CabeceraMensajes =
    ' <th>Fecha de Envio</th>' +
    '<th>Descripci&oacute;n</th>' +
    '<th>Emisor</th>' +
    '<th>Instituci&oacute;n Emisor</th>' +
    '<th>Fecha leido</th>' +
    '<th>Receptor</th>' +
    '<th>Instituci&oacute;n Receptor</th>' +
    '<th> </th>';
let CabeceraAvances =
    '<th>N&uacute;mero</th>' +
    '<th>Fecha</th>' +
    '<th>Avance</th>' +
    '<th>Emisor</th>' +
    '<th>Instituci&oacute;n Emisor</th>' +
    '<th>Fecha leido</th>' +
    '<th>Receptor</th>' +
    '<th>Instituci&oacute;n Receptor</th>' +
    '<th>Motivo</th>' +
    '<th></th>';
