<!--MODAL PARA REPORTE EJECUTIVO-->
<div class="modal fade" id="modal-EJECUTIVO">
    <div class='modal-dialog modal-xs' style="min-width: 20%!important;">
        <div class="modal-content">
            <div class="modal-header" style="text-align:center">
                <label style="font-size:20px;text-align:center">Reporte Ejecutivo</label>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Fecha inicio del compromiso:</label>
                        <div class="input-group">
                            <input type="date" value="{{ $fecha_inicio }}" class="form-control form-control-sm"
                                id="fecha_inicio_ejecutivo">
                            - <input type="date" value="<?php echo date('Y-12-31'); ?>" class="form-control form-control-sm"
                                id="fecha_fin_ejecutivo">

                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Instituci&oacute;n Responsable:</label>
                        <div class="input-group">
                            <select class="form-control form-control-sm select2" multiple="multiple" id="filtro_institucion_ejecutivo">
                                <option value="" selected>TODOS</option>
                                <option v-for="value in arrayInstitucion" :value="value.id"
                                    v-text="value.descripcion">
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Compromisos:</label>
                        <div class="input-group">
                            <select class="form-control form-control-sm select2" id="filtro_compromisos" v-model="filtro_compromisos"
                                multiple="multiple">
                                <option value="" selected>TODOS</option>
                                <option v-for="(value,index) in arrayCompromisosInstitucion" :value="index"
                                    v-text="value">
                                </option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="btn btn-primary" disabled v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Cargando</button>
                <button class="btn btn-success btn-sm" style="background:#17A2B7;border:0px"
                    v-on:click="exportarExcelEjecutivo()">
                    <b><img src="/images/icons/excel.png" width="20px" heigh="15px"></b>&nbsp;Exportar Excel
                </button>
                <button class="btn btn-success btn-sm" style="background:#17A2B7;border:0px"
                    v-on:click="exportarExcelEjecutivo()">
                    <b><img src="/images/icons/excel.png" width="20px" heigh="15px"></b>&nbsp;Visualizar
                </button>
                <button class="btn btn-default" type="button" onclick="generarReporteEjecutivo();"
                    v-on:click="reporteEjecutivo();">
                    <span class="fa fa-search"></span>&nbsp;Visualizarr
                </button>
                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal"
                    v-on:click='limpiarReporteEjecutivo()' id="cerrar_reporte_ejecutivo" v-show="!cargando"><b><i
                            class="fa fa-times"></i></b>&nbsp;Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
