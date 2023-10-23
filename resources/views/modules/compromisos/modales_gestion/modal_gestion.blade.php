<div class="modal fade" id="modal-default">
    <div class="modal-dialog modal-dialog-1  modal-xl">
        <div class="modal-content modal-content_">
            <div class="modal-body">
                <div class="row">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-10">
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
                                                        onclick="datatableCargarUbicaciones();">
                                                        <i class="fa fa-cog"></i>&nbsp;Ubicaci&oacute;n</a>
                                                </li>
                                                <li class="li-ubica nav-item" v-show="!crear">
                                                    <a class="nav-link" href="#antecedentes" data-toggle="tab"
                                                        onclick="datatableCargarAntecedentes();">
                                                        <i class="fa fa-cog"></i>&nbsp;Antecedentes</a>
                                                </li>
                                                <li class="li-ubica nav-item" v-show="!crear">
                                                    <a class="nav-link" href="#objetivos" data-toggle="tab"
                                                        onclick="datatableCargarObjetivos();">
                                                        <i class="fa fa-cog"></i>&nbsp;Objetivos</a>
                                                </li>
                                                <li class="li-ubica nav-item hidden" v-show="!crear">
                                                    <a class="nav-link" href="#cronograma" data-toggle="tab"
                                                        v-on:click="linkNav=4">
                                                        <i class="fa fa-cog"></i>&nbsp;Cronograma</a>
                                                </li>
                                                <li class="li-ubica nav-item" v-show="!crear">
                                                    <a class="nav-link" href="#archivos" data-toggle="tab"
                                                        onclick="datatableCargarArchivos();">
                                                        <i class="fa fa-cog"></i>&nbsp;Archivos</a>
                                                </li>
                                                <li class=" li-ubica nav-item " v-show="!crear">
                                                    <a class="nav-link" href="#avances" data-toggle="tab"
                                                        onclick="datatableCargarAvances();">
                                                        <i class="fa fa-cog"></i>&nbsp;Avances</a>
                                                </li>
                                                <li class="li-ubica nav-item" v-show="!crear">
                                                    <a class="nav-link" href="#mensajes" data-toggle="tab"
                                                        onclick="datatableCargarMensajes();">
                                                        <i class="fa fa-paper-plane"></i>&nbsp;Mensajes</a>
                                                </li>


                                                <li class="li-ubica nav-item" v-show="!crear">
                                                    <a class="nav-link" href="#historico" data-toggle="tab"
                                                        onclick="datatableCargarHistorico();">
                                                        <i class="fa fa-history"></i>&nbsp;Historico</a>
                                                </li>
                                            </ul>

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <h5 v-text="formCrear.codigo" style="font-weight: bold"></h5>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="tab-content">
                                            @include('modules.compromisos.tabs_registro.antecedentes')
                                            @include('modules.compromisos.tabs_registro.archivos')
                                            @include('modules.compromisos.tabs_registro.avances')
                                            @include('modules.compromisos.tabs_registro.cronograma')
                                            @include('modules.compromisos.tabs_registro.historico')
                                            @include('modules.compromisos.tabs_registro.informacion_general')
                                            @include('modules.compromisos.tabs_registro.mensajes')
                                            @include('modules.compromisos.tabs_registro.ubicacion')
                                            @include('modules.compromisos.tabs_registro.objetivos')

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
                                v-show="visibleNotificar&&rolMinistro==0&&!cargando"><b><i
                                        class="fa fa-paper-plane"></i></b>
                                Notificar</button>
                            <button class="btn btn-default cerrarmodal" data-dismiss="modal" v-show="!cargando"><b><i
                                        class="fa fa-times"></i></b>
                                Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
