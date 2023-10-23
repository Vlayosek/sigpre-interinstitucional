var app_calendario = new Vue({
    el: "#menu", //pagina_calendar
    data: {
        calendario: false,
        calendario_finalizacion: false,
    },

    created: function() {

    },
    methods: {
        /*Exportar a excel calendario de reportes*/
        async exportarExcelCalendarioReportes(tipo_reporte = null) {
            let fecha = $('#renderRange' + tipo_reporte).text();
            var urlKeeps = "exportarExcelCalendarioReportes";
            var fill = {
                mes: fecha,
                tipo: tipo_reporte,
            };
            console.log(fill);
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
    },
})