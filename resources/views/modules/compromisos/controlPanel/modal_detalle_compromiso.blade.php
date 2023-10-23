<!--MODAL PARA DETALLE DE COMPROMISOS-->
<div class="modal fade" id="modal-COMPROMISOS_DETALLE">
    <div class="modal-dialog modal-dialog-1  modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="text-align:center">
                <label style="font-size:20px;">Detalles de Provincias por compromisos</label>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class=" table " >

                        <table class="table table-bordered table-striped"
                            id="dtUbicacionCompromisosDetalle" style="width:100%!important">
                            <thead>

                            </thead>
                            <tbody id="tbobymenu" class="menu-pen">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">

                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal" v-on:click='limpiarReporteCC()'
                    id="cerrar_detalle_compromisos" v-show="!cargando"><b><i class="fa fa-times"></i></b>&nbsp;Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
