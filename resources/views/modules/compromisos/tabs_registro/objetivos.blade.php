<div class="tab-pane" id="objetivos">
    <div class="container-fluid">
        <div class="row">
            <div :class="arregloObjetivos != 0 ? 'hidden' : 'col-md-3'">
                @if ($corresponsable)
                    <div class="col-md-12 hidden">
                    @else
                        <div class="col-md-12">
                @endif
                <div class="row">
                    <div class="col-md-12 hidden">
                        <label>Tipo de Objetivo:</label>
                        {!! Form::select('tipo_objetivo_id', $tipos_objetivos, null, [
                            'v-model' => 'formObjetivo.tipo_objetivo_id',
                            'placeholder' => 'SELECCIONE UNA OPCION',
                            'class' => 'form-control ',
                            'id' => 'tipo_objetivo_id',
                            ':disabled' => 'deshabilitarPorDesbloqueo',
                        ]) !!}
                    </div>
                    <div class="col-md-12">
                        <input type="hidden" value="0"
                            id="idObjetivo"
                            v-model="formObjetivo.idObjetivo">
                        <label>Objetivo:</label>
                        <textarea class="form-control" id="objetivo" v-model="formObjetivo.objetivo" autocomplete="off"></textarea>
                    </div>
                    <div class="col-md-12">
                        <label>Descripción de la Meta:</label>
                        <textarea class="form-control" v-model="formObjetivo.descripcion_meta" autocomplete="off" id="descripcion_meta"></textarea>
                    </div>
                    <div class="col-md-12 padding-colMD">
                        <label>Meta Final:</label>
                        <input type="text"
                            class="form-control metafinal mayuscula"
                            v-model="formObjetivo.meta">
                    </div>
                    <div class="col-md-12">
                        <label>Temporalidad:</label>
                        {!! Form::select('temporalidad_id', $temporalidades, null, [
                            'v-model' => 'formObjetivo.temporalidad_id',
                            'placeholder' => 'SELECCIONE UNA OPCION',
                            'class' => 'form-control ',
                            'id' => 'temporalidad_id',
                        ]) !!}
                    </div>
                    <div class="col-md-12">
                        <label for="name"
                            class="control-label col-sm-12">Fecha
                            inicio:</label>
                        <input autocomplete="off" class="form-control"
                            type="date"
                            v-model="formObjetivo.fecha_inicio_objetivo"
                            id="fecha_inicio_objetivo"
                            :disabled="disableDatePicker" />

                    </div>

                    <div class="col-md-12">
                        <label for="name"
                            class="control-label col-sm-12">Fecha
                            Fin:</label>
                        <input autocomplete="off" class="form-control"
                            type="date"
                            v-model="formObjetivo.fecha_fin_objetivo"
                            id="fecha_fin_objetivo" />
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" name="upload"
                                    class="btn btn-block btn-default"
                                    v-on:click="limpiarFormularios()">
                                    Limpiar</button>
                            </div>

                            <div class="col-md-6">
                                <button type="button" name="upload"
                                    class="btn btn-block btn-info"
                                    v-on:click="guardarObjetivo()">
                                    <i class="fa fa-save"></i>
                                    &nbsp;Guardar</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <button id="BotonDatatableObjetivo"
            onclick="datatableCargarObjetivos()" class="hidden"></button>
        @if ($corresponsable)
            <div class="col-md-12">
            @else
                <div
                    :class="arregloObjetivos != 0 ? 'col-md-12' : 'col-md-9'">
        @endif
        <div class="table table-responsive tablaConsulta">
            <div class="modal-content" id="motivo_rechazo"
                v-show="visibleRechazar">
                <div class="modal-header">
                    <!--<h5 class="modal-title" id="myModalLabel">
                                Objetivo Rechazado
                            </h5>   -->
                </div>
                <div class="modal-body">
                    <input type="hidden" id="idObj">
                    <label>Motivo de rechazo:<span>*</span></label>
                    <textarea id='text_rechazo' class='form-control' maxlength="200"></textarea>
                    </textarea>
                </div>

                <div class="modal-footer justify-content-end">
                    <button class="btn btn-primary btn-sm" disabled
                        v-show="cargando"><img
                            src="{{ url('/spinner.gif') }}">&nbsp;Guardando</button>

                    <button class="btn btn-primary" data-dismiss="modal"
                        v-on:click='guardarMotivoRechazo()'>
                        <i class="fa fa-save"></i>&nbsp;Guardar
                    </button>

                    <button class="btn btn-default btn-sm"
                        v-on:click="cerrarRechazo()" id="cerrar_registro"
                        v-show="!cargando"><b><i
                                class="fa fa-times"></i></b>
                        Cerrar</button>
                </div>
            </div>
            <br>
            <table class="table table-bordered table-striped"
                id="dtmenuObjetivos" style="width:100%!important">
                <thead>
                    <th>No</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Meta</th>
                    <th>Temporalidad</th>
                    <th>Objetivo</th>
                    <th>Descripcion</th>
                    <th width="5%"></th>
                </thead>
                <tbody id="tbobymenuObjetivos" class="menu-pen">

                </tbody>
            </table>
        </div>
    </div>
</div>
