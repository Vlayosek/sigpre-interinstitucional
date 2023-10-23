var app = new Vue({
    el: '#main',
    data() {
        return {
            editar:false,
            formCrear:{
                'per_id':'0',
            },
        }
    },
    created: function () {
        //this.getKeeps();
    },
    methods: {
           
            limpiarForm:function(){
                this.editar=false;
                document.querySelector("#link-inicial").click();
                this.formCrear.per_id='0';
              
            },
            async verPDF(id){
                alert(1);
            },
            async editarPersona(id){
                var urlKeeps='editarPersona';
                var fill={
                    'id':id
                }
                await axios.post(urlKeeps,fill).then(response=>{
                    this.limpiarForm();
                    this.editar=true;
                    this.formCrear=response.data.datos;
                    datatableHistorial();
                }).catch(error => {
                    alertToast("Error, recargue la página",3500);
                });
            },
            async desactivarRegistro(id){
               
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
                        var urlKeeps='desactivarRegistro';
                        var fill={
                            'id':id
                        }
                        return false;
                      /*  axios.post(urlKeeps,fill).then(response=>{
                                alertToast(response.data.datos,3500);
                                datatableCargar();
                        }).catch(error => {
                            alertToast("Error, recargue la página",3500);
                        });*/
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