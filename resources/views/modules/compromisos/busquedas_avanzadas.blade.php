    <!-- PESTAÑA BUSQUEDAS AVANZADAS -->
    <div class="col-md-12" v-show="busquedas" id="busquedas_avanzadas">
        <div class="row">
            <!--CONSULTA DE ESTADOS-->
            <div class="col-md-12 btnTop" style="padding-bottom:0px!important;padding-top:0px!important">
                <br>
                <div class="row">
                    <div class="col-12 col-sm-9 col-md-2 float-left">
                        <div class="info-box info-box-t">
                            <span
                                :class="currentTab_ === 25 ?
                                    'info-box-icon info-box-icon-t bg-primary elevation-1 ' :
                                    'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                v-text="mensajes_busqueda" style="color: #ffffff!important;width: 50px;">
                            </span>
                            <div :class="currentTab_ === 25 ? 'info-box-content ' : 'info-box-content'">
                                <a href="#" class="info-box-text h6 " v-on:click="currentTab_ = 25;"
                                    :class="{ link_seleccionado: currentTab_ === 25 }"
                                    onclick="datatableCompromisosBusquedas('MENSAJES')" id="busquedasMensajes">
                                    MENSAJES</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-9 col-md-2 float-left">
                        <div class="info-box info-box-t">
                            <span
                                :class="currentTab_ === 26 ?
                                    'info-box-icon info-box-icon-t bg-primary elevation-1 ' :
                                    'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                v-text="archivos_busqueda" style="color: #ffffff!important;width: 50px;">
                            </span>
                            <div :class="currentTab_ === 26 ? 'info-box-content ' : 'info-box-content'">
                                <a href="#" class="info-box-text h6 " v-on:click="currentTab_ = 26;"
                                    :class="{ link_seleccionado: currentTab_ === 26 }"
                                    onclick="datatableCompromisosBusquedas('ARCHIVOS')"id="busquedasArchivos">
                                    ARCHIVOS
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-9 col-md-2 float-left">
                        <div class="info-box info-box-t">
                            <span
                                :class="currentTab_ === 27 ?
                                    'info-box-icon info-box-icon-t bg-primary elevation-1 ' :
                                    'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                v-text="avances_busqueda" style="color: #ffffff!important;width: 50px;">
                            </span>
                            <div :class="currentTab_ === 27 ? 'info-box-content ' : 'info-box-content'">
                                <a href="#" class="info-box-text h6 " v-on:click="currentTab_ = 27;"
                                    :class="{ link_seleccionado: currentTab_ === 27 }"
                                    onclick="datatableCompromisosBusquedas('AVANCES')"id="busquedasAvances">
                                    AVANCES</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-9 col-md-2 float-left">
                        <div class="info-box info-box-t">
                            <span
                                :class="currentTab_ === 28 ?
                                    'info-box-icon info-box-icon-t bg-primary elevation-1 ' :
                                    'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                v-text="objetivos_busqueda" style="color: #ffffff!important;width: 50px;">
                            </span>
                            <div :class="currentTab_ === 28 ? 'info-box-content ' : 'info-box-content'">
                                <a href="#" class="info-box-text h6 " v-on:click="currentTab_ = 28;"
                                    :class="{ link_seleccionado: currentTab_ === 28 }"
                                    onclick="datatableCompromisosBusquedas('OBJETIVOS')"id="busquedasObjetivos">
                                    OBJETIVOS</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--FIN - CONSULTA DE ESTADOS-->
            <!--CONSULTA POR GABINETE, INSTITUCION, MONITOR-->
            <div class="col-sm-6">
                &nbsp;
            </div>

            <div class="col-sm-6">
                <button type="button" class="btn btn-primary btnTop" data-toggle="modal" data-backdrop="static"
                    data-keyboard="false" data-target="#modal-filtro-busqueda" v-show="!filtro_busqueda"
                    style="float:right">
                    <i class="fa fa-filter"></i>&nbsp; Filtrar Datos
                </button>
                <button type="button" class="btn btn-default btnTop" v-show="filtro_busqueda"
                    v-on:click="resetearFiltroBusqueda()" style="float:right">
                    <i class="fa fa-list"></i>&nbsp;Datos Filtrados
                </button>
            </div>

            <!--FIN CONSULTA POR GABINETE, INSTITUCION, MONITOR-->
            <div class="card-body">
                <div class="table table-responsive" id="tablaConsulta">
                    <table class="table table-bordered table-striped" id="dtmenu_busquedas"
                        style="width:100%!important">
                        <thead>
                            <th>Id</th>
                            <th>Nombre del Compromiso</th>
                            <th>Instituci&oacute;n</th>
                            <th>Gabinete</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Estado de Gesti&oacute;n</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody id="tbobymenu" class="menu-pen">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-mensajes">
        <div class="modal-dialog modal-md" style="min-width: 90%!important;">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="contenido-modal-mensajes">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div
                                                class="table table-responsive tablaConsulta dtListadoBusquedasArchivos datatablesSelectores hidden">
                                                <table class="table table-bordered table-striped"
                                                    id="dtListadoBusquedasArchivos" style="width:100%!important">
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
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div
                                                class="table table-responsive tablaConsulta dtListadoBusquedasAvances datatablesSelectores hidden">
                                                <table class="table table-bordered table-striped"
                                                    id="dtListadoBusquedasAvances" style="width:100%!important">
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
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div
                                                class="table table-responsive tablaConsulta dtListadoBusquedasMensajes datatablesSelectores hidden">
                                                <table class="table table-bordered table-striped"
                                                    id="dtListadoBusquedasMensajes" style="width:100%!important">
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
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div
                                                class="table table-responsive tablaConsulta dtListadoBusquedasObjetivos datatablesSelectores hidden">
                                                <table class="table table-bordered table-striped"
                                                    id="dtListadoBusquedasObjetivos" style="width:100%!important">
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
                                                    <tbody>

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
                <div class="modal-footer">
                    <button class="btn btn-default cerrarmodal" data-dismiss="modal"><b><i class="fa fa-times"></i></b>
                        Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12" v-show="exportaciones">
        <button class="btn btn-primary btn-sm" onclick="cargarExportaciones();"><i class="fa fa-refresh"></i>&nbsp;REFRESCAR</button>
        <div class="row">
            <!--FIN CONSULTA POR GABINETE, INSTITUCION, MONITOR-->
            <div class="card-body">
                <div class="table table-responsive">
                    <table class="table table-bordered table-striped" id="dtExportacion"
                        style="width:100%!important">
                        <thead>
                            <th>Reg</th>
                            <th>Creación</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody id="tbobymenu" class="menu-pen">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
