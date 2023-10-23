var app = new Vue({
    el: '#main',
    data() {
        return {
            cargando: false,
            formIpRestringido: {
                'id':0,
                'usuario_id':0,
                'ips':0,
            },
        }
    },
    created: function() {
        this.limpiarFormulario();
    },
    methods: {
        limpiarFormulario: function() {
            this.formIpRestringido.id=0;
            this.formIpRestringido.usuario_id=0;
            this.formIpRestringido.ips=0;
            $("#filtro_ip_restringidas option:first-child").attr("disabled", "disabled");
            $("#filtro_usuario option:first-child").attr("disabled", "disabled");
            $("#filtro_ip_restringidas").val(null).change();
            $("#filtro_usuario").val(null).change();

            /*$("#integrantes_area option:first-child").attr("disabled", "disabled");
            $("#dispositivos").prop("selectedIndex", 0);
            $("#integrantes_area").val(null).change();
            $("#check_politica").prop("checked", false);
            $("#integrantes_area").val(null).change();
            $("#dispositivos").prop("selectedIndex", 0);*/
        },
        //ACEPTANDO LA POLITICA DE SEGURIDAD
        async restringirIp() {
            var error = "";

            if (this.formIpRestringido.usuario_id == 0)
                error += '\n Debe seleccionar un usuario';
            if (this.formIpRestringido.ips == 0)
                error += '\n Debe seleccionar una ip restringida';

            if (error.length > 0) {
                swal("Errores", error, "error");
                return false;
            }
            var urlKeeps = 'restringirIp';
            app.cargando = true;
            iniciar_modal_espera();
            await axios.post(urlKeeps,this.formIpRestringido).then(response => {
                app.cargando = false;
                this.limpiarFormulario();
                parar_modal_espera();
                datatableCargarIpsRestringir();
                alertToastSuccess("POLITICA ACEPTADA", 3500)
            }).catch(error => {
                app.cargando = false;
                swal("Cancelado!", "Error al grabar...", "error");
            });
        },
        //ELIMINANDO FUNCIONARIO CON DISPOSITVO
        async eliminarRegistro(id) { //eliminar un registro 
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
                function(isConfirm) {
                    if (isConfirm) {
                        var urlKeeps = 'eliminarRegistro'; //setea la ruta del controlador
                        var fill = { 'id': id } //busca el id y lo almacena en la var 'id'
                        app.cargando = true; //mientras busca la data, el indicador se activa "cargando"
                        axios.post(urlKeeps, fill) //elimino registro
                            .then(response => {
                                app.cargando = false; //desaparece indicador de cargando
                                datatableCargarIpsRestringir();
                                alertToastSuccess("Eliminado Exitosamente", 3500);
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
       
    }
})