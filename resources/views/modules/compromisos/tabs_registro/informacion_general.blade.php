<div class="tab-pane active" id="informacionGeneral">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8"
                style="
            padding: 20px;">
                <div class="row">
                    <div class="col-md-3">
                        <label>Tipo de Compromiso</label>
                        {!! Form::select('tipo_compromiso_id', $tipos, null, [
                            'v-model' => 'formCrear.tipo_compromiso_id',
                            'placeholder' => 'SELECCIONE UNA OPCION',
                            'class' => 'form-control ',
                            'id' => 'tipo_compromiso_id',
                            ':disabled' => 'rolMinistro==1',
                        ]) !!}
                    </div>
                    <div class="col-md-3">
                        <label>Origenes</label>
                        {!! Form::select('origen_id', $origenes, null, [
                            'v-model' => 'formCrear.origen_id',
                            'placeholder' => 'SELECCIONE UNA OPCION',
                            'class' => 'form-control ',
                            'id' => 'origen_id',
                            ':disabled' => 'rolMinistro==1',
                        ]) !!}

                    </div>
                    <div class="col-md-3">
                        <label for="name"
                            class="control-label col-sm-12">Fecha
                            inicio:</label>

                        <input autocomplete="off" class="form-control"
                            type="date" id="fecha_inicio"
                            v-model="formCrear.fecha_inicio_compromiso"
                            :disabled="rolMinistro == 1" />
                    </div>

                    <div class="col-md-3">
                        <label for="name"
                            class="control-label col-sm-12">Fecha
                            Fin:</label>
                        <input autocomplete="off" class="form-control"
                            type="date" id="fecha_fin"
                            v-model="formCrear.fecha_fin_compromiso"
                            :disabled="rolMinistro == 1" />
                    </div>
                    <div class="col-md-6">
                        <label>Nombre del Compromiso</label>
                        <textarea class="form-control-t" autocomplete="off" id="nombre_compromiso" v-model="formCrear.nombre_compromiso"
                            :disabled="rolMinistro == 1"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label>Detalle del Compromiso</label>
                        <textarea class="form-control-t" autocomplete="off" id="detalle_compromiso" v-model="formCrear.detalle_compromiso"
                            :disabled="rolMinistro == 1"></textarea>
                    </div>
                    <div :class="rolMinistro == 1?'col-md-12':'col-md-6'">
                        <label>Avances</label>
                        <textarea class="form-control-t" autocomplete="off" id="avance_compromiso" v-model="formCrear.avance_compromiso"  :disabled="rolMinistro == 1" maxlength="500"></textarea>
                    </div>
                    <div class="col-md-6" v-show="rolMinistro==0">
                        <label>Notas</label>
                        <textarea class="form-control-t" autocomplete="off" id="notas_compromiso" v-model="formCrear.notas_compromiso"  :disabled="rolMinistro == 1"></textarea>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="name"
                                    class="control-label col-sm-12">Fecha
                                    Reporte:</label>
                                <input autocomplete="off" class="form-control"
                                    type="date" id="fecha_reporte"
                                    v-model="formCrear.fecha_reporte"
                                    :disabled="rolMinistro == 1" />
                            </div>
                            <div class="col-md-3">
                                <label>Estado de Gesti&oacute;n</label>
                                {!! Form::select('estado_porcentaje_id', $estados_porcentaje, null, [
                                    'v-model' => 'formCrear.estado_porcentaje_id',
                                    'placeholder' => 'SELECCIONE UNA OPCION',
                                    'class' => 'form-control  ',
                                    'id' => 'estado_porcentaje_id',
                                    ':disabled' => 'rolMinistro==1',
                                ]) !!}

                            </div>
                            <div class="col-md-3">
                                <label>Estado del Compromiso</label>
                                {!! Form::select('estado_id', $estados, null, [
                                    'v-model' => 'formCrear.estado_id',
                                    'placeholder' => 'SELECCIONE UNA OPCION',
                                    'class' => 'form-control ',
                                    'id' => 'estado_id',
                                    ':disabled' => 'rolMinistro==1',
                                ]) !!}

                            </div>

                            <div class="col-md-3 hidden">
                                <label>Cumplimiento</label>
                                <input type="text"
                                    class="form-control numero"
                                    autocomplete="off" id="cumplimiento"
                                    v-model="formCrear.cumplimiento"
                                    disabled>
                            </div>
                            <div class="col-md-3">
                                <label>Avance del Compromiso</label>
                                <input type="text"
                                    class="form-control numero"
                                    autocomplete="off" id="avance"
                                    v-model="formCrear.avance"
                                    v-text="formCrear.avance">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4"
                style="    background: #17a2b845;
            padding: 20px;">
                <input type="hidden" id="id" value="0"
                    v-model="formCrear.id">
                <div class="col-md-12">
                    <label>Monitor</label>
                    <select id="monitor_id" v-model='formCrear.monitor_id'
                        class='form-control '
                        v-on:change="changeInstitucion(this)"
                        :disabled="rolMinistro == 1">
                        <option value="" selected>SELECCIONE UNA
                            OPCION
                        </option>
                        @foreach ($monitores as $key => $item)
                            <option value="{{ $key }}">
                                {{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Instituci&oacute;n</label>
                            <select id="institucion_id"
                                v-model='formCrear.institucion_id'
                                class='form-control select2 selector_gestion'
                                v-on:change="changeInstitucion(this)"
                                :disabled="rolMinistro == 1">

                            </select>

                        </div>
                        <div class="col-md-12">
                            <label>Responsables</label>
                            <select id="responsable_id"
                                v-model='formCrear.responsable_id'
                                class='form-control select2 selector_gestion'
                                v-on:change="changeInstitucion(this)"
                                :disabled="rolMinistro == 1">
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Delegado</label>
                            {!! Form::select('delegado_id', $delegados, null, [
                                'v-model' => 'formCrear.delegado_id',
                                'placeholder' => '--',
                                'class' => 'form-control ',
                                'id' => 'delegado_id',
                                'disabled' => 'disabled',
                            ]) !!}

                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Gabinete Sectorial</label>
                            {!! Form::select('gabinete_id', $gabinete, null, [
                                'v-model' => 'formCrear.gabinete_id',
                                'placeholder' => '--',
                                'class' => 'form-control ',
                                'id' => 'gabinete_id',
                                'disabled' => 'disabled',
                            ]) !!}

                        </div>
                    </div>
                </div>

                <div class="col-md-12">

                    <div class="col-md-12">
                        <label>Instituciones Corresponsables</label>
                        {!! Form::select('instituciones_corresponsables[]', [], null, [
                            'v-model' => 'formCrear.instituciones_corresponsables',
                            'class' => 'form-control select2 selector_gestion',
                            'multiple' => 'multiple',
                            'required' => '',
                            'id' => 'instituciones_corresponsables',
                            ':disabled' => 'rolMinistro==1',
                        ]) !!}

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
