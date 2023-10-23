var appPerfil = new Vue({
    el: '#main2',
    data(){
        return {
            currentTab:1,
            editar:false,
            editarActividad_:false,
            imagenCargada:false,
            srcImagenCargada:'',
            customLink:'informacion',
            editar_perfil:false,
            formPersona:{
                'id':0,
                'identificacion':'',
                'apellidos':'',
                'nombres':'',
                'fecha_nacimiento':'',
                'genero':'',
                'estado_civil':'',
                'apellidos_nombres':'',
                'correo_institucional':'',
                'correo_personal':'',
                'numero_telefono_casa':0,
                'numero_telefono_celular':0,
                'numero_telefono_extension':0,
                'numero_domicilio':0,
                'provincia_id':0,
                'canton_id':0,
                'calle_principal':'',
                'calle_secundaria':'',
                'sector':'',
                'bono_residencia':'',
                'jubilacion_iess':'',
                'tipo_sangre':'',
                'grupo_etnico':'',
                'fotografia':'',
                'referencia_emergencia':'',
                'referencia_contacto':'',
                'estado':'',
                'usuario_inserta':'',
                'fecha_inserta':'',
                'usuario_modifica':'',
                'fecha_modifica':'',
                'eliminado':'',
                'fecha_ingreso':'',
                'apellidos':'',
                'nombres':'',
            },
            formBanco:{
                'id':0,
                'tipo_cuenta':'',
                'nombre_banco':'',
                'numero_cuenta':'',
                'persona_id':'',
                'usuario_inserta':'',
                'fecha_inserta':'',
                'usuario_modifica':'',
                'fecha_modifica':'',
                'eliminado':'',
            },
            formCurso:{
                'id':0,
                'tipo_capacitacion':'',
                'nombre':'',
                'anio':'',
                'numero_horas':'',
                'persona_id':'',
                'usuario_inserta':'',
                'fecha_inserta':'',
                'usuario_modifica':'',
                'fecha_modifica':'',
                'eliminado':'',

            },
            formEstudio:{
                'id':0,
                'instruccion':'',
                'titulo':'',
                'institucion':'',
                'numero_referencia':'',
                'persona_id':'',
                'usuario_inserta':'',
                'fecha_inserta':'',
                'usuario_modifica':'',
                'fecha_modifica':'',
                'eliminado':'',
            },
            formFamiliar:{
                'id':0,
                'persona_id':'',
                'parentesco':'',
                'identificacion':'',
                'nombres':'',
                'apellidos':'',
                'fecha_nacimiento':'',
                'genero':'',
                'carnet_conadis':'',
                'enfermedad_catastrofica':'',
                'discapacidad':'',
                'porcentaje':'',
                'usuario_inserta':'',
                'fecha_inserta':'',
                'usuario_modifica':'',
                'fecha_modifica':'',
                'eliminado':'',
                'telefono':'',

            },
            formBienestar:{
                'id':0,
                'discapacidad_nombre':'',
                'discapacidad_numero_carnet':'',
                'discapacidad_porcentaje':'',
                'enfermedad_nombre':'',
                'enfermedad_numero_carnet':'',
                'enfermedad_porcentaje':'',
                'catastrofica_nombre':'',
                'accidente_laboral':'',
                'parte_cuerpo_afectado':'',
                'persona_id':'',

            },
            formDiscapacidad:{
                'id':0,
                'persona_id':'',
                'nombre':'',
                'numero_carnet':'',
                'porcentaje':'',
            },
            formEnfermedad:{
                'id':0,
                'persona_id':'',
                'nombre':'',
                'fecha_diagnostico':'',
            },
            formDeclaracion:{
                'persona_id':'',
                'fecha_declaracion':'',
                'archivo':''
            },
            cantones:[],
            cargando:false,
            cargando_canton:false,
            encuesta_activa:true,
            administrador_cursos:false,
            inactivos:false,
            rol_consulta:false,
            editar_perfil_personal:true
        }
    },
    created:function(){
        this.getKeeps();
    },
    methods:{
        async buscarRegistroCivil(){
            this.cargando=true;

            var urlKeeps=document.querySelector("#inicializacion").value+'/uath/consultaDatosPersona';
            var fill={
                'cedula':this.formPersona.identificacion
            }
            await axios.post(urlKeeps,fill).then(response=>{
                this.cargando=false;

                if(response.data.status!="200"){
                      alertToast(response.data.message,3500);
                      this.formPersona.apellidos_nombres='';
                      this.formPersona.nombre='';
                      this.formPersona.fecha_nacimiento='';
                      this.formPersona.estado_civil='';
                      this.formPersona.calle_principal='';
                      this.formPersona.numero_domicilio='';
                      this.formPersona.genero='';
                      this.formPersona.fotografia='';
                      $('#fotografia').val(null);
                }else{
                    if(response.data.persona!=null){
                        this.editar=true;
                        this.formPersona=response.data.persona;
                        this.actualizarDatos(true);
                    }
                    if(response.data.datos.status=="200"){
                        $('#fotografia').val(null);
                        this.formPersona.apellidos_nombres=response.data.datos.message[1]["valor"];
                         var fecha_nac=response.data.datos.message[3]["valor"].split('/');
                        this.formPersona.fecha_nacimiento=fecha_nac[2]+'-'+fecha_nac[1]+'-'+fecha_nac[0];
                        this.formPersona.estado_civil=response.data.datos.message[5]["valor"];
                        this.formPersona.genero=response.data.datos.message[22]["valor"]=="HOMBRE"?"MASCULINO":"FEMENINO";
                        this.formPersona.fotografia='data:image/png;base64,'+response.data.datos.message[23]["valor"];
                        this.verImagen();
                        document.querySelector("#edad_perfil").innerHTML= calcular_edad_perfil(this.formPersona.fecha_nacimiento);

                    }else{
                        alertToast(response.data.datos.message,3500);
                    }


                }

            }).catch(error => {
                alertToast("Error al buscar..., recargue la página",3500);
                this.cargando=false;
            });
        },

        cambiarCustom:function(data){
            this.customLink=data;
        },
        limpiarDatatable:function(){

            datatableCargarPersonasEstudios();
            datatableCargarPersonasCursos();
            datatableCargasFamiliares();
            datatableCargarPersonasDiscapacidad();
            datatableCargarPersonasEnfermedad();
            datatableCargarDeclaracionesJuramentadas();
        },
        limpiarPersonaEnfermedad:function(){
            this.formEnfermedad.id=0;
            this.formEnfermedad.persona_id='';
            this.formEnfermedad.nombre='';
            this.formEnfermedad.fecha_diagnostico='';
        },
        limpiarPersonaDiscapacidad:function(){
            this.formDiscapacidad.id=0;
            this.formDiscapacidad.persona_id='';
            this.formDiscapacidad.nombre='';
            this.formDiscapacidad.numero_carnet='';
            this.formDiscapacidad.porcentaje='';

        },
        limpiarBanco:function(){
            this.formBanco.id='';
            this.formBanco.tipo_cuenta='';
            this.formBanco.nombre_banco='';
            this.formBanco.numero_cuenta='';
            this.formBanco.persona_id='';
            this.formBanco.usuario_inserta='';
            this.formBanco.fecha_inserta='';
            this.formBanco.usuario_modifica='';
            this.formBanco.fecha_modifica='';
            this.formBanco.eliminado='';

        },
        
        limpiarDeclaracionJuramentada:function(){
            $("#fecha_declaracion").val(null);
            $("#archivo_declaracion").val(null);
            $("#archivo_declaracion_label").text("Seleccione el archivo");
        },
        limpiarEstudio:function(){
            this.formEstudio.id='';
            this.formEstudio.instruccion='';
            this.formEstudio.titulo='';
            this.formEstudio.institucion='';
            this.formEstudio.numero_referencia='';
            this.formEstudio.persona_id='';
            this.formEstudio.usuario_inserta='';
            this.formEstudio.fecha_inserta='';
            this.formEstudio.usuario_modifica='';
            this.formEstudio.fecha_modifica='';
            this.formEstudio.eliminado='';

        },
        limpiarCurso:function(){
            this.formCurso.id=0;
            this.formCurso.tipo_capacitacion='';
            this.formCurso.nombre='';
            this.formCurso.anio='';
            this.formCurso.numero_horas='';
            this.formCurso.persona_id='';
            this.formCurso.usuario_inserta='';
            this.formCurso.fecha_inserta='';
            this.formCurso.usuario_modifica='';
            this.formCurso.fecha_modifica='';
            this.formCurso.eliminado='';
        },
        limpiarCargaFamiliar:function(){
            this.formFamiliar.id=0;
            this.formFamiliar.persona_id='';
            this.formFamiliar.parentesco='';
            this.formFamiliar.identificacion='';
            this.formFamiliar.apellidos_nombres='';
            this.formFamiliar.fecha_nacimiento='';
            this.formFamiliar.genero='';
            this.formFamiliar.carnet_conadis='';
            this.formFamiliar.enfermedad_catastrofica='';
            this.formFamiliar.discapacidad='';
            this.formFamiliar.porcentaje='';
            this.formFamiliar.usuario_inserta='';
            this.formFamiliar.fecha_inserta='';
            this.formFamiliar.usuario_modifica='';
            this.formFamiliar.fecha_modifica='';
            this.formFamiliar.eliminado='';
            this.formFamiliar.telefono='';
            $("#enfermedad_catastrofica").val(null).change();
        },
        limpiarBienestar:function(){
            this.formBienestar.id=0;
            this.formBienestar.discapacidad_nombre='';
            this.formBienestar.discapacidad_numero_carnet='';
            this.formBienestar.discapacidad_porcentaje='';
            this.formBienestar.enfermedad_nombre='';
            this.formBienestar.enfermedad_numero_carnet='';
            this.formBienestar.enfermedad_porcentaje='';
            this.formBienestar.catastrofica_nombre='';
            this.formBienestar.accidente_laboral='';
            this.formBienestar.parte_cuerpo_afectado='';
            $("#formBienestar_catastrofica_nombre").val(null).change();

        },
        limpiarPersonaDiscapacidad:function(){
                this.formDiscapacidad.id=0;
                this.formDiscapacidad.persona_id='';
                this.formDiscapacidad.nombre='';
                this.formDiscapacidad.numero_carnet='';
                this.formDiscapacidad.porcentaje='';

        },
        limpiarPersonaEnfermedad:function(){
            this.formEnfermedad.id=0;
            this.formEnfermedad.persona_id='';
            this.formEnfermedad.nombre='';
           this.formEnfermedad.fecha_diagnostico='';
           $("#formBienestar_catastrofica_nombre").val(null).change();
        },
        limpiarPersonas:function(){
            this.limpiarBanco();
            this.limpiarBienestar();
            this.editar_perfil=true;

           document.querySelector("#edad_perfil").innerHTML='';
           document.querySelector("#informacionGeneral").click();
           this.customLink='informacion';
           this.cargando=false;
           this.editar=false;
           this.srcImagenCargada='';
           this.imagenCargada=false;
           this.formPersona.id=0;
           this.formPersona.identificacion='';
           this.formPersona.apellidos='';
           this.formPersona.nombres='';
           this.formPersona.fecha_nacimiento='';
           this.formPersona.genero='';
           this.formPersona.estado_civil='';
           this.formPersona.nacionalidad='';
           this.formPersona.apellidos_nombres='';
           this.formPersona.correo_institucional='';
           this.formPersona.correo_personal='';
           this.formPersona.numero_telefono_casa=0;
           this.formPersona.numero_telefono_celular=0;
           this.formPersona.numero_telefono_extension=0;
           this.formPersona.provincia_id=0;
           this.formPersona.canton_id=0;
           this.formPersona.calle_principal='';
           this.formPersona.calle_secundaria='';
           this.formPersona.numero_domicilio=0;
           this.formPersona.sector='';
           this.formPersona.bono_residencia='';
           this.formPersona.jubilacion_iess='';
           this.formPersona.tipo_sangre='';
           this.formPersona.grupo_etnico='';
           this.formPersona.fotografia='';
           this.formPersona.referencia_emergencia='';
           this.formPersona.referencia_contacto='';
           this.formPersona.estado='';
           this.formPersona.usuario_inserta='';
           this.formPersona.fecha_inserta='';
           this.formPersona.usuario_modifica='';
           this.formPersona.fecha_modifica='';
           this.formPersona.eliminad='';
           this.formPersona.fecha_ingreso='';
           this.formPersona.apellidos='';
           this.formPersona.nombres='';
            this.editar_perfil_personal=false;
           this.cargando_canton=false;
           $("#canton_id_perfil").html('');

        },
        verImagen:function(){
            var timestamp = new Date().getTime();
            let descripcion=this.formPersona.fotografia;
            let nombre=this.formPersona.fotografia;
            let direccion='';
            if(descripcion==""||descripcion==null)
                 this.imagenCargada=false;
            else{
                    direccion=this.formPersona.fotografia.indexOf("data:image/png;base64")==-1?(document.querySelector("#direccionDocumentos").value+'/FOTOS/'):'';
                    direccion=direccion+descripcion;

                this.srcImagenCargada=this.formPersona.fotografia.indexOf("data:image/png;base64")==-1?(direccion+'?t='+timestamp):direccion;
                this.imagenCargada=true;
            }


        },

        async cargarCanton(provincia_id){
           // this.limpiarPersonas();
          ///  this.editar=true;
            var urlKeeps=document.querySelector("#inicializacion").value+'/uath/cargarCanton';
            var fill={
                'provincia_id':provincia_id
            }
            await axios.post(urlKeeps,fill).then(response=>{
                this.cantones=response.data.datos;
                $("#canton_id_perfil").html('');

                agregarComboCanton(0,0,true);
                this.cantones.forEach(function(key, value){
                    agregarComboCanton(key.id, key.nombre);
                });
                if(this.cargando_canton==true){
                    $("#canton_id_perfil").val(this.formPersona.canton_id).change();
                    this.cargando_canton=false;
                }else{
                    this.formPersona.canton_id='';
                    $("#canton_id_perfil").val("").change();
                }


            }).catch(error => {
               alertToast("Error en la carga",3500);
            });
        },

            async editarCurso(id){
                var urlKeeps=document.querySelector("#inicializacion").value+'/uath/editarCurso';
                var fill={
                    'id':id
                }
                await axios.post(urlKeeps,fill).then(response=>{
                    this.formCurso=response.data.message;

                }).catch(error => {
                   alertToast("Error en la carga",3500);
                });
            },

            async eliminarCurso(id){
                var urlKeeps=document.querySelector("#inicializacion").value+'/uath/eliminarCurso';
                var fill={
                    'id':id
                }
                await axios.post(urlKeeps,fill).then(response=>{
                    alertToastSuccess("Eliminado Exitoso",3500);
                    this.limpiarCurso();
                    datatableCargarPersonasCursos();
                }).catch(error => {
                   alertToast("Error en la carga",3500);
                });
            },
            async guardarCurso(){
                var urlKeeps=document.querySelector("#inicializacion").value+'/uath/guardarCurso';
                this.formCurso.persona_id=this.formPersona.id;
                this.formCurso.identificacion=this.formPersona.identificacion;
                var errores = this.validarCedulaRegistrada();

                if(this.formCurso.nombre==''||this.formCurso.nombre==null)
                {
                        errores += "\n Debe de ingresar el nombre del curso";
                }
                if(this.formCurso.tipo_capacitacion==''||this.formCurso.tipo_capacitacion==null)
                {
                        errores += "\n Debe de ingresar el tipo de capacitación";
                }
                if (errores != "Errores") {
                    alertToast(errores, 3500);
                    return false;
                }
                await axios.post(urlKeeps,this.formCurso).then(response=>{
                    if(response.data.status=="200"){
                        this.formPersona.id=response.data.persona_id;
                        datatableCargarPersonasCursos();
                        this.limpiarCurso();
                        alertToastSuccess("Grabado Exitoso",3500);
                    }else{
                          alertToast("Error en la carga",3500);
                    }

                }).catch(error => {
                   alertToast("Error en la carga",3500);
                });
            },
            async eliminarHistorial(id,persona_id){
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
                        var urlKeeps=document.querySelector("#inicializacion").value+'/uath/eliminarHistorial';
                        let fill={
                            'id':id
                        }
                         axios.post(urlKeeps,fill).then(response=>{
                            if(response.data.status!="200")
                                    alertToast("Error al eliminar el registro",3500);
                            else{
                                alertToastSuccess("Eliminado Exitoso",3500);
                                datatablehistorialPersona(persona_id);
                             //   datatableCargarPersonas();
                            }

                        }).catch(error => {
                            appPerfil.cargando=false;
                            swal("Cancelado!", "Error al grabar...", "error");
                        });
                    } else {
                        swal("Cancelado!", "No se registraron cambios...", "error");
                        return false;
                    }

                })
            },

            guardarHistorial:function(id,persona_id){

                appPerfil.cargando=false;
                var url=document.querySelector("#inicializacion").value+'/uath/guardarHistorial';
              //  var url = 'guardarHistorial';
                var data = new FormData();
                data.append('id', id);
                data.append('persona_id', persona_id);

                if($("#area"+"_"+id+"").val()!=null)
                data.append('area_id', $("#area"+"_"+id+"").val());

                if($("#cargo"+"_"+id+"").val()!=null)
                data.append('cargo_id', $("#cargo"+"_"+id+"").val());

                if($("#denominacion"+"_"+id+"").val()!=null)
                data.append('denominacion_id', $("#denominacion"+"_"+id+"").val());

                if($("#tipo_contrato_id"+"_"+id+"").val()!=null)
                data.append('tipo_contrato_id', $("#tipo_contrato_id"+"_"+id+"").val());
                data.append('motivo_id', $("#motivo_id"+"_"+id+"").val());

                data.append('fecha_ingreso', $("#fecha_ingreso"+"_"+id+"").val());
                data.append('fecha_salida', $("#fecha_salida"+"_"+id+"").val());
                iniciar_modal_espera();
                axios.post(url, data).then(response => {
                    parar_modal_espera();
                    appPerfil.cargando=false;
                    datatablehistorialPersona(persona_id);
                    alertToastSuccess("Registro descargado exitosamente", 3500)
                }).catch(error => {
                    appPerfil.cargando=false;
                    swal("Cancelado!", "Error al grabar...", "error");
                });
            },
            async editarEstudio(id){
                var urlKeeps=document.querySelector("#inicializacion").value+'/uath/editarEstudio';
                var fill={
                    'id':id
                }
                await axios.post(urlKeeps,fill).then(response=>{
                    this.formEstudio=response.data.message;

                }).catch(error => {
                   alertToast("Error en la carga",3500);
                });


            },
            async eliminarEstudio(id){
                var urlKeeps=document.querySelector("#inicializacion").value+'/uath/eliminarEstudio';
                var fill={
                    'id':id
                }
                await axios.post(urlKeeps,fill).then(response=>{
                    alertToastSuccess("Eliminado Exitoso",3500);
                    datatableCargarPersonasEstudios();
                    this.limpiarEstudio();

                }).catch(error => {
                   alertToast("Error en la carga",3500);
                });
            },
            async eliminarDeclaracionJuramentada(id){
                var urlKeeps=document.querySelector("#inicializacion").value+'/uath/eliminarDeclaracionJuramentada';
                var fill={
                    'id':id
                }
                await axios.post(urlKeeps,fill).then(response=>{
                    alertToastSuccess("Eliminado Exitoso",3500);
                    datatableCargarDeclaracionesJuramentadas();
                    this.limpiarDeclaracionJuramentada();

                }).catch(error => {
                   alertToast("Error en la carga",3500);
                });
            },
            async guardarDeclaracionJuramentada(){
                var urlKeeps=document.querySelector("#inicializacion").value+'/uath/guardarDeclaracionJuramentada';
                var errores = this.validarCedulaRegistrada();
                var data = new FormData();
                data.append('persona_id',this.formPersona.id);
                data.append('identificacion',this.formPersona.identificacion);
                data.append('fecha_declaracion',$("#fecha_declaracion").val());

                $('#archivo_declaracion').each(function (a, array) {
                    if (array.files.length > 0) {
                        $.each(array.files, function (k, file) {
                            data.append('archivos[' + k + ']', file);
                        })
                    } else
                    data.append('archivos', null);
                });
                if (errores != "Errores") {
                    alertToast(errores, 3500);
                    return false;
                }
                await axios.post(urlKeeps,data).then(response=>{
                    if(response.data.status=="200"){
                        this.formPersona.id=response.data.persona_id;
                        alertToastSuccess("Grabado Exitoso",3500);
                        datatableCargarDeclaracionesJuramentadas();
                        this.limpiarDeclaracionJuramentada();
                    }else{
                          alertToast("Error en la carga",3500);
                    }

                }).catch(error => {
                   alertToast("Error en la carga",3500);
                });
            },
            descargarDeclaracionJuramentada: function (id) {
                var url=document.querySelector("#inicializacion").value+'/uath/descargarDeclaracionJuramentada';
            
                    var data = new FormData();
    
                    data.append('id', id);
    
                    cargarLoading();
                    axios.post(url, data).then(response => {
                        $(".confirm").click()
                        let direccion = document.querySelector("#direccionDocumentos").value;
                        direccion = direccion + response.data.url_declaraciones + response.data.descripcion;
                        downloadURI(direccion, response.data.nombre)
    
                    }).catch(error => {
                        $(".confirm").click()
                        swal("Cancelado!", "Error al grabar...", "error");
                    });
          
    
            },
            async guardarEstudio(){
                var urlKeeps=document.querySelector("#inicializacion").value+'/uath/guardarEstudio';
                this.formEstudio.persona_id=this.formPersona.id;
                this.formEstudio.identificacion=this.formPersona.identificacion;
                var errores = this.validarCedulaRegistrada();

                if(this.formEstudio.instruccion==''||this.formEstudio.instruccion==null)
                {
                        errores += "\n Debe de seleccionar su tipo de instruccion";
                }
                if(this.formEstudio.titulo==''||this.formEstudio.titulo==null)
                {
                        errores += "\n Debe de ingresar el nombre del titulo";
                }
                if(this.formEstudio.institucion==''||this.formEstudio.institucion==null)
                {
                        errores += "\n Debe de ingresar la institución";
                }
                if (errores != "Errores") {
                    alertToast(errores, 3500);
                    return false;
                }
                await axios.post(urlKeeps,this.formEstudio).then(response=>{
                    if(response.data.status=="200"){
                        this.formPersona.id=response.data.persona_id;
                        alertToastSuccess("Grabado Exitoso",3500);
                        datatableCargarPersonasEstudios();
                        this.limpiarEstudio();
                    }else{
                          alertToast("Error en la carga",3500);
                    }

                }).catch(error => {
                   alertToast("Error en la carga",3500);
                });
            },

            async editarHistorial(id){
                var urlKeeps=document.querySelector("#inicializacion").value+'/uath/editarHistorial';
                var fill={
                    'id':id
                }
                await axios.post(urlKeeps,fill).then(response=>{

                    $("#area_"+id+"").val(response.data.message.area_id).change();
                    $("#cargo_"+id+"").val(response.data.message.cargo_id).change();
                    $("#denominacion_"+id+"").val(response.data.message.denominacion_id).change();
                }).catch(error => {
                   alertToast("Error en la carga",3500);
                });

            },
            async editarCargaFamiliar(id){
                var urlKeeps=document.querySelector("#inicializacion").value+'/uath/editarCargaFamiliar';
                var fill={
                    'id':id
                }
                await axios.post(urlKeeps,fill).then(response=>{
                    this.formFamiliar=response.data.message;
                    $("#enfermedad_catastrofica").val(this.formFamiliar.enfermedad_catastrofica).change();
                }).catch(error => {
                   alertToast("Error en la carga",3500);
                });

            },
            async eliminarCargaFamiliar(id){
                var urlKeeps=document.querySelector("#inicializacion").value+'/uath/eliminarCargaFamiliar';
                var fill={
                    'id':id
                }
                await axios.post(urlKeeps,fill).then(response=>{
                    alertToastSuccess("Eliminado Exitoso",3500);
                    datatableCargasFamiliares();
                    this.limpiarCargaFamiliar();
                }).catch(error => {
                   alertToast("Error en la carga",3500);
                });
            },



            async guardarCargaFamiliar(){
                var urlKeeps=document.querySelector("#inicializacion").value+'/uath/guardarCargaFamiliar';
                this.formFamiliar.persona_id=this.formPersona.id;
                this.formFamiliar.identificacion_persona=this.formPersona.identificacion;
                var errores = this.validarCedulaRegistrada();
                if (errores != "Errores") {
                    alertToast(errores, 3500);
                    return false;
                }
                await axios.post(urlKeeps,this.formFamiliar).then(response=>{
                    if(response.data.status=="200"){
                        this.formPersona.id=response.data.persona_id;
                        alertToastSuccess("Grabado Exitoso",3500);
                        datatableCargasFamiliares();
                        this.limpiarCargaFamiliar();
                    }else{
                          alertToast("Error en la carga",3500);
                    }

                }).catch(error => {
                   alertToast("Error en la carga",3500);
                });
            },

            async editarPersonaDiscapacidad(id){
                var urlKeeps=document.querySelector("#inicializacion").value+'/uath/editarPersonaDiscapacidad';
                var fill={
                    'id':id
                }
                await axios.post(urlKeeps,fill).then(response=>{
                    this.formDiscapacidad=response.data.message;

                }).catch(error => {
                   alertToast("Error en la carga",3500);
                });

            },
            async eliminarPersonaDiscapacidad(id){
                var urlKeeps=document.querySelector("#inicializacion").value+'/uath/eliminarPersonaDiscapacidad';
                var fill={
                    'id':id
                }
                await axios.post(urlKeeps,fill).then(response=>{
                    alertToastSuccess("Eliminado Exitoso",3500);
                    datatableCargarPersonasDiscapacidad();
                    this.limpiarEstudio();

                }).catch(error => {
                   alertToast("Error en la carga",3500);
                });
            },
            async guardarPersonaDiscapacidad(){
                var urlKeeps=document.querySelector("#inicializacion").value+'/uath/guardarPersonaDiscapacidad';
                this.formDiscapacidad.persona_id=this.formPersona.id;
                this.formDiscapacidad.identificacion=this.formPersona.identificacion;
                var errores = this.validarCedulaRegistrada();
                if (errores != "Errores") {
                    alertToast(errores, 3500);
                    return false;
                }
                await axios.post(urlKeeps,this.formDiscapacidad).then(response=>{
                    if(response.data.status=="200"){
                        this.formPersona.id=response.data.persona_id;
                        alertToastSuccess("Grabado Exitoso",3500);
                        datatableCargarPersonasDiscapacidad();
                        this.limpiarPersonaDiscapacidad();
                    }else{
                          alertToast("Error en la carga",3500);
                    }

                }).catch(error => {
                   alertToast("Error en la carga",3500);
                });
            },

            async editarPersonaEnfermedad(id){
                var urlKeeps=document.querySelector("#inicializacion").value+'/uath/editarPersonaEnfermedad';
                var fill={
                    'id':id
                }
                await axios.post(urlKeeps,fill).then(response=>{
                    this.formEnfermedad=response.data.message;
                    $("#formBienestar_catastrofica_nombre").val(this.formEnfermedad.nombre).change();

                }).catch(error => {
                   alertToast("Error en la carga",3500);
                });


            },
            async eliminarPersonaEnfermedad(id){
                var urlKeeps=document.querySelector("#inicializacion").value+'/uath/eliminarPersonaEnfermedad';
                var fill={
                    'id':id
                }
                await axios.post(urlKeeps,fill).then(response=>{
                    alertToastSuccess("Eliminado Exitoso",3500);
                    datatableCargarPersonasEnfermedad();
                    this.limpiarPersonaEnfermedad();

                }).catch(error => {
                   alertToast("Error en la carga",3500);
                });
            },
            async guardarPersonaEnfermedad(){
                var urlKeeps=document.querySelector("#inicializacion").value+'/uath/guardarPersonaEnfermedad';
                this.formEnfermedad.persona_id=this.formPersona.id;
                this.formEnfermedad.identificacion=this.formPersona.identificacion;
                var errores = this.validarCedulaRegistrada();
                if (errores != "Errores") {
                    alertToast(errores, 3500);
                    return false;
                }
                await axios.post(urlKeeps,this.formEnfermedad).then(response=>{
                    if(response.data.status=="200"){
                        this.formPersona.id=response.data.persona_id;
                        alertToastSuccess("Grabado Exitoso",3500);
                        datatableCargarPersonasEnfermedad();
                        this.limpiarPersonaEnfermedad();
                    }else{
                          alertToast("Error en la carga",3500);
                    }

                }).catch(error => {
                   alertToast("Error en la carga",3500);
                });
            },
            actualizarDatos:function(regCivil=false){
                this.formPersona.bono_residencia=this.formPersona.bono_residencia!=null?this.formPersona.bono_residencia:false;
                this.formPersona.jubilacion_iess=this.formPersona.jubilacion_iess!=null?this.formPersona.jubilacion_iess:false;
                if(!regCivil)
                this.verImagen();
                this.cargando_canton=true;
                $("#provincia_id_perfil").val(this.formPersona.provincia_id).change();
                appPerfil.formPersona.provincia_id;
                this.cargarCanton(appPerfil.formPersona.provincia_id);
                document.querySelector("#edad_perfil").innerHTML=calcular_edad_perfil(this.formPersona.fecha_nacimiento);
                this.formBanco.tipo_cuenta=this.formPersona.tipo_cuenta;
                this.formBanco.nombre_banco=this.formPersona.nombre_banco;
                this.formBanco.numero_cuenta=this.formPersona.numero_cuenta;
                this.formBanco.persona_id=this.formPersona.persona_id;
                this.limpiarDatatable();
            },
            async editarPerfil(){

                 this.editar_perfil=true;

                    this.limpiarPersonas();
                    this.editar=true;
                    var urlKeeps=document.querySelector("#inicializacion").value+'/uath/editarPerfil';
                    var fill={
                        'identificacion':document.querySelector("#cedula_logueada").value
                    }
                    await axios.post(urlKeeps,fill).then(response=>{
                        if(response.data.message!=null){
                            this.formPersona=response.data.message;
                            this.actualizarDatos();
                            this.editar_perfil_personal=true;

                        }else
                        alertToast("No existen datos cargados por Talento Humano",3500);


                    }).catch(error => {
                    alertToast("Error en la carga",3500);
                    });
            },
            async editarPersona(id,editar_perfil='1'){

                    this.limpiarPersonas();
                    if(editar_perfil=='1')
                    this.editar_perfil=true;
                    this.editar=true;
                      var urlKeeps=document.querySelector("#inicializacion").value+'/uath/editarPersona';
                   // var urlKeeps='editarPersona';
                    var fill={
                        'id':id
                    }
                    await axios.post(urlKeeps,fill).then(response=>{
                        if(response.data.status=="200"){
                            this.formPersona=response.data.message;
                            this.editar_perfil_personal=false;
                            if(this.formPersona.identificacion!=response.data.identificacion){
                                this.administrador_cursos=response.data.rol_administrador_cursos;
                                this.inactivos=response.data.rol_inactivos;
                                this.rol_consulta=response.data.rol_consulta;


                            }else{
                                this.administrador_cursos=false;
                                this.inactivos=false;
                                this.rol_consulta=false
                            }
                            this.actualizarDatos();
                        }else
                            alertToast("Error en la carga",3500);

                    }).catch(error => {
                        alertToast("Error en la carga",3500);
                    });

            },

            getKeeps:function(){
              /*  var urlKeeps='consultaEstados';
                let fecha_inicio=$("#fecha_inicio").val();
                let fecha_fin=$("#fecha_fin").val();
                let fill={
                    'fecha_inicio':fecha_inicio,
                    'fecha_fin':fecha_fin,
                }
                axios.post(urlKeeps,fill).then(response=>{
                    this.activos=response.data.datos.activos;
                    this.inactivos=response.data.datos.inactivos;
                    this.pendientes=response.data.datos.pendientes;
                })*/
            },

            async eliminarPersona(id){
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
                        var urlKeeps=document.querySelector("#inicializacion").value+'/uath/eliminarPersona';
                        let fill={
                            'id':id
                        }
                         axios.post(urlKeeps,fill).then(response=>{
                            if(response.data.status!="200")
                                    alertToast(response.data.datos,3500);
                            else{

                                datatableCargarPersonas();
                            }

                        }).catch(error => {
                            appPerfil.cargando=false;
                            swal("Cancelado!", "Error al grabar...", "error");
                        });
                    } else {
                        swal("Cancelado!", "No se registraron cambios...", "error");
                        return false;
                    }

                })
            },

            async grabarUsuarioEncuesta(descripcion){
                var urlKeeps=document.querySelector("#inicializacion").value+'/grabarUsuarioEncuesta';
                var fill={
                    'descripcion':descripcion
                }
                this.encuesta_activa=true;

                await axios.post(urlKeeps,fill).then(response=>{
                    this.encuesta_activa=false;
                    $("#encuestaUsuariosGeneral").addClass("hidden");
                    if(response.data.status==200){
                        alertToastSuccess("Registrado Exitoso",3500);
                    }
                    else{
                        alertToast(response.data.message,3500);
                    }
                }).catch(error => {
                    this.encuesta_activa=false;
                   alertToast("Error en la carga",3500);
                });
            },
            async guardarUbicacion(){
                var urlKeeps=document.querySelector("#inicializacion").value+'/uath/guardarRegistro';
                await axios.post(urlKeeps,this.formPersona).then(response=>{

                    if(!this.editar_perfil&&this.formPersona.id==0){
                        appHistorial.consultaEstados();
                        datatableCargarPersonas();
                    }
                    alertToastSuccess("Grabado Exitoso",3500);
                }).catch(error => {
                   alertToast("Error en la carga",3500);
                });
            },

            async guardarBanco(){
                this.formBanco.persona_id=this.formPersona.id;
                this.formBanco.identificacion=this.formPersona.identificacion;
                var errores = this.validarCedulaRegistrada();
                if (errores != "Errores") {
                    alertToast(errores, 3500);
                    return false;
                }
                var fill=this.formBanco;
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
                        var urlKeeps = document.querySelector("#inicializacion").value+'/uath/guardarBanco';
                            appPerfil.cargando=true;
                             axios.post(urlKeeps,fill).then(response=>{
                                if(response.data.status=="200"){
                                    appPerfil.formPersona.persona_id=response.data.persona_id;
                                    appPerfil.cargando=false;
                                    appPerfil.editar=true;
                                    alertToastSuccess("Registrado exitosamente", 3500);
                                }else{
                                      alertToast("Error en la carga",3500);
                                }


                            })
                           .catch(error => {
                                appPerfil.cargando=false;
                                swal("Cancelado!", "Error al registrar...", "error");

                            });


                    } else {
                        swal("Cancelado!", "No se registraron cambios...", "error");
                        return false;
                    }
                });
            },
            validarCedulaRegistrada:function(){
                var errores = "Errores";
                if(($("[name='errorCedula']").text().indexOf(" no ")!=-1&&$("[name='errorCedula']").text()!='')||appPerfil.formPersona.identificacion.length<10)
                {
                        errores += "\n Debe de ingresar cédula válida";
                }
                return errores;
            },
            async guardarBienestar(){
                this.formBienestar.persona_id=this.formPersona.id;
                this.formBienestar.identificacion=this.formPersona.identificacion;
                var errores = this.validarCedulaRegistrada();
                if (errores != "Errores") {
                    alertToast(errores, 3500);
                    return false;
                }



                var fill=this.formBienestar;
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
                        var urlKeeps = document.querySelector("#inicializacion").value+'/uath/guardarBienestar';
                            appPerfil.cargando=true;
                             axios.post(urlKeeps,fill).then(response=>{
                                appPerfil.cargando=false;
                                appPerfil.editar=true;
                                alertToastSuccess("Registrado exitosamente", 3500);
                            })
                           .catch(error => {
                                appPerfil.cargando=false;
                                swal("Cancelado!", "Error al registrar...", "error");
                            });
                    } else {
                        swal("Cancelado!", "No se registraron cambios...", "error");
                        return false;
                    }
                });
            },
            async guardarRegistro(){
                this.formPersona.apellidos_nombres=this.formPersona.apellidos_nombres.toUpperCase();
                this.formPersona.bono_residencia=this.formPersona.bono_residencia==false?'':'1';
                this.formPersona.jubilacion_iess=this.formPersona.jubilacion_iess==false?'':'1';
                this.formPersona.provincia_id=this.formPersona.provincia_id==''|this.formPersona.provincia_id==null?0:this.formPersona.provincia_id;
                this.formPersona.canton_id=this.formPersona.canton_id==''|this.formPersona.canton_id==null?0:this.formPersona.canton_id;

                this.formPersona.numero_telefono_casa=this.formPersona.numero_telefono_casa==''|this.formPersona.numero_telefono_casa==null?0:this.formPersona.numero_telefono_casa;
                this.formPersona.numero_telefono_celular=this.formPersona.numero_telefono_celular==''|this.formPersona.numero_telefono_celular==null?0:this.formPersona.numero_telefono_celular;
                this.formPersona.numero_telefono_extension=this.formPersona.numero_telefono_extension==''|this.formPersona.numero_telefono_extension==null?0:this.formPersona.numero_telefono_extension;
                this.formPersona.numero_domicilio=this.formPersona.numero_domicilio==''|this.formPersona.numero_domicilio==null?0:this.formPersona.numero_domicilio;

               // this.formPersona.per_car_conadis_porcentaje=this.formPersona.per_car_conadis_porcentaje!=null?this.formPersona.per_car_conadis_porcentaje:0;
                //this.formPersona.per_por_enfermedad=this.formPersona.per_por_enfermedad!=null?this.formPersona.per_por_enfermedad:0;
                var fill=this.formPersona;
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
                        var url = document.querySelector("#inicializacion").value+'/uath/guardarRegistro';
                        cargarLoading();
                        var data = new FormData();

                        var errores = "Errores";
                            if(($("[name='errorCedula']").text().indexOf(" no ")!=-1&&$("[name='errorCedula']").text()!='')||appPerfil.formPersona.identificacion.length<10)
                            {
                                    errores += "\n Debe de ingresar cédula válida";
                            }

                        if($("[name='errorCorreo']").text().indexOf(" no ")!=-1&&$("[name='errorCorreo']").text()!='')
                        {
                                errores += "\n Debe de ingresar correo institucional válido";
                        }
                        if(appPerfil.formPersona.apellidos_nombres==""||appPerfil.formPersona.apellidos_nombres==null)
                        errores += "\n Debe de ingresar el nombre";

                        if(appPerfil.formPersona.correo_institucional==""||appPerfil.formPersona.correo_institucional==null)
                        errores += "\n Debe de ingresar el correo institucional";

                        if(appPerfil.formPersona.fecha_nacimiento==""||appPerfil.formPersona.fecha_nacimiento==null)
                        errores += "\n Debe de ingresar la fecha de nacimiento";

                        $('#fotografia').each(function (a, array) {
                            if (array.files.length > 0) {
                                $.each(array.files, function (k, file) {
                                    data.append('archivo[' + k + ']', file);
                                })
                            } else
                                 data.append('archivo', null);

                        });


                        if (errores != "Errores") {
                             alertToast(errores, 3500);
                        }else{

                            for (const property in fill) {
                                let atributo=`${property}`;
                                let valor=`${fill[property]}`;
                                data.append(atributo,valor);
                              }
                              appPerfil.cargando=true;

                            axios.post(url, data,
                                    { headers :
                                        {'content-type': 'multipart/form-data'}
                                    }
                            )
                            .then(response => {
                                var recargarDatatable=0;
                                if(!appPerfil.editar)
                                    recargarDatatable=1;

                                if(response.data.status=="200"){
                                    appPerfil.formPersona.id=response.data.id;
                                    appPerfil.cargando=false;
                                    appPerfil.editar=true;
                                    alertToastSuccess("Registrado exitosamente", 3500)
                                    appPerfil.formPersona.fotografia=response.data.foto;
                                    appPerfil.verImagen();
                                    if(recargarDatatable==1)
                                    appPerfil.limpiarDatatable();
                                        if(!appPerfil.editar_perfil&&appPerfil.formPersona.id==0){
                                            datatableCargarPersonas();
                                            appHistorial.consultaEstados();
                                        }
                                }else{
                                    alertToast("Error al grabar",3500);
                                }

                            }).catch(error => {
                                appPerfil.cargando=false;
                                swal("Cancelado!", "Error al registrar...", "error");

                            });
                        }


                    } else {
                        swal("Cancelado!", "No se registraron cambios...", "error");
                        return false;
                    }
                });
            },



    }
  })

