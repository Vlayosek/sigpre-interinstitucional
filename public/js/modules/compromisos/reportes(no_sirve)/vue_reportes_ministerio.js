var app = new Vue({
    el: '#main',
    data() {
        return {
            srcImagenCargada_: '',
            institucion_filtro: '',
            gabinete_filtro: '',
            formRespuesta: false,
        }
    },
    created: function() {
        //        this.getKeeps();
    },
    methods: {
        async exportarExcelMinisterio() {

            var institucion_filtro = $("#filtro_institucion").val() == "" || $("#filtro_institucion").val() == null ? "--" : $("#filtro_institucion").val();
            var nombre_ = document.getElementById('filtro_institucion').options[document.getElementById('filtro_institucion').selectedIndex].text;

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
                if (response.data.status == 200)
                    document.querySelector("#hrefMinisterioGenerado").click();
            }).catch(error => {
                this.cargando = false;
                alertToast("Error, recargue la página", 3500);
            });
        },

        //EXPORTAR REPORTE COMPROMISOS PRESIDENCIALES GABINETE
        async exportarExcelGabinete() {

            var gabinete_filtro = $("#filtro_gabinete").val() == "" || $("#filtro_gabinete").val() == null ? "--" : $("#filtro_gabinete").val();
            var nombre_gabinete = document.getElementById('filtro_gabinete').options[document.getElementById('filtro_gabinete').selectedIndex].text;

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
                if (response.data.status == 200)
                    document.querySelector("#hrefGabineteGenerado").click();
            }).catch(error => {
                this.cargando = false;
                alertToast("Error, recargue la página", 3500);
            });
        },
    }
})