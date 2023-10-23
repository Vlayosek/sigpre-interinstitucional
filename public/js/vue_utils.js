var app_utils = new Vue({
    el: '#main_paginar',
    data() {
        return {
            pagination:{
                'total'         :0,
                'current_page'  :0,
                'per_page'     :0,
                'last_page'     :0,
                'from'       :0,
                'to'          :0,
            },
            offset: 4,
        }
    },
    created: function () {
       // this.consultaEstados();
       // this.mes=document.querySelector("#meseSet").value;
    },
    computed:{
        isActived:function(){
            return this.pagination.current_page;
        },
        pagesNumber:function(){
            if(!this.pagination.to){
				return [];
			}

			var from = this.pagination.current_page - this.offset; 
			if(from < 1){
				from = 1;
			}

			var to = from + (this.offset * 2); 
			if(to >= this.pagination.last_page){
				to = this.pagination.last_page;
			}

			var pagesArray = [];
			while(from <= to){
				pagesArray.push(from);
				from++;
			}
			return pagesArray;
        }
    },
    methods: {
  
        descargarExcel: function(nombre,url) {
            swal({
                title: "Loading...",
                text: "Please wait",
                icon: "/images/loading.gif",
                button: false,
                closeOnClickOutside: false,
                closeOnEsc: false,
                showConfirmButton: false
        
              });
            axios
            .get(
                url, {
                    responseType: 'blob' //Change the responseType to blob
                }
            )
            .then(resp => {
                if (resp.status == 200) {
                    $(".confirm").click();
                            let blob = new Blob([resp.data], { type: "application/vnd.ms-excel" });
                            let link = URL.createObjectURL(blob);
                            let a = document.createElement("a");
                            a.download = nombre+".xlsx";
                            a.href = link;
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                }
                else{
                    $(".confirm").click();
                    alertToast("error al generar excel",2500);
                }
            })
            .catch(error => {
                $(".confirm").click();
                alertToast("error al generar excel",2500);
        
            })
            .finally(() => { 
               $(".confirm").click();
            });
		},
    },
})