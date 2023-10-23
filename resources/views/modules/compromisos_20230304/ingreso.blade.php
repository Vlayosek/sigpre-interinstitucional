<div class="modal fade" id="modal-negar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <h5>Motivo del Negado</h5>
                    <input type="hidden" v-model="formNegar.id">
                    <input type="text" placeholder="Motivo" class="form-control" v-model="formNegar.motivo">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary cerrarmodal" data-dismiss="modal" v-on:click="negarAvances()"><b><i
                            class="fa fa-save"></i></b>
                    Guardar Motivo</button>
                <button class="btn btn-default cerrarmodal" data-dismiss="modal"><b><i class="fa fa-times"></i></b>
                    Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-default">
    <div class="modal-dialog modal-dialog-1  modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="tabbable" id="tabs-162344">
                                            <ul class="nav nav-tabs ">
                                                <li class="li-ubica nav-item">
                                                    <a class="nav-link " id="link_inicial" href="#informacionGeneral"
                                                        data-toggle="tab" v-on:click="linkNav=0">
                                                        <i class="fa fa-info"></i>&nbsp;
                                                        Informacion General</a>
                                                </li>

                                                <li class="li-ubica nav-item" v-show="!crear">
                                                    <a class="nav-link" href="#ubicacion" data-toggle="tab"
                                                        v-on:click="linkNav=1">
                                                        <i class="fa fa-cog"></i>&nbsp;Ubicaci&oacute;n</a>
                                                </li>
                                                <li class="li-ubica nav-item" v-show="!crear">
                                                    <a class="nav-link" href="#antecedentes" data-toggle="tab"
                                                        v-on:click="linkNav=2">
                                                        <i class="fa fa-cog"></i>&nbsp;Antecedentes</a>
                                                </li>
                                                <li class="li-ubica nav-item" v-show="!crear">
                                                    <a class="nav-link" href="#objetivos" data-toggle="tab"
                                                        v-on:click="linkNav=3">
                                                        <i class="fa fa-cog"></i>&nbsp;Objetivos</a>
                                                </li>
                                                <!--<li class="li-ubica nav-item" v-show="!crear">
                                                    <a class="nav-link" href="#cronograma" data-toggle="tab"
                                                        v-on:click="linkNav=4">
                                                        <i class="fa fa-cog"></i>&nbsp;Cronograma</a>
                                                </li>-->
                                                <li class="li-ubica nav-item" v-show="!crear">
                                                    <a class="nav-link" href="#archivos" data-toggle="tab"
                                                        v-on:click="linkNav=5">
                                                        <i class="fa fa-cog"></i>&nbsp;Archivos</a>
                                                </li>
                                                <li class=" li-ubica nav-item " v-show="!crear">
                                                    <a class="nav-link" href="#avances" data-toggle="tab"
                                                        v-on:click="linkNav=6">
                                                        <i class="fa fa-cog"></i>&nbsp;Avances</a>
                                                </li>
                                                <li class="li-ubica nav-item" v-show="!crear">
                                                    <a class="nav-link" href="#mensajes" data-toggle="tab"
                                                        v-on:click="linkNav=7">
                                                        <i class="fa fa-paper-plane"></i>&nbsp;Mensajes</a>
                                                </li>


                                                <li class="li-ubica nav-item" v-show="!crear">
                                                    <a class="nav-link" href="#historico" data-toggle="tab"
                                                        v-on:click="linkNav=8">
                                                        <i class="fa fa-history"></i>&nbsp;Historico</a>
                                                </li>
                                            </ul>

                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <h5 v-text="formCrear.codigo" style="font-weight: bold"></h5>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="tab-content">
                                            <div class="tab-pane " id="cronograma">
                                                <div class="col-md-12 padding-colMD">
                                                    <div class="row">
                                                        <div :class="formCronograma.numero == '--' ? 'hidden' : 'col-md-4'"
                                                            style="background: rgba(0, 0, 0, 0.04);
                                                            padding-left: 30px;
                                                            padding-right: 30px;
                                                            right: 30px;
                                                            left: 0px;">
                                                            <div class="row">
                                                                <h5 class="col-md-12 padding-colMD"
                                                                    v-text="'Periodo: '+formCronograma.numero">

                                                                </h5>

                                                                <div class="col-md-12 padding-colMD">
                                                                    <label>Descripci&oacute;n:</label>
                                                                    <textarea class="form-control" v-model="formCronograma.descripcion_meta"></textarea>
                                                                </div>
                                                                <div class="col-md-12 padding-colMD hidden">
                                                                    <label>Caracterizaci&oacute;n:</label>
                                                                    <textarea class="form-control" v-model="formCronograma.caracterizacion"></textarea>
                                                                </div>

                                                                <div class="col-md-12 padding-colMD">
                                                                    <div class="row">
                                                                        <h6 class="col-md-12 padding-colMD">
                                                                            <strong>Periodo</strong>
                                                                        </h6>
                                                                        <hr />
                                                                        <div class="col-md-4 padding-colMD">
                                                                            <label>Meta:</label>
                                                                            <input type="text"
                                                                                class="form-control decimal"
                                                                                v-model="formCronograma.meta_periodo"
                                                                                v-on:keyup="calcular">
                                                                        </div>
                                                                        <div class="col-md-4 padding-colMD">
                                                                            <label>Cumplimiento:</label>
                                                                            <input type="text"
                                                                                class="form-control decimal"
                                                                                v-model="formCronograma.cumplimiento_periodo"
                                                                                v-on:keyup="calcular">
                                                                        </div>
                                                                        <div class="col-md-4 padding-colMD">
                                                                            <label>Pendiente:</label>
                                                                            <input type="text"
                                                                                class="form-control numero"
                                                                                v-model="formCronograma.pendiente_periodo"
                                                                                disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12 padding-colMD">
                                                                    <div class="row">
                                                                        <h6 class="col-md-12 padding-colMD">
                                                                            <strong>Acumulado</strong>
                                                                        </h6>
                                                                        <hr />
                                                                        <div class="col-md-4 padding-colMD">
                                                                            <label>Meta:</label>
                                                                            <input type="text"
                                                                                class="form-control numero"
                                                                                v-model="formCronograma.meta_acumulada"
                                                                                disabled>
                                                                        </div>
                                                                        <div class="col-md-4 padding-colMD">
                                                                            <label>Cumplimiento:</label>
                                                                            <input type="text"
                                                                                class="form-control numero"
                                                                                v-model="formCronograma.cumplimiento_acumulado"
                                                                                disabled>
                                                                        </div>
                                                                        <div class="col-md-4 padding-colMD">
                                                                            <label>Pendiente:</label>
                                                                            <input type="text"
                                                                                class="form-control numero"
                                                                                v-model="formCronograma.pendiente_acumulado"
                                                                                disabled>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="col-md-12 padding-colMD">
                                                                    <label>Observaci&oacute;n:</label>
                                                                    <textarea class="form-control" v-model="formCronograma.observaciones"></textarea>
                                                                </div>
                                                                <div class="col-md-12 padding-colMD">
                                                                    <br />
                                                                </div>
                                                                <div class="col-md-6 padding-colMD">
                                                                    <button class="btn btn-block btn-default"
                                                                        v-on:click="formCronograma.numero='--'">Ocultar</button>
                                                                </div>
                                                                @if (!$corresponsable)
                                                                    <div class="col-md-6 padding-colMD">
                                                                        <button class="btn btn-block btn-info"
                                                                            v-on:click="guardarPeriodo()"><i
                                                                                class="fa fa-save"></i>&nbsp;Registrar
                                                                            Cumplimiento</button>
                                                                    </div>
                                                                @endif
                                                            </div>

                                                        </div>
                                                        <div :class="formCronograma.numero == '--' ? ' col-md-12' : ' col-md-8'"
                                                            style="padding:15px">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <label>Seleccione un Objetivo:</label>
                                                                    <select id="objetivo_id"
                                                                        v-model='formCronograma.objetivo_id'
                                                                        class='form-control'>
                                                                        <option value="" selected>SELECCIONE UNA
                                                                            OPCION
                                                                        </option>
                                                                        <option v-for="vv in arregloObjetivos"
                                                                            :value="vv.id"
                                                                            v-text="(vv.numero+'. '+vv.objetivo).toUpperCase()">
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="table table-responsive tablaConsulta">
                                                                        <button id="BotonDatatablePeriodos"
                                                                            onclick="datatableCargarPeriodos()"
                                                                            class="hidden"></button>

                                                                        <table
                                                                            class="table table-bordered table-striped"
                                                                            id="dtmenuPeriodos"
                                                                            style="width:100%!important">
                                                                            <thead>
                                                                                <th></th>
                                                                                <th>#</th>
                                                                                <th>Fecha Inicio</th>
                                                                                <th>Fecha Fin</th>
                                                                                <th>Temporalidad</th>
                                                                                <th>Descripcion</th>
                                                                                <th>Meta</th>
                                                                                <th>Cumplimiento</th>
                                                                                <th>Pendiente</th>
                                                                                <th>Meta Acumulada</th>
                                                                                <th>Cumplimiento acumulado</th>
                                                                                <th>% Avance Periodo</th>
                                                                                <th>% Avance/Meta Total</th>

                                                                            </thead>
                                                                            <tbody id="dtbodyPeriodos"
                                                                                class="menu-pen">

                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane " id="avances">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div :class="rolMinistro == 0 ? 'hidden' : 'col-md-12'">
                                                            <div
                                                                :class="formCrear.cerrado == 'true' && rolMinistro == 1 ?
                                                                    'hidden row' : 'row'">
                                                                <div class="col-md-10">
                                                                    <div class="col-md-12">
                                                                        <input type="hidden" value="0"
                                                                            id="idAvance"
                                                                            v-model="formAvance.idAvance">
                                                                        <label>Avance</label>
                                                                        <textarea class="form-control-t" id="avance" autocomplete="off" v-model="formAvance.descripcion">
                                                                        </textarea>

                                                                    </div>

                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="row">
                                                                        &nbsp;
                                                                        <div class="col-md-12">
                                                                            <button type="button" name="upload"
                                                                                class="btn btn-block btn-info btnTopM"
                                                                                v-on:click="guardarAvance()"
                                                                                style="height:60px">
                                                                                <i class="fa fa-save"></i>
                                                                                &nbsp;Guardar Avance</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="col-md-12">
                                                                <button id="BotonDatatableAvances"
                                                                    onclick="datatableCargarAvances()"
                                                                    class="hidden"></button>


                                                                <div class="table table-responsive tablaConsulta">
                                                                    <table class="table table-bordered table-striped"
                                                                        id="dtmenuAvances"
                                                                        style="width:100%!important">
                                                                        <thead>
                                                                            <th>N&uacute;mero</th>
                                                                            <th>Fecha</th>
                                                                            <th>Avance</th>
                                                                            <th>Emisor</th>
                                                                            <th>Instituci&oacute;n Emisor</th>
                                                                            <th>Fecha leido</th>
                                                                            <th>Receptor</th>
                                                                            <th>Instituci&oacute;n Receptor</th>
                                                                            <th>Motivo</th>
                                                                            <th></th>
                                                                        </thead>
                                                                        <tbody id="tbobymenuAvances" class="menu-pen">

                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane " id="ubicacion">
                                                <div class="container-fluid">

                                                    <div class="row">
                                                        <div :class="rolMinistro == 1 ? 'hidden' : 'col-md-4'">
                                                            <div class="row">
                                                                <div class="col-md-12">

                                                                    <div class="card card-primary">
                                                                        <div class="card-body p-0"
                                                                            style="height:600px;overflow-y:scroll">
                                                                            <div class="tree_main">
                                                                                <ul id="bs_main" class="main_ul">
                                                                                    <li :id="'bs_' + valueProvincia.id"
                                                                                        v-for="valueProvincia in arregloProvincias">
                                                                                        <span class="abrirplus plus"
                                                                                            :name="'bs_l_' + valueProvincia.id"
                                                                                            onclick="abrirCantones(this)">&nbsp;</span>
                                                                                        <input type="checkbox"
                                                                                            :id="'c_bs_' + valueProvincia.id"
                                                                                            name="ubicacion_"
                                                                                            :value="valueProvincia.id"
                                                                                            onclick="checkearCanton(this)">
                                                                                        <span
                                                                                            v-text="valueProvincia.descripcion"></span>
                                                                                        <ul :id="'bs_l_' + valueProvincia.id"
                                                                                            class="sub_ul hidden"
                                                                                            name="ul_ubicaciones">
                                                                                            <li :id="'bf_' + valueCanton.id"
                                                                                                v-for="valueCanton in valueProvincia.lista_detalle">
                                                                                                <span
                                                                                                    class="abrirplus plus"
                                                                                                    :name="'bf_l_' +
                                                                                                    valueCanton.id"
                                                                                                    onclick="abrirParroquias(this)">&nbsp;</span>
                                                                                                <input type="checkbox"
                                                                                                    :id="'c_bs_' +
                                                                                                    valueCanton.id"
                                                                                                    name="ubicacion_"
                                                                                                    :value="valueCanton.id"
                                                                                                    onclick="checkearCanton(this)">
                                                                                                <span
                                                                                                    v-text="valueCanton.descripcion">
                                                                                                </span>
                                                                                                <ul :id="'bf_l_' + valueCanton.id"
                                                                                                    class="inner_ul hidden"
                                                                                                    name="ul_ubicaciones">
                                                                                                    <li :id="'io_' +
                                                                                                    valueParroquia.id"
                                                                                                        v-for="valueParroquia in valueCanton.lista_detalle"
                                                                                                        class="li_cbs">
                                                                                                        <input
                                                                                                            type="checkbox"
                                                                                                            :id="'c_bs_' +
                                                                                                            valueParroquia
                                                                                                                .id"
                                                                                                            name="ubicacion_"
                                                                                                            :value="valueParroquia
                                                                                                                .id" />&nbsp;<span
                                                                                                            v-text="valueParroquia.descripcion">
                                                                                                        </span>
                                                                                                    </li>
                                                                                                </ul>
                                                                                            </li>

                                                                                        </ul>
                                                                                    </li>

                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <button type="button" name="upload"
                                                                        class="btn btn-block btn-info"
                                                                        v-on:click="guardarUbicacion()">
                                                                        <i class="fa fa-save"></i>
                                                                        &nbsp;Guardar Ubicaci&oacute;n</button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div :class="rolMinistro == 1 ? 'col-md-12' : 'col-md-8'">
                                                            <button id="BotonDatatableUbicaciones"
                                                                onclick="datatableCargarUbicaciones()"
                                                                class="hidden"></button>


                                                            <div class="table table-responsive tablaConsulta">
                                                                <table class="table table-bordered table-striped"
                                                                    id="dtmenuUbicacion" style="width:100%!important">
                                                                    <thead>
                                                                        <th>Provincia</th>
                                                                        <th>Ciudad</th>
                                                                        <th>Parroquia</th>
                                                                    </thead>
                                                                    <tbody id="tbobymenuUbicacion" class="menu-pen">

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
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
                                                                <div class="col-md-6">
                                                                    <label>Avances</label>
                                                                    <textarea class="form-control-t" autocomplete="off" id="avance_compromiso" v-model="formCrear.avance_compromiso"></textarea>
                                                                </div>
                                                                <div class="col-md-6" v-show="rolMinistro==0">
                                                                    <label>Notas</label>
                                                                    <textarea class="form-control-t" autocomplete="off" id="notas_compromiso" v-model="formCrear.notas_compromiso"></textarea>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <div class="col-md-4">
                                                                            <label>Estado de Gesti&oacute;n</label>
                                                                            {!! Form::select('estado_porcentaje_id', $estados_porcentaje, null, [
                                                                                'v-model' => 'formCrear.estado_porcentaje_id',
                                                                                'placeholder' => 'SELECCIONE UNA OPCION',
                                                                                'class' => 'form-control  ',
                                                                                'id' => 'estado_porcentaje_id',
                                                                                ':disabled' => 'rolMinistro==1',
                                                                            ]) !!}

                                                                        </div>
                                                                        <div class="col-md-4">
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
                                                                        <div class="col-md-4">
                                                                            <label>Avance del Compromiso</label>
                                                                            <input type="text"
                                                                                class="form-control numero"
                                                                                autocomplete="off" id="avance"
                                                                                v-model="formCrear.avance"
                                                                                v-text="formCrear.avance" disabled>
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
                                                                    <div class="col-md-6">
                                                                        <label>Instituci&oacute;n</label>
                                                                        <select id="institucion_id"
                                                                            v-model='formCrear.institucion_id'
                                                                            class='form-control select2'
                                                                            v-on:change="changeInstitucion(this)"
                                                                            :disabled="rolMinistro == 1">

                                                                        </select>

                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label>Responsables</label>
                                                                        <select id="responsable_id"
                                                                            v-model='formCrear.responsable_id'
                                                                            class='form-control select2'
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
                                                                        'class' => 'form-control select2',
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
                                            <div class="tab-pane" id="antecedentes">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <input type="hidden" value="0"
                                                                    v-model="formAntecedente.idAntecedente"
                                                                    id="idAntecedente">
                                                                <div class="col-md-3 " v-show="rolMinistro==0">

                                                                    <div class="col-md-12">
                                                                        <label for="name"
                                                                            class="control-label col-sm-12">Fecha
                                                                            :</label>

                                                                        <input autocomplete="off" class="form-control"
                                                                            type="date" id="fecha_antecedente"
                                                                            v-model="formAntecedente.fecha_antecedente" />

                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="col-md-12">
                                                                            <label>Antecedente</label>

                                                                            <input type="text"
                                                                                class="form-control col-sm-12"
                                                                                id="antecedente" autocomplete="off"
                                                                                v-model="formAntecedente.antecedente">

                                                                        </div>
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
                                                                                    v-on:click="guardarAntecedente()">
                                                                                    <i class="fa fa-save"></i>
                                                                                    &nbsp;Guardar</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                </div>
                                                                <div
                                                                    :class="rolMinistro == 0 ? 'col-md-9' :
                                                                        'col-md-12 padding40px'">
                                                                    <button id="BotonDatatableAntecedentes"
                                                                        onclick="datatableCargarAntecedentes()"
                                                                        class="hidden"></button>

                                                                    <div class="table table-responsive tablaConsulta">
                                                                        <table
                                                                            class="table table-bordered table-striped"
                                                                            id="dtmenuAntecedentes"
                                                                            style="width:100%!important">
                                                                            <thead>
                                                                                <th>No</th>
                                                                                <th>Fecha</th>
                                                                                <th>Antecedente</th>
                                                                                <th width="20%"></th>
                                                                            </thead>
                                                                            <tbody id="tbobymenuAntecedentes"
                                                                                class="menu-pen">

                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="objetivos">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <!--  <div :class="rolMinistro == 1 ? 'hidden' : 'col-md-3'">-->
                                                                @if ($corresponsable)
                                                                    <div class="col-md-3 hidden">
                                                                    @else
                                                                        <div class="col-md-3">
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
                                                                        <textarea class="form-control" id="objetivo" v-model="formObjetivo.objetivo" autocomplete="off"
                                                                            :disabled="deshabilitarPorDesbloqueo"></textarea>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label>Descripcion de la Meta:</label>
                                                                        <textarea class="form-control" v-model="formObjetivo.descripcion_meta" autocomplete="off" id="descripcion_meta"
                                                                            :disabled="deshabilitarPorDesbloqueo"></textarea>
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
                                                            <button id="BotonDatatableObjetivo"
                                                                onclick="datatableCargarObjetivos()"
                                                                class="hidden"></button>
                                                            @if ($corresponsable)
                                                                <div class="col-md-12">
                                                                @else
                                                                    <div
                                                                        :class="rolMinistro == 1 ? 'col-md-9' : 'col-md-9'">
                                                            @endif
                                                            <div class="table table-responsive tablaConsulta">
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="archivos">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div
                                                        :class="formCrear.cerrado == 'true' && rolMinistro == 1 ?
                                                            'hidden row' : 'row'">
                                                        <div class="col-sm-10 ">
                                                            <button id="BotonDatatableArchivo"
                                                                onclick="datatableCargarArchivos()"
                                                                class="hidden"></button>
                                                            <div class="form-group">
                                                                <label><strong>Cargar
                                                                        Archivos</strong></label>
                                                                <div class="custom-file">
                                                                    <input type="file" name="files" multiple
                                                                        class="custom-file-input form-control"
                                                                        id="customFile">
                                                                    <label class="custom-file-label"
                                                                        id="customfilelabel"
                                                                        for="customFile">Archivo</label>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="row">
                                                                &nbsp;
                                                                <div class="col-md-12">
                                                                    <button type="button" name="upload"
                                                                        class="btn btn-block btn-info"
                                                                        v-on:click="guardarArchivo()">
                                                                        <i class="fa fa-save"></i>
                                                                        &nbsp;Guardar Archivos</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="table table-responsive tablaConsulta">
                                                                <table class="table table-bordered table-striped"
                                                                    id="dtmenuArchivos" style="width:100%!important">
                                                                    <thead>
                                                                        <th>Fecha</th>
                                                                        <th>Nombre</th>
                                                                        <th>Emisor</th>
                                                                        <th>Instituci&oacute;n Emisor</th>
                                                                        <th>Fecha leido</th>
                                                                        <th>Receptor</th>
                                                                        <th>Instituci&oacute;n Receptor</th>
                                                                        <th width="20%"></th>
                                                                    </thead>
                                                                    <tbody id="tbobymenuArchivos" class="menu-pen">

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="historico">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button id="BotonDatatableHistorico"
                                                                onclick="datatableCargarHistorico()"
                                                                class="hidden"></button>

                                                            <div class="table table-responsive tablaConsulta">
                                                                <table class="table table-bordered table-striped"
                                                                    id="dtmenuHistorico" style="width:100%!important">
                                                                    <thead>
                                                                        <th>Fecha</th>
                                                                        <th>Descripcion</th>
                                                                        <th>Usuario</th>
                                                                        <th>Institucion</th>
                                                                    </thead>
                                                                    <tbody id="tbobymenuHistorico" class="menu-pen">

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="mensajes">
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div
                                                        :class="formCrear.cerrado == 'true' && rolMinistro == 1 ?
                                                            'hidden row' : 'row'">
                                                        <div class="col-md-10">
                                                            <div class="col-md-12">
                                                                <label>Enviar Mensaje</label>
                                                                <textarea class="form-control-t" id="mensaje" autocomplete="off" v-model="formMensaje.descripcion">
                                                                            </textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="row">
                                                                &nbsp;
                                                                <div class="col-md-12">
                                                                    <button type="button" name="upload"
                                                                        class="btn btn-block btn-info btnTopM"
                                                                        v-on:click="guardarMensaje()"
                                                                        style="height:60px">
                                                                        <i class="fa fa-paper-plane"></i>
                                                                        &nbsp;Enviar Mensaje</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button id="BotonDatatableMensaje"
                                                                onclick="datatableCargarMensajes()"
                                                                class="hidden"></button>
                                                            <div class="table table-responsive tablaConsulta">
                                                                <table class="table table-bordered table-striped"
                                                                    id="dtmenuMensajes" style="width:100%!important">
                                                                    <thead>
                                                                        <th>Fecha de Envio</th>
                                                                        <th>Descripcion</th>
                                                                        <th>Emisor</th>
                                                                        <th>Instituci&oacute;n Emisor</th>
                                                                        <th>Fecha leido</th>
                                                                        <th>Receptor</th>
                                                                        <th>Instituci&oacute;n Receptor</th>
                                                                        <th> </th>
                                                                    </thead>
                                                                    <tbody id="tbobymenuMensajes" class="menu-pen">

                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-end">
        <button class="btn btn-primary" disabled v-show="cargando"><img
                src="{{ url('/spinner.gif') }}">&nbsp;Cargando
        </button>
        <button :class="rolMinistro == 1 ? 'hidden' : 'btn btn-primary'" v-on:click="confirmar()"
            v-show="linkNav==0&&!cargando"><b><i class="fa fa-save"></i></b>
            Guardar Compromiso</button>
        <button class="btn btn-primary " v-on:click="crearCodigo()"
            v-show="visibleNotificar&&rolMinistro==0&&!cargando"><b><i class="fa fa-paper-plane"></i></b>
            Notificar</button>
        <button class="btn btn-default cerrarmodal" data-dismiss="modal" v-show="!cargando"><b><i
                    class="fa fa-times"></i></b>
            Cerrar</button>

    </div>
</div>
</div>
</div>
<div class="modal fade" id="modal-filtrado">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">
                    Filtrado
                </h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    FILTRADO
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="btn btn-default cerrarmodal" data-dismiss="modal"><b><i class="fa fa-times"></i></b>
                    Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-excel">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">
                    Filtrado de Datos en Excel
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
