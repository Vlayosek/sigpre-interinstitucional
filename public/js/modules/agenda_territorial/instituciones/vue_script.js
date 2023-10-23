    var app = new Vue({
    el: '#main',
    data: {
            formCrear: {
                'id':0,
                'nombre':'',
                'institucion_id':'',
                'siglas':'',
                'ministro_usuario_id':''
                },
                formGabinete:{
                    'id':0,
                    'nombre':'',
                    'siglas':'',
                    },
                cargando:false,
                gabinete:false,
                text_gabinete:"<i class='fa fa-tasks'></i>&nbsp;GABINETE",
                text_institucion:"<i class='fa fa-tasks'></i>&nbsp;INSTITUCION",
                disabled_institucion:false
        },
        created:function(){
            this.limpiarForm();
         },
        methods: {
            limpiarForm:function(){
                this.formCrear.id=0;
                this.formCrear.nombre='';
                this.formCrear.institucion_id='';
                this.formCrear.siglas='';
                this.formCrear.ministro_usuario_id='';
                this.disabled_institucion=false;
                

                this.formGabinete.id=0;
                this.formGabinete.nombre='';
                this.formGabinete.siglas='';
                $("#filtro_institucion").val(null).change();
                $("#ministro_usuario_id").val(null).change();
               // resetarModal();
            },
            cambioVisualizador:function(){
                if(this.gabinete)
                this.gabinete=false;
                else
                this.gabinete=true;

            },
            
            async consultaInstitucion(id) {
                this.disabled_institucion=true;
                $("#principal_tab").click();
                var urlKeeps='consultaInstitucion';
               
                app.cargando=true;
                var fill={
                    'id':id
                }
                await axios.post(urlKeeps,fill).then(response=>{
                   
                    app.cargando=false;
                    if(response.data.status==200){
                       this.formCrear=response.data.datos;
                       $("#filtro_institucion").val(this.formCrear.institucion_id).change();
                       $("#ministro_usuario_id").val(this.formCrear.ministro_usuario_id).change();
                    }else{
                        swal("Cancelado!", "Error al grabar...", "error");
                    }
                }).catch(error => {
                   
                    app.cargando=false;
                    swal("Cancelado!", "Error al grabar...", "error");
                });
            },
            async guardarGabinete() {
                let error="";
                if(this.formGabinete.nombre==''||this.formGabinete.nombre==null) error+="\n Debe digitar los nombre del gabinete";
                if(this.formGabinete.siglas==''||this.formGabinete.siglas==null) error+="\n Debe escribir las siglas del gabinete";
                
                if (error.length > 0) {
                    swal(" ", error, "error");
                    return false;
                }
                var urlKeeps='guardarGabinete';
               
                app.cargando=true;
                await axios.post(urlKeeps,this.formGabinete).then(response=>{
                    if(response.data.status==200){
                        this.limpiarForm();
                        $("#cerrar_modal_gabinete").click();
                       
                        app.cargando=false;
                        alertToastSuccess("Grabado Exitosamente",3500);
                        datatableCargarGabinete();
                    }else{
                       
                        app.cargando=false;
                        swal("Cancelado!", "Error al grabar...", "error");
                    }
                }).catch(error => {
                   
                    app.cargando=false;
                    swal("Cancelado!", "Error al grabar...", "error");
                });
            },
            async guardarInstitucion() {
                let error="";
                if(this.formCrear.nombre==''||this.formCrear.nombre==null) error+="\n Debe digitar los nombre de la institucion";
                if(this.formCrear.institucion_id==''||this.formCrear.institucion_id==null) error+="\n Debe seleccionar una institución";
                if(this.formCrear.ministro_usuario_id==''||this.formCrear.ministro_usuario_id==null) error+="\n Debe seleccionar un usuario Ministro";
                if(this.formCrear.siglas==''||this.formCrear.siglas==null) error+="\n Debe escribir las siglas de la institucion";
                
                if (error.length > 0) {
                    swal(" ", error, "error");
                    return false;
                }
                var urlKeeps='guardarInstitucion';
               
                app.cargando=true;
                await axios.post(urlKeeps,this.formCrear).then(response=>{
                    if(response.data.status==200){
                        this.limpiarForm();
                        $("#cerrar_modal_institucion").click();
                       
                        app.cargando=false;
                        alertToastSuccess("Grabado Exitosamente",3500);
                        datatableCargar();
                    }else{
                        app.cargando=false;
                        swal("Cancelado!", "Error al grabar...", "error");
                    }
                }).catch(error => {
                    app.cargando=false;
                    swal("Cancelado!", "Error al grabar...", "error");
                });
            },
            async eliminarGabinete(id){//eliminar un registro 
                let result = await swal(
                    {
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
                        var urlKeeps='eliminarInstitucion'; //setea la ruta del controlador
                        var fill={'id':id}//busca el id y lo almacena en la var 'id'
                        app.cargando=true; //mientras busca la data, el indicador se activa "cargando"
                        axios.post(urlKeeps,fill) //elimino registro
                        .then(response=>{
                            app.cargando=false; //desaparece indicador de cargando
                            datatableCargarGabinete();
                            alertToastSuccess("Eliminado Exitosamente",3500);
                        })
                        .catch(error => {
                            app.cargando=false; //desaparece indicador de cargando
                            swal("Cancelado!", "Error al grabar...", "error");
                        });
                    } 
                    else {
                        swal("Cancelado!", "No se registraron cambios...", "error");
                        return false;
                    }
                }
                )
            },
            async eliminarInstitucion(id){//eliminar un registro 
                let result = await swal(
                    {
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
                        var urlKeeps='eliminarInstitucion'; //setea la ruta del controlador
                        var fill={'id':id}//busca el id y lo almacena en la var 'id'
                        app.cargando=true; //mientras busca la data, el indicador se activa "cargando"
                        axios.post(urlKeeps,fill) //elimino registro
                        .then(response=>{
                            app.cargando=false; //desaparece indicador de cargando
                            datatableCargar();
                            alertToastSuccess("Eliminado Exitosamente",3500);
                        })
                        .catch(error => {
                            app.cargando=false; //desaparece indicador de cargando
                            swal("Cancelado!", "Error al grabar...", "error");
                        });
                    } 
                    else {
                        swal("Cancelado!", "No se registraron cambios...", "error");
                        return false;
                    }
                }
                )
            },
        
          
        },

});
