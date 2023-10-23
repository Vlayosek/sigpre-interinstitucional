<!--MODAL PARA REPORTE POR FILTRO-->
<div class="modal fade" id="modal-COMPROMISOS_CUMPLIDOS">
    <div class='modal-dialog modal-xs' style="min-width: 20%!important;">
        <div class="modal-content">
            <div class="modal-header" style="text-align:center">
                <label style="font-size:20px;">Reporte de compromisos cumplidos</label>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Fecha inicio del Compromisos:</label>
                        <div class="input-group">
                            <input type="date" class="form-control form-control-sm" id="fecha_inicio_cumplidos"
                            value="{{$fecha_inicio}}">-
                            <input type="date" class="form-control form-control-sm" id="fecha_fin_cumplidos"
                            value="<?php echo date('Y-12-31');?>" >
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label>Seleccione Gabinete:</label>
                        <div class="input-group">
                            <select class="form-control form-control-sm select2" id="filtro_gabinete"
                                name="filtro_gabinete" multiple="multiple">
                                <option value="" selected>TODOS</option>
                                <option v-for="value in arrayGabinete" :value="value.id"
                                    v-text="value.descripcion">
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Seleccione Instituci&oacute;n Responsable:</label>
                        <div class="input-group">
                            <select class="form-control form-control-sm select2" id="filtro_institucion"
                                name="filtro_institucion" multiple="multiple">
                                <option value="" selected>TODOS</option>
                                <option v-for="value in arrayInstitucion" :value="value.id"
                                    v-text="value.descripcion">
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <span class="input-group-btn">&nbsp;
                    <button class="btn btn-primary" disabled v-show="cargando"><img
                            src="{{ url('/spinner.gif') }}">&nbsp;Cargando</button>
                    <button class="btn btn-default" type="button" v-on:click="exportarExcelCumplidos()">
                        <img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel
                    </button>
                </span>
                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal" v-on:click='limpiarReporteCC()'
                    id="cerrar_reporte_cumplido" v-show="!cargando"><b><i class="fa fa-times"></i></b>&nbsp;Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
