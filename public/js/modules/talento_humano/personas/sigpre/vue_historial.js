var appHistorial = new Vue({
    el: '#main',
    data() {
        return {
            currentTab_: 1,
            cargando_: false,
            activos: 0,
            inactivos: 0,
            pendientes: 0,
            formFiltro: {
                'identificacion': '',
                'filtro': ''
            },
            filtro: false,
            formHistorial: {
                'area_id': '',
                'edificio_id': '',
                'denominacion_id': '',
                'cargo_id': '',
                'fecha_ingreso': '',
                'fecha_salida': '',
                'tipo_contrato_id': '',
                'motivo_id': '',
                'sueldo': '',
                'persona_id': '',
                'id': '',
                // 'horario_id': '',
            },
            actualizado: false,
            cargandoItem: false,
            volver_guardar: false,
            habilitarGrabar: true,

        }
    },
    created: function () {
    },
    methods: {
        limpiarForm: function () {
            this.formHistorial.area_id = '';
            this.formHistorial.edificio_id = '';
            this.formHistorial.denominacion_id = '';
            this.formHistorial.cargo_id = '';
            this.formHistorial.fecha_ingreso = '';
            this.formHistorial.fecha_salida = '';
            this.formHistorial.tipo_contrato_id = '';
            this.formHistorial.motivo_id = '';
            this.formHistorial.sueldo = '';
            // this.formHistorial.horario_id = '';

            $('#customFile').val(null);
            $('#customfilelabel').text("Archivo");
            $("#hora_inicio").attr("disabled", false);
            $("#hora_fin").attr("disabled", false);
            this.formHistorial.valida_jefe = false;
            this.formHistorial.valida_talento_humano = false;
        },
        limpiarFiltro: function () {
            this.formFiltro.identificacion = '';
            this.formFiltro.filtro = '';
            $(".filtro_select").addClass("hidden");

        },
        changeFiltro: function () {
            var filtroDato = this.formFiltro.filtro
            if (filtroDato == 'sueldo_')
                $("#busqueda").addClass("numero");
            else {
                $("#busqueda").removeClass("numero");
                $(".filtro_select").addClass("hidden");

                if (filtroDato.indexOf('_id') != -1) {
                    $("#" + filtroDato + "_busqueda").removeClass("hidden");
                } else
                    $(".filtro_select_input").removeClass("hidden");

            }

        },
        cambiarEstado: function (id, persona_id) {
            var urlKeeps = 'cambiarEstado';
            var urlKeeps = document.querySelector("#inicializacion").value + '/uath/cambiarEstado';

            var fill = {
                'id': id
            }
            iniciar_modal_espera();
            axios.post(urlKeeps, fill).then(response => {
                parar_modal_espera();

                alertToastSuccess("Grabado exitoso", 3500);
                datatablehistorialPersona(persona_id);
            })
        },
        cambiarPrincipal: function (id, persona_id) {
            var urlKeeps = document.querySelector("#inicializacion").value + '/uath/cambiarPrincipal';

            var fill = {
                'id': id
            }
            iniciar_modal_espera();

            axios.post(urlKeeps, fill).then(response => {
                parar_modal_espera();
                alertToastSuccess("Grabado exitoso", 3500);
                datatablehistorialPersona(persona_id);
            })
        },
        actualizadoHistorial: function () {
            var urlKeeps = 'actualizadoHistorial';
            var urlKeeps = document.querySelector("#inicializacion").value + '/uath/actualizadoHistorial';

            var fill = {
                'id': this.persona_id
            }
            iniciar_modal_espera();

            axios.post(urlKeeps, fill).then(response => {
                this.actualizado = true;
                parar_modal_espera();
                alertToastSuccess("Grabado exitoso", 3500);
            }).catch(error => {
                alertToast("Error al grabar...", 3500);
            });
        },
        consultaPersonaHistorial: function (id) {
            this.persona_id = id;
            var urlKeeps = document.querySelector("#inicializacion").value + '/uath/consultaPersonaHistorial';

            var fill = {
                'id': id
            }
            axios.post(urlKeeps, fill).then(response => {
                this.actualizado = response.data.actualizado;
                $(".span_apelllidos_nombres").text(response.data.persona.apellidos_nombres.toUpperCase());
                $(".span_identificacion").text(response.data.persona.identificacion);
            })
        },
        filtrarPersona: function () {
            if (this.formFiltro.identificacion.length < 3) {
                alertToast("Debe escribir en el filtro al menos 3 caracteres", 3500);
                return false;
            }

            this.filtro = true;
            datatableCargarPersonas();
        },
        quitarFiltro: function () {
            this.limpiarFiltro();
            this.filtro = false;
            datatableCargarPersonas();
        },
        grabarNuevoHistorial: function () {

        },
        limpiarHistorial: function () {
            this.formHistorial.motivo_id = '';
            this.formHistorial.tipo_contrato_id = '';
            this.formHistorial.area_id = '';
            this.formHistorial.edificio_id = '';
            this.formHistorial.cargo_id = '';
            this.formHistorial.denominacion_id = '';
            this.formHistorial.fecha_ingreso = '';
            this.formHistorial.fecha_salida = '';
            this.formHistorial.sueldo = '';
            this.formHistorial.persona_id = '';
            this.formHistorial.id = '';
            // this.formHistorial.horario_id = '';
        },
        async editarHistorial_(id, persona_id, element = null) {
            this.formHistorial.persona_id = persona_id;
            this.formHistorial.id = id;
            if (element != null)
                transaccionToogle(element, true);

            var urlKeeps = 'editarHistorial_';
            var fill = {
                'id': id
            }
            await axios.post(urlKeeps, fill).then(response => {

                this.limpiarHistorial();
                this.formHistorial = response.data.datos;

                $("#cargo_id").val(this.formHistorial.cargo_id).on();
                $("#motivo_id").val(this.formHistorial.motivo_id).on();
                $("#area_id").val(this.formHistorial.area_id).on();
                $("#edificio_id").val(this.formHistorial.edificio_id).on();
                // $("#horario_id").val(this.formHistorial.horario_id).on();
                $("#tipo_contrato_id").val(this.formHistorial.tipo_contrato_id).on();
                $("#denominacion_id").val(this.formHistorial.denominacion_id).on();
                this.formHistorial.fecha_ingreso = this.formHistorial.fecha_ingreso;
                this.formHistorial.fecha_salida = this.formHistorial.fecha_salida;
                this.formHistorial.sueldo = this.formHistorial.denominacion.remuneracion != null ? this.formHistorial.denominacion.remuneracion : '--';

            }).catch(error => {
                alertToast("Error, recargue la página", 3500);
            });
        },
        //Editar Historial
        async editarHistorial(id, habilitarGrabar, element = null) {

            this.volver_guardar = false;

            if (element != null)
                transaccionToogle(element, true);

            this.habilitarGrabar = false;

            var urlKeeps = 'editarPermiso';
            var fill = {
                'id': id
            }
            await axios.post(urlKeeps, fill).then(response => {

                this.limpiarForm();
                this.formHistorial = response.data.datos;

                this.formHistorial.area_id = '',
                    this.formHistorial.edificio_id = '',
                    this.formHistorial.denominacion_id = '',
                    this.formHistorial.cargo_id = '',
                    this.formHistorial.fecha_ingreso = '',
                    this.formHistorial.fecha_salida = '',
                    this.formHistorial.tipo_contrato_id = '',
                    this.formHistorial.motivo_id = ''

                this.formCrear.fecha_fin = this.formCrear.fecha_fin != null ? this.formCrear.fecha_fin : this.formCrear.fecha_inicio;
                this.formCrear.hora_fin = this.formCrear.hora_fin != null ? this.formCrear.hora_fin : '00:00:00';
                this.formCrear.hora_inicio = this.formCrear.hora_inicio != null ? this.formCrear.hora_inicio : '00:00:00';
                this.mostrarArchivo = this.formCrear.archivo != null ? true : false;
                this.editar = true;

            }).catch(error => {
                alertToast("Error, recargue la página", 3500);
            });
        },

        async guardarHistorial() {

            var urlKeeps = 'guardarHistorial_';
            axios.post(urlKeeps, appHistorial.formHistorial).then(response => {
                console.log(response);
                if (response.data.status != "200") {
                    appHistorial.cargando_ = false;
                    if (response.data.status == "300")
                        alertToast(response.data.message, 3500);
                    else
                        alertToast("Error al Guardar el registro", 3500);
                }
                else {
                    appHistorial.cargando_ = false;
                    datatablehistorialPersona(this.formHistorial.persona_id);
                    document.querySelector("#cerrar_modal_historial").click();

                    alertToastSuccess("Guardado Exitoso", 3500);
                }

            }).catch(error => {
                appHistorial.cargando_ = false;
                swal("Cancelado!", "Error al grabar...", "error");
            });
        },
    }
})
