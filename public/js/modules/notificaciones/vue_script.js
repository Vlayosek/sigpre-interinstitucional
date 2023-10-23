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
        this.consultarNotificacionesTeletrabajo();
    },
    methods: {
        async consultarNotificacionesTeletrabajo() {
            var urlKeeps =
                document.querySelector("#inicializacion").value +
                "/notificaciones/consultarNotificacionesTeletrabajo";
            var fill = {};
            this.cargando = true;
            await axios.post(urlKeeps, fill).then((response) => {
                this.cargando = false;
                this.actividades_pendientes_registrar =
                    response.data.actividades_pendientes_registrar;
                this.actividades_pendientes_aprobar =
                    response.data.actividades_pendientes_aprobar;
                this.total_notificaciones =
                    parseInt(this.actividades_pendientes_registrar) +
                    parseInt(this.actividades_pendientes_aprobar);
            });
        },
   
    },
});
