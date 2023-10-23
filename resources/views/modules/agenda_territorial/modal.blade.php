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

                                                <li class="li-ubica nav-item"
                                                    v-show="!crear&&(inauguracion_complementaria||inauguracion_principal)">
                                                    <a class="nav-link" href="#inauguracion" data-toggle="tab"
                                                        v-on:click="linkNav=3">
                                                        <i class="fa fa-cog"></i>&nbsp;Inauguraci&oacute;n</a>
                                                </li>
                                                <li class="li-ubica nav-item" v-show="!crear">
                                                    <a class="nav-link" href="#orden_dia" data-toggle="tab"
                                                        v-on:click="linkNav=4">
                                                        <i class="fa fa-cog"></i>Orden del d&iacute;a</a>
                                                </li>
                                                <li class="li-ubica nav-item" v-show="!crear">
                                                    <a class="nav-link" href="#archivos" data-toggle="tab"
                                                        v-on:click="linkNav=5">
                                                        <i class="fa fa-cog"></i>&nbsp;Archivos</a>
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
                                            <div class="tab-pane " id="ubicacion">
                                                <div class="container-fluid">

                                                    <div class="row">
                                                        <div :class="rolMinistro != 1 ? 'hidden' : 'col-md-4'">
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
                                                        <div :class="rolMinistro != 1 ? 'col-md-12' : 'col-md-8'">
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
                                                        <div class="col-md-3"
                                                        style="background: #17a2b845;padding: 20px;">
                                                        <input type="hidden" id="id" value="0"
                                                            v-model="formCrear.id">
                                                        <div class="col-md-12">
                                                            <label>Monitor</label>
                                                            <select id="monitor_id" v-model='formCrear.monitor_id'
                                                                class='form-control '
                                                                v-on:change="changeInstitucion(this)" disabled>
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
                                                                        class='form-control select2'
                                                                        v-on:change="changeInstitucion(this)"
                                                                        disabled>

                                                                    </select>

                                                                </div>
                                                                <div class="col-md-12">
                                                                    <label>Responsables</label>
                                                                    <select id="responsable_id"
                                                                        v-model='formCrear.responsable_id'
                                                                        class='form-control select2'
                                                                        v-on:change="changeInstitucion(this)"
                                                                        disabled>

                                                                    </select>
                                                                </div>
                                                                <div class="col-md-12">
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
                                                                <div class="col-md-12">
                                                                    <label>Datos del contacto</label>
                                                                    <input type="text" class="form-control"
                                                                        v-model="formCrear.contacto_delegado"
                                                                        disabled>

                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>
                                                        <div class="col-md-9"
                                                            style="padding: 20px;">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <label>Tipo de Actividad</label>
                                                                    <select v-model="formCrear.tipo_id"
                                                                        class="form-control form-control-sm" id="tipo_id"
                                                                        :disabled="rolMinistro != 1">
                                                                        <option value="">SELECCIONE UNA
                                                                            OPCI&Oacute;N
                                                                        </option>
                                                                        @foreach ($tipos as $value)
                                                                            <option value="{{ $value->id }}"
                                                                                data-abv="{{ $value->abv }}">
                                                                                {{ $value->descripcion }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-3">
                                                                    <label for="name"
                                                                        class="control-label col-sm-12">Fecha
                                                                        Sugerida:</label>

                                                                    <input autocomplete="off" class="form-control form-control-sm"
                                                                        type="date" id="fecha_inicio"
                                                                        v-model="formCrear.fecha_inicio"
                                                                        :disabled="rolMinistro != 1" />
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label>Estado</label>
                                                                    {!! Form::select('estado_porcentaje_id', $estados_porcentaje, null, [
                                                                        'v-model' => 'formCrear.estado_porcentaje_id',
                                                                        'placeholder' => 'SELECCIONE UNA OPCION',
                                                                        'class' => 'form-control  form-control-sm',
                                                                        'id' => 'estado_porcentaje_id',
                                                                        ':disabled' => 'rolMinistro==1',
                                                                    ]) !!}

                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label>Prioridad</label>
                                                                    {!! Form::select('estado_id', $estados, null, [
                                                                        'v-model' => 'formCrear.estado_id',
                                                                        'placeholder' => 'SELECCIONE UNA OPCION',
                                                                        'class' => 'form-control form-control-sm',
                                                                        'id' => 'estado_id',
                                                                        ':disabled' => 'rolMinistro!=1',
                                                                    ]) !!}

                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Tema</label>
                                                                    <textarea class="form-control-t" autocomplete="off" id="tema" v-model="formCrear.tema"
                                                                        :disabled="rolMinistro != 1"></textarea>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Objetivo del enfoque politico</label>
                                                                    <textarea class="form-control-t" autocomplete="off" id="objetivo" v-model="formCrear.objetivo"
                                                                        :disabled="rolMinistro != 1"></textarea>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Antecedente</label>
                                                                    <textarea class="form-control-t" autocomplete="off" id="antecedente" v-model="formCrear.antecedente"
                                                                        :disabled="rolMinistro != 1"></textarea>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Justificacion</label>
                                                                    <textarea class="form-control-t" autocomplete="off" id="justificacion" v-model="formCrear.justificacion"
                                                                        :disabled="rolMinistro != 1"></textarea>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Descripcion de la actividad</label>
                                                                    <textarea class="form-control-t" autocomplete="off" id="descripcion" v-model="formCrear.descripcion"
                                                                        :disabled="rolMinistro != 1"></textarea>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label>Observación</label>
                                                                    <textarea class="form-control-t" autocomplete="off" id="observacion" v-model="formCrear.observacion"
                                                                        :disabled="rolMinistro != 1"></textarea>
                                                                </div>
                                                                <div class="col-md-4">
                                                                  <label class="">Duración</label>
                                                                  <input 
                                                                    type="time"
                                                                    class="form-control "
                                                                    autocomplete="off" id="duracion"
                                                                    v-model="formCrear.duracion"
                                                                    :disabled='rolMinistro != 1' 
                                                                  />
                                                                </div>
                                                                <div class="col-md-4">
                                                                  <label class="">Lugar</label>
                                                                  <input type="text"
                                                                  class="form-control "
                                                                  autocomplete="off" id="lugar"
                                                                  v-model="formCrear.lugar"
                                                                  :disabled='rolMinistro != 1'
                                                                  />
                                                                </div>                                                                                                                                
                                                                <div class="col-md-4 mt-4">
                                                                  <div class="form-check form-check-inline">
                                                                    <input type="checkbox"
                                                                    class="form-check-input"
                                                                    id="impacto"
                                                                    v-model="formCrear.impacto"
                                                                    :disabled='rolMinistro != 1'>
                                                                    <label>Impacto</label>
                                                                  </div>                                                                    
                                                                  <div class="form-check form-check-inline">
                                                                    <input type="checkbox"
                                                                        class="form-check-input"
                                                                        id="coyuntura"
                                                                        v-model="formCrear.coyuntura"
                                                                        :disabled='rolMinistro != 1'>
                                                                    <label>Coyuntura Política</label>
                                                                  </div>
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
                                                                <div class="col-md-3 " v-show="rolMinistro==1">

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
                                                                    :class="rolMinistro == 1 ? 'col-md-9' :
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
                                                                                <th width="20%">Acciones</th>
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
                                            <div class="tab-pane" id="inauguracion"
                                                v-show="inauguracion_complementaria||inauguracion_principal">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row">

                                                                <div class="col-md-12">

                                                                    <div class="row"
                                                                        v-show="inauguracion_principal">
                                                                        <div class="col-md-12">
                                                                            <div class="row ">
                                                                                <div class="col-md-8">
                                                                                    <div class="row">
                                                                                        <h5 class="col-md-12"
                                                                                            style="color: #3172d7;">
                                                                                            <br />
                                                                                            Obra Principal
                                                                                            <br />
                                                                                        </h5>
                                                                                        <div class="col-md-12">
                                                                                            <div class="row">
                                                                                                <div class="col-md-6">
                                                                                                    <label
                                                                                                        for="name"
                                                                                                        class="control-label col-sm-12">Visita
                                                                                                        del Sr.
                                                                                                        Presidente:</label>
                                                                                                    {!! Form::select('visita_presidente', ['SI' => 'SI', 'NO' => 'NO'], null, [
                                                                                                        'placeholder' => 'SELECCIONE UNA OPCION',
                                                                                                        'class' => 'form-control ',
                                                                                                        'v-model' => 'formObraPrincipal.visita_presidente',
                                                                                                        ':disabled' => 'rolMinistro!=1',
                                                                                                    ]) !!}

                                                                                                </div>
                                                                                                <div class="col-md-6"
                                                                                                    v-show="formObraPrincipal.visita_presidente=='SI'">
                                                                                                    <label
                                                                                                        for="name"
                                                                                                        class="control-label col-sm-12">Fecha
                                                                                                        de la
                                                                                                        &uacute;ltima
                                                                                                        visita:</label>
                                                                                                    <input
                                                                                                        type="date"
                                                                                                        v-model="formObraPrincipal.fecha_ultima_visita"
                                                                                                        class="form-control"
                                                                                                        :disabled="rolMinistro != 1">

                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <label for="name"
                                                                                                class="control-label col-sm-12">Fecha
                                                                                                de inicio:</label>
                                                                                            <input type="date"
                                                                                                v-model="formObraPrincipal.fecha_inicio"
                                                                                                class="form-control"
                                                                                                :disabled="rolMinistro != 1">

                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <label for="name"
                                                                                                class="control-label col-sm-12">Fecha
                                                                                                de fin:</label>
                                                                                            <input type="date"
                                                                                                v-model="formObraPrincipal.fecha_fin"
                                                                                                class="form-control"
                                                                                                :disabled="rolMinistro != 1">

                                                                                        </div>

                                                                                        <div class="col-md-12">

                                                                                            <label>Situaci&oacute;n
                                                                                                Actual: </label>
                                                                                            <textarea type="text" class="form-control col-sm-12" style="height:120px" autocomplete="off"
                                                                                                v-model="formObraPrincipal.situacion_actual" :disabled="rolMinistro != 1">
                                                                                            </textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-4"
                                                                                    style="background: rgba(23, 162, 184, 0.27) none repeat scroll 0% 0%; padding: 20px;">
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <label for="name"
                                                                                                class="control-label col-sm-12">Ejecutor
                                                                                                de proyecto:</label>
                                                                                            <input type="text"
                                                                                                v-model="formObraPrincipal.ejecutor_proyecto"
                                                                                                class="form-control"
                                                                                                :disabled="rolMinistro != 1">

                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <label for="name"
                                                                                                class="control-label col-sm-12">Constructor
                                                                                                de obra:</label>
                                                                                            <input type="text"
                                                                                                v-model="formObraPrincipal.constructor_obra"
                                                                                                class="form-control"
                                                                                                :disabled="rolMinistro != 1">

                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <label for="name"
                                                                                                class="control-label col-sm-12">N&uacute;mero
                                                                                                de beneficiarios
                                                                                                directos:</label>
                                                                                            <input type="text"
                                                                                                v-model="formObraPrincipal.numero_beneficiarios_directos"
                                                                                                class="form-control numero"
                                                                                                :disabled="rolMinistro != 1">

                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <label for="name"
                                                                                                class="control-label col-sm-12">N&uacute;mero
                                                                                                de beneficiarios
                                                                                                indirectos:</label>
                                                                                            <input type="text"
                                                                                                v-model="formObraPrincipal.numero_beneficiarios_indirectos"
                                                                                                class="form-control numero"
                                                                                                :disabled="rolMinistro != 1">

                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <label for="name"
                                                                                                class="control-label col-sm-12">Porcentaje
                                                                                                de avance:</label>
                                                                                            <input type="text"
                                                                                                v-model="formObraPrincipal.porcentaje_avance"
                                                                                                class="form-control numero"
                                                                                                :disabled="rolMinistro != 1">

                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <label for="name"
                                                                                                class="control-label col-sm-12">Costo
                                                                                                del proyecto:</label>
                                                                                            <input type="text"
                                                                                                id="costo_proyecto"
                                                                                                v-model="formObraPrincipal.costo_proyecto"
                                                                                                class="form-control moneda_full"
                                                                                                pattern="^\$\d{1,3}(,\d{3})*(\.\d+)?$"
                                                                                                :disabled="rolMinistro != 1">

                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            <label for="name"
                                                                                                class="control-label col-sm-12">Fuente
                                                                                                de
                                                                                                financiamiento:</label>
                                                                                            <input type="text"
                                                                                                v-model="formObraPrincipal.fuente_financiamiento"
                                                                                                class="form-control "
                                                                                                :disabled="rolMinistro != 1">

                                                                                        </div>
                                                                                        <div class="col-md-12">
                                                                                            <br />
                                                                                            <div class="row">
                                                                                                <div class="col-md-6">
                                                                                                    <button
                                                                                                        type="button"
                                                                                                        name="upload"
                                                                                                        class="btn btn-block btn-default"
                                                                                                        v-on:click="limpiarFormularios()"
                                                                                                        v-show="rolMinistro==1">
                                                                                                        Limpiar</button>
                                                                                                </div>

                                                                                                <div class="col-md-6">
                                                                                                    <button
                                                                                                        type="button"
                                                                                                        name="upload"
                                                                                                        class="btn btn-block btn-info"
                                                                                                        v-on:click="guardarObraPrincipal()"
                                                                                                        v-show="rolMinistro==1">
                                                                                                        <i
                                                                                                            class="fa fa-save"></i>
                                                                                                        &nbsp;Guardar</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>


                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="row">
                                                                        <h5 class="col-md-12" style="color: #3172d7;">
                                                                            <br />
                                                                            Obra Complementaria
                                                                            <hr />
                                                                        </h5>
                                                                        <div class="col-md-3 "
                                                                            v-show="rolMinistro==1">

                                                                            <div class="col-md-12">
                                                                                <label for="name"
                                                                                    class="control-label col-sm-12">Obra
                                                                                    Complementaria:</label>

                                                                                <textarea type="text" class="form-control col-sm-12" autocomplete="off"
                                                                                    v-model="formObraComplementaria.descripcion"> </textarea>

                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <div class="col-md-12">
                                                                                    <label>Porcentaje de Avance</label>
                                                                                    <input type="number"
                                                                                        class="form-control"
                                                                                        id="porcentaje_avance_obra_complementaria"
                                                                                        v-model="formObraComplementaria.porcentaje_avance"
                                                                                        min="5" max="100"
                                                                                        step="5">

                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <label for="name"
                                                                                    class="control-label col-sm-12">Responsable
                                                                                    de ejecuci&oacute;n:</label>

                                                                                <textarea type="text" class="form-control col-sm-12" autocomplete="off"
                                                                                    v-model="formObraComplementaria.responsable"> </textarea>

                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <hr />
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <button type="button"
                                                                                            name="upload"
                                                                                            class="btn btn-block btn-default"
                                                                                            v-on:click="limpiarFormularios()">
                                                                                            Limpiar</button>
                                                                                    </div>

                                                                                    <div class="col-md-6">
                                                                                        <button type="button"
                                                                                            name="upload"
                                                                                            class="btn btn-block btn-info"
                                                                                            v-on:click="guardarObraComplementaria()">
                                                                                            <i class="fa fa-save"></i>
                                                                                            &nbsp;Guardar</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            :class="rolMinistro == 1 ? 'col-md-9' :
                                                                                'col-md-12 padding40px'">

                                                                            <div
                                                                                class="table table-responsive tablaConsulta">

                                                                                <table
                                                                                    class="table table-bordered table-striped"
                                                                                    id="dtmenuObraComplementaria"
                                                                                    style="width:100%!important">
                                                                                    <thead>
                                                                                        <th>No</th>
                                                                                        <th>Obra Complementaria</th>
                                                                                        <th>Porcentaje</th>
                                                                                        <th>Responsable</th>
                                                                                        <th width="20%">Acciones
                                                                                        </th>
                                                                                    </thead>
                                                                                    <tbody
                                                                                        id="tbobymenuObraComplementaria"
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
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="orden_dia">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row">

                                                                <div class="col-md-3 " v-show="rolMinistro==1">

                                                                    <div class="col-md-12">
                                                                        <label for="name"
                                                                            class="control-label col-sm-12">Tema:</label>

                                                                        <input autocomplete="off" class="form-control"
                                                                            type="text"
                                                                            v-model="formOrdenDia.tema" />

                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="name"
                                                                            class="control-label col-sm-12">Expositor:</label>

                                                                        <input autocomplete="off" class="form-control"
                                                                            type="text"
                                                                            v-model="formOrdenDia.expositor" />

                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="name"
                                                                            class="control-label col-sm-12">Cargo:</label>

                                                                        <input autocomplete="off" class="form-control"
                                                                            type="text"
                                                                            v-model="formOrdenDia.cargo" />

                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="name"
                                                                            class="control-label col-sm-12">Entidad:</label>

                                                                        <input autocomplete="off" class="form-control"
                                                                            type="text"
                                                                            v-model="formOrdenDia.entidad" />

                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="name"
                                                                            class="control-label col-sm-12">Tiempo de
                                                                            intervenci&oacute;n:</label>

                                                                        <input autocomplete="off"
                                                                            class="form-control " type="time"
                                                                            v-model="formOrdenDia.tiempo" />

                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label for="name"
                                                                            class="control-label col-sm-12">Informaci&oacute;n
                                                                            Complementaria:</label>

                                                                        <textarea class="form-control-t" id="mensaje" autocomplete="off"
                                                                            v-model="formOrdenDia.informacion_complementaria">
                                                                            </textarea>

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
                                                                                    v-on:click="guardarOrdenDia()">
                                                                                    <i class="fa fa-save"></i>
                                                                                    &nbsp;Guardar</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                </div>

                                                                <div
                                                                    :class="rolMinistro == 1 ? 'col-md-9' :
                                                                        'col-md-12 padding40px'">
                                                                    <div class="row">
                                                                        <div class="col-md-10 padding40px"
                                                                            v-show="rolMinistro==1">
                                                                            <div class="row">
                                                                                <label
                                                                                    class="col-md-12"><strong>Participantes</strong></label>

                                                                                <div class="col-md-10">
                                                                                    <div class="form-group">
                                                                                        <div class="custom-file">
                                                                                            <input type="file"
                                                                                                name="files" multiple
                                                                                                class="custom-file-input form-control"
                                                                                                id="participantes_archivo">
                                                                                            <label
                                                                                                class="custom-file-label"
                                                                                                id="participantes_archivo_label"
                                                                                                for="participantes_archivo">Seleccione
                                                                                                un Archivo</label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-2">

                                                                                    <button type="button"
                                                                                        name="upload"
                                                                                        class="btn btn-block btn-info"
                                                                                        v-on:click="guardarArchivoParticipantes()">
                                                                                        <i class="fa fa-save"></i>
                                                                                        &nbsp;Guardar</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-md-2">
                                                                            <label
                                                                                class="col-md-12"><strong>&nbsp;</strong></label>
                                                                            <br />
                                                                            <button
                                                                                v-on:click="descargarArchivoParticipantesCargados();"
                                                                                class="btn btn-default "
                                                                                style="margin:22px"
                                                                                v-show="formCrear.nombre_participantes_archivo!=''&&formCrear.nombre_participantes_archivo!=null">Descargar
                                                                                Participantes</button>
                                                                        </div>
                                                                        <div
                                                                            class="table table-responsive tablaConsulta">
                                                                            <table
                                                                                class="table table-bordered table-striped"
                                                                                id="dtmenuOrden"
                                                                                style="width:100%!important">
                                                                                <thead>
                                                                                    <th>No</th>
                                                                                    <th>Tema</th>
                                                                                    <th>Expositor</th>
                                                                                    <th>Cargo</th>
                                                                                    <th>Entidad</th>
                                                                                    <th>Tiempo</th>
                                                                                    <th>Informacion</th>
                                                                                    <th width="20%">Acciones</th>
                                                                                </thead>
                                                                                <tbody id="tbobymenuOrden"
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
                                            </div>
                                            <div class="tab-pane" id="archivos">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div
                                                                :class="formCrear.cerrado == 'true' ? 'hidden row' : 'row'">
                                                                <div class="col-sm-10 ">
                                                                    <button id="BotonDatatableArchivo"
                                                                        onclick="datatableCargarArchivos()"
                                                                        class="hidden"></button>
                                                                    <div class="form-group">
                                                                        <label><strong>Cargar
                                                                                Archivos</strong></label>
                                                                        <div class="custom-file">
                                                                            <input type="file" name="files"
                                                                                multiple
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
                                                                        <table
                                                                            class="table table-bordered table-striped"
                                                                            id="dtmenuArchivos"
                                                                            style="width:100%!important">
                                                                            <thead>
                                                                                <th>Fecha</th>
                                                                                <th>Nombre</th>
                                                                                <th>Emisor</th>
                                                                                <th>Instituci&oacute;n Emisor</th>
                                                                                <th>Fecha leido</th>
                                                                                <th>Receptor</th>
                                                                                <th>Instituci&oacute;n Receptor</th>
                                                                                <th width="20%">Acciones</th>
                                                                            </thead>
                                                                            <tbody id="tbobymenuArchivos"
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
                                                                        <table
                                                                            class="table table-bordered table-striped"
                                                                            id="dtmenuHistorico"
                                                                            style="width:100%!important">
                                                                            <thead>
                                                                                <th>Fecha</th>
                                                                                <th>Descripcion</th>
                                                                                <th>Usuario</th>
                                                                                <th>Institucion</th>
                                                                            </thead>
                                                                            <tbody id="tbobymenuHistorico"
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
                                            <div class="tab-pane" id="mensajes">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div
                                                                :class="formCrear.cerrado == 'true' ? 'hidden row' : 'row'">
                                                                <div class="col-md-10">
                                                                    <div class="col-md-12">
                                                                        <label>Enviar Mensaje</label>
                                                                        <textarea class="form-control-t" id="mensaje" autocomplete="off" v-model="formMensaje.mensaje">
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
                                                                        <table
                                                                            class="table table-bordered table-striped"
                                                                            id="dtmenuMensajes"
                                                                            style="width:100%!important">
                                                                            <thead>
                                                                                <th>Fecha de Envio</th>
                                                                                <th>Descripcion</th>
                                                                                <th>Emisor</th>
                                                                                <th>Instituci&oacute;n Emisor</th>
                                                                                <th>Fecha leido</th>
                                                                                <th>Receptor</th>
                                                                                <th>Instituci&oacute;n Receptor</th>
                                                                                <th>Acciones</th>
                                                                            </thead>
                                                                            <tbody id="tbobymenuMensajes"
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
                <button class="btn btn-primary" v-on:click="confirmar()" v-show="linkNav==0&&!cargando"><b><i
                            class="fa fa-save"></i></b>
                    Guardar Actividad</button>
                <button class="btn btn-primary " v-on:click="crearCodigo()"
                    v-show="visibleNotificar&&rolMinistro==1&&!cargando"><b><i class="fa fa-paper-plane"></i></b>
                    Enviar</button>
                <button class="btn btn-default cerrarmodal" id="cerrar_modal_actividad" data-dismiss="modal"
                    v-show="!cargando"><b><i class="fa fa-times"></i></b>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">
                    Filtrado de Datos en Excel
                </h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 ">
                        <label>Provincias:</label>
                        <select id="provincia_id_exportar" class="form-control select2">
                            <option value="">TODAS LAS PROVINCIAS</option>
                            <option v-for="value in arregloProvincias" v-text="value.descripcion"
                                :value="value.id">
                            </option>
                        </select>

                    </div>
                    <div class="col-md-4 ">
                        <label>Ciudad:</label>
                        <select id="ciudad_id_exportar" class="form-control select2">
                            <option value="">TODAS LAS CIUDADES</option>
                            <option v-for="value in arregloCanton_" v-text="value.descripcion"
                                :value="value.id">
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4 ">
                        <label>Parroquia:</label>
                        <select id="canton_id_exportar" class="form-control select2">
                            <option value=""> TODAS LAS PARROQUIAS</option>
                            <option v-for="value in arregloParroquia_" v-text="value.descripcion"
                                :value="value.id">
                            </option>
                        </select>
                    </div>
                    <div :class="rolMinistro == 1 ? 'hidden' : 'col-md-12'">
                        <label>Institución responsable:</label>
                        {!! Form::select('institucion_id_exportar[]', $instituciones, null, [
                            'placeholder' => 'TODOS LAS INSTITUCIONES',
                            'class' => 'form-control select2',
                            'multiple' => 'multiple',
                            'required' => '',
                            'id' => 'institucion_id_exportar',
                        ]) !!}

                    </div>
                    <div class="col-md-12 ">
                        <label>Estados:</label>
                        {!! Form::select('estado_id_exportar', $estados_porcentaje, null, [
                            'placeholder' => 'TODOS LOS ESTADOS',
                            'class' => 'form-control select2',
                            'id' => 'estado_id_exportar',
                        ]) !!}

                    </div>
                    <div class="col-md-12">
                        <br />
                        <h5> Fecha sugerida </h5>
                        <hr />

                    </div>

                    <div class="col-md-6">
                        <label>fecha inicio:</label>
                        <div class="input-group">
                            <input type="date" class="form-control" id="fecha_inicio_exportar"
                                value="<?php echo date('Y-01-01'); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
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