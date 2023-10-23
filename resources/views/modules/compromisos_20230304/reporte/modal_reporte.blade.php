<!--MODAL PARA REPORTE POR FILTRO-->
<div class="modal fade" id="modal-COMPROMISOS_CUMPLIDOS">
    <div class='modal-dialog modal-xs' style="min-width: 20%!important;">
        <div class="modal-content">
            <div class="modal-header" style="text-align:center">
                <label style="font-size:20px;">Reporte de compromisos cumplidos</label>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Fecha inicio de período:</label>
                        <div class="input-group">
                            <input type="date" class="form-control form-control-sm" id="fecha_inicio_cc"
                                value="<?php echo date('Y-m-01'); ?>">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Fecha Fin:</label>
                        <div class="input-group">
                            <input type="date" class="form-control form-control-sm" id="fecha_fin_cc"
                                value="<?php echo date('Y-m-t'); ?>">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Seleccione Gabinete:</label>
                        <div class="input-group">
                            <select class="form-control form-control-sm select2" id="filtro_gabinete"
                                name="filtro_gabinete">
                                <option value="" selected>TODOS</option>
                                <option v-for="value in arrayGabinete" :value="value.id"
                                    v-text="value.descripcion">
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>Seleccione Instituci&oacute;n Responsable:</label>
                        <div class="input-group">
                            <select class="form-control form-control-sm select2" id="filtro_institucion"
                            name="filtro_institucion">
                                <option value="" selected>TODOS</option>
                                <option v-for="value in arrayInstitucion" :value="value.id"
                                    v-text="value.descripcion">
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="modal-footer justify-content-end">
                <span class="input-group-btn">&nbsp;
                    <button class="btn btn-primary" disabled v-show="cargando"><img
                            src="{{ url('/spinner.gif') }}">&nbsp;Cargando</button>
                    <button class="btn btn-default" type="button" v-on:click="exportarExcelCumplidos()">
                        <img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel
                    </button>
                    <!--<a href="{{ url('/reporte_compromisos_cumplidos.xlsx') }}" class="hidden" type="button"
                        id="hrefCumplidosGenerado" target="_blank"></a>-->
                </span>
                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal" v-on:click='limpiarReporteCC()'
                    id="cerrar_reporte_cumplido" v-show="!cargando"><b><i class="fa fa-times"></i></b>&nbsp;Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
<!--MODAL PARA REPORTE DETALLADO-->
<div class="modal fade" id="modal-DETALLADO">
    <div class='modal-dialog modal-xs' style="min-width: 20%!important;">
        <div class="modal-content">
            <div class="modal-header" style="text-align:center">
                <label style="font-size:20px;text-align:center">Reporte Detallado</label>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Compromiso:</label>
                        <div class="input-group">
                            <select class="form-control form-control-sm select2" id="filtro_compromisos_detallado">
                                <option value="" selected>SELECCIONE UN COMPROMISO</option>
                                <option v-for="value in arrayCompromisos" :value="value.id"
                                    v-text="value.nombre_compromiso">
                                </option>
                            </select>
                        </div>
                    </div>
                    <!--<a href="{{ url('/reporte_compromiso.xlsx') }}" class="hidden" type="button"
                        id="hrefGenerado" target="_blank"></a>-->
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="btn btn-primary" disabled v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Cargando</button>
                <button class="btn btn-success btn-sm" style="background:#17A2B7;border:0px"
                    v-on:click="mostrarFiltroCompromiso = true;consultaReporteEjecutivo(false);">
                    <b><img src="/images/icons/excel.png" width="20px" heigh="15px"></b>&nbsp;Exportar Excel
                </button>
                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal"
                    v-on:click='limpiarReporteEjecutivo()' id="cerrar_reporte_detallado" v-show="!cargando"><b><i
                            class="fa fa-times"></i></b>&nbsp;Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
<!--MODAL PARA REPORTE EJECUTIVO-->
<div class="modal fade" id="modal-EJECUTIVO">
    <div class='modal-dialog modal-xs' style="min-width: 20%!important;">
        <div class="modal-content">
            <div class="modal-header" style="text-align:center">
                <label style="font-size:20px;text-align:center">Reporte Ejecutivo</label>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!--FIN FILTRO PASTEL Y PERZONALIZADO-->
                    <div class="col-md-4 offset-md-1" style="float:right;padding-right: 0px;padding-left: 0px;width:20%"
                        v-show="!mostrarFiltroPeriodo" v-on:click="mostrar_periodo()">
                        <button class="btn btn-primary btn-block  btn-sm">&nbsp;POR PERIODO</button>
                    </div>
                    <div class="col-md-4 offset-md-1"
                        style="float:right;padding-right: 0px; padding-left: 0px;width:20%"
                        v-show="mostrarFiltroPeriodo">
                        <button class="btn btn-info btn-block btn-sm" disabled>&nbsp;POR PERIODO</button>
                    </div>
                    <div class="col-md-4 offset-md-1" style="float:right;padding-right: 0px;padding-left: 0px;width:20%"
                        v-show="!mostrarFiltroCompromiso" v-on:click="mostrar_compromiso()">
                        <button class="btn btn-primary btn-block  btn-sm">&nbsp;POR COMPROMISO</button>
                    </div>
                    <div class="col-md-4 offset-md-1"
                        style="float:right;padding-right: 0px; padding-left: 0px;width:20%"
                        v-show="mostrarFiltroCompromiso">
                        <button class="btn btn-info btn-block btn-sm" disabled>&nbsp;POR COMPROMISO</button>
                    </div>
                    <!--FIN PESTAÑAS-->
                    <div class="col-md-4 offset-md-4" v-show="mostrarFiltroPeriodo"><br>
                        <input type="checkbox" name="chk_periodo_actual" id="chk_periodo_actual"
                            v-on:click="mostrar_periodo_actual()">
                        <label>PERIODO ACTUAL</label>
                    </div>
                    <div class="col-md-12" v-show="mostrarFiltroPeriodo">
                        <div class="input-group">
                            <select class="form-control form-control-sm select2" id="filtro_periodos">
                                <option value="" selected>SELECCIONE EL PERIODO</option>
                                <option v-for="value in arrayPeriodo" :value="value.id"
                                    v-text="value.periodo_compromiso">
                                </option>
                            </select>
                        </div>
                    </div>

                    <!--
                    <div class="col-md-12" v-show="mostrarFiltroPeriodo">
                        <label>Fecha inicio de periodo:</label>
                        <div class="input-group">
                            <input type="date" class="form-control form-control-sm" id="fecha_inicio_ejecutivo">
                        </div>
                    </div>
                    <div class="col-md-12" v-show="mostrarFiltroPeriodo">
                        <label>Fecha Fin de periodo:</label>
                        <div class="input-group">
                            <input type="date" class="form-control form-control-sm" id="fecha_fin_ejecutivo">
                        </div>
                    </div>
                    -->
                    <div class="col-md-12" v-show="mostrarFiltroCompromiso">
                        <label>Compromiso:</label>
                        <div class="input-group">
                            <select class="form-control form-control-sm select2" id="filtro_compromisos">
                                <option value="" selected>SELECCIONE EL COMPROMISO</option>
                                <option v-for="value in arrayCompromisos" :value="value.id"
                                    v-text="value.nombre_compromiso">
                                </option>
                            </select>
                        </div>
                    </div>
                    <!--<a href="{{ url('/reporte_compromiso.xlsx') }}" class="hidden" type="button"
                        id="hrefGenerado" target="_blank"></a>-->
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="btn btn-primary" disabled v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Cargando</button>
                <button class="btn btn-success btn-sm" style="background:#17A2B7;border:0px"
                    v-on:click="consultaReporteEjecutivo(true)"
                    v-show="mostrarFiltroCompromiso || mostrarFiltroPeriodo">
                    <b><img src="/images/icons/excel.png" width="20px" heigh="15px"></b>&nbsp;Exportar Excel
                </button>
                <button class="btn btn-default btn-sm" v-on:click='limpiarReporteEjecutivo()' v-show="!cargando">
                    <b><i class="fas fa-eraser"></i></b>&nbsp;Reset
                </button>
                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal"
                    v-on:click='limpiarReporteEjecutivo()' id="cerrar_reporte_ejecutivo" v-show="!cargando"><b><i
                            class="fa fa-times"></i></b>&nbsp;Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
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
                        <label>Institución</label>
                        {!! Form::select('institucion', $cqlInstitucion, null, ['id' => 'filtro_inst', 'class' => 'form-control select2', 'placeholder' => 'SELECCIONE UNA INSTITUCIÓN']) !!}
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
                    <!--<a href="{{ url('/storage/COMPROMISOS_GENERADOS/MINISTERIO_COMPROMISOS_GENERADO.xlsx') }}" class="hidden" type="button"
                        id="hrefMinisterioGenerado" target="_blank"></a>-->
                </span>
                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal"
                    v-on:click='limpiarCompromisoMinisterio()' id="cerrar_reporte_ministerio" v-show="!cargando"><b><i
                            class="fa fa-times"></i></b>&nbsp;Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
<!--MODAL PARA REPORTE DE COMPROMISOS POR GABINETE-->
<div class="modal fade" id="modal-COMPROMISOS_GABINETE">
    <div class='modal-dialog modal-xs' style="min-width: 20%!important;">
        <div class="modal-content">
            <div class="modal-header" style="text-align:center">
                <label style="font-size:20px;text-align:center">Reporte Compromisos Gabinete</label>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Gabinete Sectorial</label>
                        {!! Form::select('gabinete', $cqlGabinete, null, ['id' => 'filtro_gab', 'class' => 'form-control select2', 'placeholder' => 'SELECCIONE UN GABINETE']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="btn btn-primary" disabled v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Cargando</button>
                <span class="input-group-btn">&nbsp;
                    <button class="btn btn-default" type="button" v-on:click="exportarExcelGabinete()">
                        <img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel
                    </button>
                    <!-- <a href="{{ url('/reporte_compromisos_gabinete.xlsx') }}" class="hidden" type="button"
                        id="hrefGabineteGenerado" target="_blank"></a>-->
                    <!--    <a href="{{ url('/storage/COMPROMISOS_GENERADOS/GABINETE_COMPROMISOS.xlsx') }}" class="hidden" type="button"
                        id="hrefGabineteGenerado" target="_blank"></a>-->
                </span>
                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal"
                    v-on:click='limpiarCompromisoGabinete()' id="cerrar_reporte_gabinete" v-show="!cargando"><b><i
                            class="fa fa-times"></i></b>&nbsp;Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
<!--MODAL PARA REPORTE DE MAPA DE CALOR POR COMPROMISO INDIVIDUAL-->
<div class="modal fade" id="modal-MAPA_CALOR1">
    <div class='modal-dialog modal-xs' style="min-width: 20%!important;">
        <div class="modal-content">
            <div class="modal-header" style="text-align:center">
                <label style="font-size:20px;text-align:center">Reporte Compromiso Individual</label>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label>Estado de Gestión</label>
                        {!! Form::select('gestion', $gestion_filtro, null, ['id' => 'filtro_gestion', 'class' => 'form-control select2', 'placeholder' => 'TODOS LOS ESTADOS']) !!}
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
                    <div class="col-md-12">
                        <label>Ubicación</label>
                        {!! Form::select('ubicacion', $ubicacion_filtro, null, ['id' => 'filtro_ubicacion', 'class' => 'form-control select2', 'placeholder' => 'TODAS LAS UBICACIONES']) !!}
                    </div>
                    <div class="col-md-12">
                        <label>Gabinete Sectorial:</label>
                        <div class="input-group">
                            <select class="form-control form-control-sm select2" id="filtro_gabinete_ind"
                                name="filtro_gabinete_ind">
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
                            <select class="form-control form-control-sm select2" id="filtro_institucion_ind" name="filtro_institucion_ind">
                                <option value="" selected>TODOS</option>
                                <option v-for="value in arrayInstitucion" :value="value.id"
                                    v-text="value.descripcion">
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="btn btn-primary" disabled v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Cargando</button>
                <!--<span class="input-group-btn">&nbsp;
                    <button class="btn btn-default hidden" type="button" v-on:click="exportarExcelMapa()">
                        <img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel
                    </button>
                    <a href="{{ url('/reporte_compromisos_mapa.xlsx') }}" class="hidden" type="button"
                        id="hrefCompromisoIndGenerado" target="_blank"></a>
                </span>-->
                <button class="btn btn-default" type="button" onclick="generarReporteMapa();" v-on:click="reporteDinamico_tc();">
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