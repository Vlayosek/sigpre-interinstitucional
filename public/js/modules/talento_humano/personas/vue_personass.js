var app = new Vue({
    el: '#main',
    data() {
        return {
            editar:false,
            pendientes:0,
            registrados:0,
            finalizadas:0,
            formCrear:{
                'pva_id':'0',
                'pva_fecha_desde':'',
                'pva_hora_desde':'',
                'pva_fecha_hasta':'',
                'pva_hora_hasta':'',
                'pva_motivo':'asdsad',
                'tpe_id':'',
                'tso_id':'',
            },
            mostrarArchivo:false
        }
    },
    created: function () {
        //this.getKeeps();
    },
    methods: {
            descargarArchivo:function(){
                let descripcion=this.formCrear.pva_archivo;
                let nombre=this.formCrear.pva_nombre_archivo;
                let direccion='';
                if(this.formCrear.migrado==true){
                    direccion=document.querySelector("#direccionDocumentos").value;
                    direccion=direccion+'/PORTAL/PERMISOS/'+descripcion;
                }else{
                    nombre=descripcion;
                    direccion=document.querySelector("#direccionDocumentosPortal").value;
                    direccion=direccion+descripcion;
                }
                downloadURI(direccion,nombre)
            },
            limpiarForm:function(){
         
               
                this.formCrear.pva_id='0';
                this.formCrear.pva_fecha_desde='';
                this.formCrear.pva_hora_desde='';
                this.formCrear.pva_fecha_hasta='';
                this.formCrear.pva_hora_hasta='';
                this.formCrear.pva_motivo='';
                this.formCrear.tpe_id='';
                this.formCrear.tso_id='';
                this.mostrarArchivo=false;
                $('#customFile').val(null);
            },
            async verPDF(id){
                alert(1);
             /*   this.editar=true;
                var urlKeeps='editarPermiso';
                var fill={
                    'id':id
                }
                await axios.post(urlKeeps,fill).then(response=>{
                    this.formCrear=response.data.datos;
                    this.formCrear.pva_fecha_hasta=this.formCrear.pva_fecha_hasta!=null?this.formCrear.pva_fecha_hasta:this.formCrear.pva_fecha_desde;
                    this.formCrear.pva_hora_hasta=this.formCrear.pva_hora_hasta!=null?this.formCrear.pva_hora_hasta:'00:00:00';
                    this.formCrear.pva_hora_desde=this.formCrear.pva_hora_desde!=null?this.formCrear.pva_hora_desde:'00:00:00';
                    this.mostrarArchivo=this.formCrear.archivo!=null?true:false;
                }).catch(error => {
                    alertToast("Error, recargue la página",3500);
                });*/
            },
            async editarPermiso(id){
                this.editar=true;
                var urlKeeps='editarPermiso';
                var fill={
                    'id':id
                }
                await axios.post(urlKeeps,fill).then(response=>{
                    this.limpiarForm();
                    this.formCrear=response.data.datos;
                    this.formCrear.pva_fecha_hasta=this.formCrear.pva_fecha_hasta!=null?this.formCrear.pva_fecha_hasta:this.formCrear.pva_fecha_desde;
                    this.formCrear.pva_hora_hasta=this.formCrear.pva_hora_hasta!=null?this.formCrear.pva_hora_hasta:'00:00:00';
                    this.formCrear.pva_hora_desde=this.formCrear.pva_hora_desde!=null?this.formCrear.pva_hora_desde:'00:00:00';
                    this.mostrarArchivo=this.formCrear.pva_archivo!=null?true:false;
                }).catch(error => {
                    alertToast("Error, recargue la página",3500);
                });
            },
            async eliminarPermiso(id){
                let result = await swal({
                    title: "Estás seguro de realizar esta accion\n ",
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
                        this.editar=true;
                        var urlKeeps='eliminarPermiso';
                        var fill={
                            'id':id
                        }
                        axios.post(urlKeeps,fill).then(response=>{
                                alertToast(response.data.datos,3500);
                                datatableCargar();
                        }).catch(error => {
                            alertToast("Error, recargue la página",3500);
                        });
                    }else
                            swal("Cancelado!", "No se registraron cambios...", "error");
                        
            });

            },
            
            async guardarRegistro() {
            let fill=this.formCrear;
            let result = await swal({
                title: "Estás seguro de realizar esta accion\n ",
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
                        var urlKeeps = 'guardarRegistro';
                        var data = new FormData();

                        $('#customFile').each(function (a, array) {
                            if (array.files.length > 0) {
                                $.each(array.files, function (k, file) {
                                    data.append('archivos[' + k + ']', file);
                                })
                            } else
                            data.append('archivos', null);
                        });
                        for (const property in fill) {
                            let atributo=`${property}`;
                            let valor=`${fill[property]}`;
                            data.append(atributo,valor);
                          }
                       
                        axios.post(urlKeeps, data,
                                { headers : 
                                    {'content-type': 'multipart/form-data'}
                                }
                        )
                        .then(response => {
                            if(response.data.status=="200"){
                                datatableCargar();
                                alertToastSuccess(response.data.message,3500);
                            }else{
                                alertToast(response.data.message,3500);
                            }
                        }).catch(error => {
                            alertToast("Error, recargue la página",3500);
                        });
                    } else {
                        swal("Cancelado!", "No se registraron cambios...", "error");
                        return false;
                    }
                });
        },
      
    }
})