<!--MODAL PARA REPORTE DE COMPROMISOS POR GABINETE-->
<div class="modal fade" id="modal-COMPROMISOS_GABINETE">
    <div class='modal-dialog modal-xs' style="min-width: 20%!important;">
        <div class="modal-content">
            <div class="modal-header" style="text-align:center">
                <label style="font-size:20px;text-align:center">Reporte Compromisos Gabinete</label>
            </div>
            @include('modules.compromisos.controlPanel.modal_estandar_gabinete')

            <div class="modal-footer justify-content-end">
                <button class="btn btn-primary" disabled v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Cargando</button>
                <span class="input-group-btn">&nbsp;
                    <button class="btn btn-default" type="button" v-on:click="exportarExcelGabinete()">
                        <img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel
                    </button>

                </span>
                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal"
                    v-on:click='limpiarCompromisoGabinete()' id="cerrar_reporte_gabinete" v-show="!cargando"><b><i
                            class="fa fa-times"></i></b>&nbsp;Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
