var appNotificacion = new Vue({
    el: "#notificacionMain",
    data() {
        return {
            actividades_pendientes_registrar: 0,
            actividades_pendientes_aprobar: 0,
            total_notificaciones: 0,

            cantidad_avance: 0,
            cantidad_archivo: 0,
            cantidad_mensaje: 0,
            cantidad_objetivo: 0,
            total_notificaciones_compromisos: 0,
            estado: "",
            tipo: "",
        };
    },
    created: function () {
        this.consultarNotificacionesCompromisos();
    },
    methods: {

        async consultarNotificacionesCompromisos() {
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/notificaciones/consultarNotificacionesCompromisos";
            var fill = {};
            this.cargando = true;
            await axios.post(urlKeeps, fill).then((response) => {
                this.cargando = false;
                this.cantidad_avance = response.data.cantidad_avance;
                this.cantidad_archivo = response.data.cantidad_archivo;
                this.cantidad_mensaje = response.data.cantidad_mensaje;
                this.cantidad_objetivo = response.data.cantidad_objetivo;
                this.total_notificaciones_compromisos =
                    parseInt(this.cantidad_avance) +
                    parseInt(this.cantidad_archivo) +
                    parseInt(this.cantidad_mensaje) +
                    parseInt(this.cantidad_objetivo);


            });
        },

        async consultarDTNotificaciones(tipo) {
            datatableCargarNotCompromisos(tipo);
        },

        async cambiarEstadoLeido() {
            var tipo = this.tipo;
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/notificaciones/cambiarEstadoLeido";
            var fill = {
                tipo: tipo,
            };
            this.cargando = true;
            await axios.post(urlKeeps, fill).then((response) => {
                this.cargando = false;
                this.tipo = "";
                this.consultarNotificacionesCompromisos();
            });
        },
    },
});
