<!--MODAL PARA REPORTE DE MAPA DE CALOR POR COMPROMISO INDIVIDUAL-->
<div class="modal fade" id="modal-MAPA_CALOR1">
    <div class='modal-dialog modal-xs' style="min-width: 20%!important;">
        <div class="modal-content">
            <div class="modal-header" style="text-align:center">
                <label style="font-size:20px;text-align:center">Reporte por ubicación</label>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Fecha inicio del Compromisos:</label>
                        <div class="input-group">
                            <input type="date" class="form-control form-control-sm" id="fecha_inicio_ubicacion"
                            value="{{$fecha_inicio}}">-
                            <input type="date" class="form-control form-control-sm" id="fecha_fin_ubicacion"
                            value="<?php echo date('Y-12-31');?>" >
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Ubicación</label>
                        {!! Form::select('ubicacion', $ubicacion_filtro, null, [
                            'id' => 'filtro_ubicacion',
                            'class' => 'form-control select2',
                            'multiple'=>'multiple',
                            'placeholder' => 'TODAS LAS UBICACIONES',
                        ]) !!}
                    </div>
                    <div class="col-md-12">
                        <label>Estado de Gestión</label>
                        {!! Form::select('gestion', $gestion_filtro, null, [
                            'id' => 'filtro_gestion',
                            'class' => 'form-control select2',
                            'placeholder' => 'TODOS LOS ESTADOS',
                            'multiple'=>'multiple'
                        ]) !!}
                    </div>
                    <div class="col-md-12">
                        <label>Gabinete Sectorial:</label>
                        <div class="input-group">
                            <select class="form-control form-control-sm select2" id="filtro_gabinete_ind"
                                name="filtro_gabinete_ind" multiple="multiple">
                                <option value="" selected>TODOS</option>
                                <option v-for="value in arrayGabinete" :value="value.id"
                                    v-text="value.descripcion">
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Instituci&oacute;n Responsable:</label>
                        <div class="input-group">
                            <select class="form-control form-control-sm select2" id="filtro_institucion_ind"
                                name="filtro_institucion_ind" multiple="multiple">
                                <option value="" selected>TODOS</option>
                                <option v-for="value in arrayInstitucion" :value="value.id"
                                    v-text="value.descripcion">
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label>Compromisos</label>
                        <div class="input-group">
                            <select id="filtro_compromiso_individual" class="select2 form-control" multiple
                                :disabled="habilitarCompromiso" v-model="filtro_compromiso_individual">
                                <option v-for="value in arrayCompromiso" :value="value.id"
                                    v-text="value.nombre_compromiso">
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="btn btn-primary" disabled v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Cargando</button>

                <button class="btn btn-default" type="button" onclick="generarReporteMapa();"
                    v-on:click="reporteDinamico_tc();">
                    <span class="fa fa-search"></span>&nbsp;Buscar
                </button>
                <button class="btn btn-default cerrarmodal" data-dismiss="modal"
                    v-on:click='limpiarMapaCompromisoIndividual()' id="cerrar_reporte_individual"
                    v-show="!cargando"><b><i class="fa fa-times"></i></b>&nbsp;Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
