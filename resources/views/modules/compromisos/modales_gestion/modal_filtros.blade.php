<div class="modal fade" id="modal-excel-monitor">
    <div class="modal-dialog modal-dialog-1  modal-xl">
        <div class="modal-content modal-content_">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">
                    Filtrado de Datos y exportación en Excel - MONITOR
                </h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="row">

                            <div class="col-md-12">
                                <label>Gabinete responsable:</label>
                                {!! Form::select('gabinete_id_exportar_monitor', $gabinete, null, [
                                    'placeholder' => 'TODOS LOS GABINETES',
                                    'class' => 'form-control select2 exportar_monitor selectores_exportar_monitor',
                                    'multiple' => 'multiple',
                                    'id' => 'gabinete_id_exportar_monitor',
                                ]) !!}
                                <label>Institución responsable:</label>
                                {!! Form::select('institucion_id_exportar_monitor', [], null, [
                                    'placeholder' => 'TODOS LAS INSTITUCIONES',
                                    'class' => 'form-control select2 exportar_monitor selectores_exportar_monitor',
                                    'multiple' => 'multiple',
                                    'id' => 'institucion_id_exportar_monitor',
                                ]) !!}
                            </div>
                            <div class="col-md-12">
                                <label>Gabinete Corresponsable:</label>
                                {!! Form::select('gabinete_id_corresponsable_exportar_monitor', $gabinete, null, [
                                    'placeholder' => 'TODOS LOS GABINETES CORRESPONSABLES',
                                    'class' => 'form-control select2 exportar_monitor selectores_exportar_monitor',
                                    'multiple' => 'multiple',
                                    'id' => 'gabinete_id_corresponsable_exportar_monitor',
                                ]) !!}
                                <label>Institución corresponsable:</label>
                                {!! Form::select('institucion_id_corresponsable_exportar_monitor', [], null, [
                                    'placeholder' => 'TODOS LAS INSTITUCIONES CORRESPONSABLES',
                                    'class' => 'form-control select2 exportar_monitor selectores_exportar_monitor',
                                    'multiple' => 'multiple',
                                    'id' => 'institucion_id_corresponsable_exportar_monitor',
                                ]) !!}
                            </div>
                            <div class="col-md-12">
                                <label> <input type="checkbox" id="habilitarFechaInicio" v-model="habilitarFechaInicio"
                                        :checked="habilitarFechaInicio">
                                    Habilitar Interválos de Fecha de Inicio del compromiso
                                </label>
                            </div>
                            <div class="col-md-12">
                                <label>fecha inicio:</label>
                                <div class="input-group">
                                    <input type="date" class="form-control exportar_monitor"
                                        id="fecha_inicio_exportar_monitor" value="{{ $fecha_inicio }}"
                                        :disabled="!habilitarFechaInicio">
                                    <input type="date" class="form-control exportar_monitor"
                                        id="fecha_fin_exportar_monitor" value="<?php echo date('Y-12-31'); ?>"
                                        :disabled="!habilitarFechaInicio">

                                </div>
                            </div>
                            <div class="col-md-12">
                                <label> <input type="checkbox" id="habilitarFechaFin" v-model="habilitarFechaFin"
                                        :checked="habilitarFechaFin">
                                    Habilitar Interválos de Fecha Fin del Compromiso
                                </label>
                            </div>
                            <div class="col-md-12">
                                <label>fecha fin:</label>
                                <div class="input-group">
                                    <input type="date" class="form-control exportar_monitor"
                                        id="fecha_inicio_fin_exportar_monitor" value="{{ $fecha_inicio }}"
                                        :disabled="!habilitarFechaFin">
                                    <input type="date" class="form-control exportar_monitor"
                                        id="fecha_fin_fin_exportar_monitor" value="<?php echo date('Y-12-31'); ?>"
                                        :disabled="!habilitarFechaFin">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">

                            <div class="col-md-12">
                                <label>Tipo:</label>
                                {!! Form::select('tipo_id_exportar_monitor', $tipos, null, [
                                    'placeholder' => 'TODOS LOS TIPOS',
                                    'class' => 'form-control select2 exportar_monitor selectores_exportar_monitor',
                                    'multiple' => 'multiple',
                                    'id' => 'tipo_id_exportar_monitor',
                                ]) !!}
                            </div>
                            <div class="col-md-12">
                                <label>Estado de Gestión:</label>
                                {!! Form::select('estado_id_exportar_monitor', $estados, null, [
                                    'placeholder' => 'TODOS LOS ESTADOS DE GESTIÓN',
                                    'class' => 'form-control select2 exportar_monitor selectores_exportar_monitor',
                                    'multiple' => 'multiple',
                                    'id' => 'estado_id_exportar_monitor',
                                ]) !!}
                            </div>
                            <div class="col-md-12">
                                <label>Estado del Compromiso:</label>
                                {!! Form::select('estado_porcentaje_id_exportar_monitor', $estados_porcentaje, null, [
                                    'placeholder' => 'TODOS LOS ESTADOS DEL COMPROMISO',
                                    'class' => 'form-control select2 exportar_monitor selectores_exportar_monitor',
                                    'multiple' => 'multiple',
                                    'id' => 'estado_porcentaje_id_exportar_monitor',
                                ]) !!}
                            </div>
                            <div class="col-md-12">
                                <label>Provincias:</label>
                                {!! Form::select('provincia_id_exportar_monitor', $provincias_compromisos, null, [
                                    'placeholder' => 'TODAS LAS PROVINCIAS',
                                    'class' => 'form-control select2 exportar_monitor selectores_exportar_monitor',
                                    'multiple' => 'multiple',
                                    'id' => 'provincia_id_exportar_monitor',
                                ]) !!}
                            </div>
                            <div class="col-md-12">
                                <label>Cantones:</label>
                                {!! Form::select('canton_id_exportar_monitor', [], null, [
                                    'placeholder' => 'TODOS LOS CANTONES',
                                    'class' => 'form-control select2 exportar_monitor selectores_exportar_monitor',
                                    'multiple' => 'multiple',
                                    'id' => 'canton_id_exportar_monitor',
                                ]) !!}
                            </div>
                            <div class="col-md-12">
                                <label>Parroquias:</label>
                                {!! Form::select('parroquia_id_exportar_monitor', [], null, [
                                    'placeholder' => 'TODAS LAS PARROQUIAS',
                                    'class' => 'form-control select2 exportar_monitor selectores_exportar_monitor',
                                    'multiple' => 'multiple',
                                    'id' => 'parroquia_id_exportar_monitor',
                                ]) !!}
                            </div>
                            <div class="col-md-12 btnTop">
                                <div class="row" style="background:rgba(42, 191, 249, 0.15);padding:10px">
                                    <div class="col-md-12 ">
                                        <label>Nombre de Compromisos:</label>
                                        <select
                                            class="form-control form-control-sm select2 exportar_monitor selectores_exportar_monitor"
                                            id="nombre_compromiso_exportar_monitor"
                                            name="nombre_compromiso_exportar_monitor" multiple="multiple">
                                            <option value="" selected>TODOS</option>
                                            <option v-for="(value,index) in arragloNombreCodigoCompromisos"
                                                :value="index" v-text="value">
                                            </option>
                                        </select>

                                    </div>
                                    <div class="col-md-12">
                                        <label>Código del Compromisos:</label>
                                        <select
                                            class="form-control form-control-sm select2 exportar_monitor selectores_exportar_monitor"
                                            id="codigo_compromiso_exportar_monitor"
                                            name="codigo_compromiso_exportar_monitor" multiple="multiple">
                                            <option value="" selected>TODOS</option>
                                            <option v-for="(value,index) in arragloCodigosCompromisos"
                                                :value="index" v-text="value">
                                            </option>
                                        </select>

                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">

                            <div class="col-md-12">
                                <label>Descripción del antecedente:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm exportar_monitor"
                                        id="descripcion_antecedente_exportar_monitor">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label> <input type="checkbox" id="habilitarFechaAntecedente"
                                        v-model="habilitarFechaAntecedente">
                                    Habilitar Interválos de Fechas último antecedente
                                </label>
                            </div>
                            <div class="col-md-12">
                                <label>fecha del último antecedente:</label>
                                <div class="input-group">
                                    <input type="date" class="form-control exportar_monitor"
                                        id="fecha_inicio_antecedente_exportar_monitor" value="{{ $fecha_inicio }}"
                                        :disabled="!habilitarFechaAntecedente">
                                    <input type="date" class="form-control exportar_monitor"
                                        id="fecha_fin_antecedente_exportar_monitor" value="<?php echo date('Y-12-31'); ?>"
                                        :disabled="!habilitarFechaAntecedente">
                                </div>
                            </div>


                            <div class="col-md-12">
                                <label>Último avance aprobado:</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm exportar_monitor"
                                        id="descripcion_avance_exportar_monitor">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label> <input type="checkbox" id="habilitarFechaUltimoAvance"
                                        v-model="habilitarFechaUltimoAvance">
                                    Habilitar Interválos de Fechas último avance
                                </label>
                            </div>
                            <div class="col-md-12">
                                <label>fecha del último avance:</label>
                                <div class="input-group">
                                    <input type="date" class="form-control exportar_monitor"
                                        id="fecha_inicio_avance_exportar_monitor" value="{{ $fecha_inicio }}"
                                        :disabled="!habilitarFechaUltimoAvance">
                                    <input type="date" class="form-control exportar_monitor"
                                        id="fecha_fin_avance_exportar_monitor" value="<?php echo date('Y-12-31'); ?>"
                                        :disabled="!habilitarFechaUltimoAvance">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <hr />
                            </div>
                            <div class="col-md-12">
                                <label> <input type="checkbox" id="habilitarFechaCumplido"
                                        v-model="habilitarFechaCumplido" :checked="habilitarFechaCumplido">
                                    Habilitar Interválos de Fechas de Compromisos cumplidos
                                </label>
                            </div>
                            <div class="col-md-12">
                                <label>Fecha de Compromisos Cumplido:</label>
                                <div class="input-group">
                                    <input type="date" class="form-control exportar_monitor"
                                        id="fecha_inicio_cuumplido_exportar_monitor" value="{{ $fecha_inicio }}"
                                        :disabled="!habilitarFechaCumplido">
                                    <input type="date" class="form-control exportar_monitor"
                                        id="fecha_fin_cumplido_exportar_monitor" value="<?php echo date('Y-12-31'); ?>"
                                        :disabled="!habilitarFechaCumplido">

                                </div>
                            </div>

                            <div class="col-md-12">
                                <label>Monitor:</label>
                                {!! Form::select('monitor_id_exportar_monitor', $monitores, null, [
                                'placeholder' => 'TODOS LOS MONITORES',
                                'class' => 'form-control select2 exportar_monitor selectores_exportar_monitor',
                                'multiple' => 'multiple',
                                'id' => 'monitor_id_exportar_monitor',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="btn btn-default" type="button" v-on:click="exportarExcelAvanzado()">
                    <img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel
                </button>
                <button class="btn btn-default" type="button" v-on:click="filtrarDatosAvanzado()">
                    <i class="fa fa-search"></i>&nbsp;Filtrar
                </button>
                <button class="btn btn-default" onclick="resetearSelectores()">Resetear Filtros</button>

                <button class="btn btn-default cerrarmodal" v-on:click="cerrarFiltroDatosMonitor()" data-dismiss="modal"
                    id="cerrar_modal_filtro_monitor"><b><i class="fa fa-times"></i></b>
                    Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-excel-ministro">
    <div class="modal-dialog ">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">
                    Filtrado de Datos y exportación en Excel - MINISTRO
                </h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div :class="rolMinistro == 0 ? 'col-md-12' : 'hidden'">
                        <label>Gabinete responsable:</label>
                        {!! Form::select('gabinete_id_exportar', $gabinete, null, [
                            'placeholder' => 'TODOS LOS GABINETES',
                            'class' => 'form-control ',
                            'id' => 'gabinete_id_exportar',
                        ]) !!}
                        <label>Institución responsable:</label>
                        {!! Form::select('institucion_id_exportar', [], null, [
                            'placeholder' => 'TODOS LAS INSTITUCIONES',
                            'class' => 'form-control ',
                            'id' => 'institucion_id_exportar',
                        ]) !!}
                    </div>
                    <div class="col-md-12">
                        <label>fecha inicio:</label>
                        <div class="input-group">
                            <input type="date" class="form-control" id="fecha_inicio_exportar"
                                value="<?php echo date('2021-01-01'); ?>">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>fecha fin:</label>
                        <div class="input-group">
                            <input type="date" class="form-control" id="fecha_fin_exportar"
                                value="<?php echo date('Y-12-31'); ?>">
                            <span class="input-group-btn">&nbsp;

                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="btn btn-default" type="button" v-on:click="exportarExcel()">
                    <img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel
                </button>
                <button class="btn btn-default" type="button" v-on:click="filtrarDatos()">
                    <i class="fa fa-search"></i>&nbsp;Filtrar
                </button>

                <button class="btn btn-default cerrarmodal" data-dismiss="modal" id="cerrar_modal_filtro"><b><i
                            class="fa fa-times"></i></b>
                    Cerrar</button>
            </div>
        </div>
    </div>
</div>
