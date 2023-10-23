<!--MODAL PARA REPORTE DE COMPROMISOS POR MINISTERIO-->
<div class="modal fade" id="modal-COMPROMISOS_MINISTERIO">
    <div class='modal-dialog modal-xs' style="min-width: 20%!important;">
        <div class="modal-content">
            <div class="modal-header" style="text-align:center">
                <label style="font-size:20px;text-align:center">Reporte Compromisos Ministerio</label>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Fecha inicio del compromiso:</label>
                        <div class="input-group">
                            <input type="date" value="{{$fecha_inicio}}"  class="form-control form-control-sm" id="fecha_inicio_ministerio">
                           -  <input type="date"  value="<?php echo date('Y-12-31');?>" class="form-control form-control-sm" id="fecha_fin_ministerio">

                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Institución</label>
                        {!! Form::select('institucion', $cqlInstitucion, null, [
                            'id' => 'filtro_inst',
                            'class' => 'form-control select2',
                            'placeholder' => 'TODAS LAS INSTITUCIONES',
                            'multiple'=>'multiple'
                        ]) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="btn btn-primary" disabled v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Cargando</button>
                <span class="input-group-btn">&nbsp;
                    <button class="btn btn-default" type="button" v-on:click="exportarExcelMinisterio()">
                        <img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel
                    </button>

                </span>
                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal"
                    v-on:click='limpiarCompromisoMinisterio()' id="cerrar_reporte_ministerio"
                    v-show="!cargando"><b><i class="fa fa-times"></i></b>&nbsp;Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
