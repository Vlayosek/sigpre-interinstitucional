<div class="col-md-12">
    <div class="row">
        <div class="col-md-10">
            <div class="row">
                <div class="col-md-12"
                    style="padding-bottom:0px!important;padding-top:0px!important">
                    <div class="col-12 col-sm-9 col-md-2 float-left">
                        <div class="info-box info-box-t">
                            <span
                                :class="currentTab === 0 ?
                                    'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo' :
                                    'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                v-text="registrados">
                            </span>
                            <div
                                :class="currentTab === 0 ? 'info-box-content btnActivo' :
                                    'info-box-content'">
                                <a href="#" id="estado_inicial" class="info-box-text h6 estados_gestiones ACT"
                                    v-on:click="currentTab = 0;"
                                    :class="{ link_seleccionado: currentTab === 0 }"
                                    onclick="datatableCargar('ACT')">
                                    Registrados</a>
                            </div>
                        </div>
                        <!-- /.info-box -->
                    </div>

                </div>
                <div class="col-md-12"
                    style="padding-bottom:0px!important;padding-top:0px!important">
                    <div class="">
                        <div class="col-12 col-sm-9 col-md-2 float-left">
                            <div class="info-box info-box-t">
                                <span
                                    :class="currentTab === 7 ?
                                        'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo' :
                                        'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                    v-text="planificacion">
                                </span>
                                <div
                                    :class="currentTab === 7 ? 'info-box-content btnActivo' :
                                        'info-box-content'">
                                    <a href="#" class="info-box-text h6 estados_gestiones PLA"
                                        v-on:click="currentTab = 7;"
                                        :class="{ link_seleccionado: currentTab === 7 }"
                                        onclick="datatableCargar('PLA',1)">
                                        En Planificaci&oacute;n</a>
                                </div>
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-9 col-md-2 float-left"
                            style="background:#007bff17">
                            <div class="info-box info-box-t">
                                <span
                                    :class="currentTab === 6 ?
                                        'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo' :
                                        'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                    v-text="ejecucion">
                                </span>
                                <div
                                    :class="currentTab === 6 ? 'info-box-content btnActivo' :
                                        'info-box-content'">
                                    <a href="#" class="info-box-text h6 estados_gestiones EJE"
                                        v-on:click="currentTab = 6;"
                                        :class="{ link_seleccionado: currentTab === 6 }"
                                        onclick="datatableCargar('EJE',1)">
                                        En Ejecuci&oacute;n</a>
                                </div>
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-9 col-md-2 float-left">
                            <div class="info-box info-box-t">
                                <span
                                    :class="currentTab === 10 ?
                                        'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo' :
                                        'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                    v-text="standby" style="color: #ffffff!important;">
                                </span>
                                <div
                                    :class="currentTab === 10 ? 'info-box-content btnActivo' :
                                        'info-box-content'">
                                    <a href="#" class="info-box-text h6 estados_gestiones STA"
                                        v-on:click="currentTab = 10;"
                                        :class="{ link_seleccionado: currentTab === 10 }"
                                        onclick="datatableCargar('STA',1)">
                                        Stand By</a>
                                </div>
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-9 col-md-2 float-left">
                            <div class="info-box info-box-t">
                                <span
                                    :class="currentTab === 9 ?
                                        'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo' :
                                        'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                    v-text="cerrado">
                                </span>
                                <div
                                    :class="currentTab === 9 ? 'info-box-content btnActivo' :
                                        'info-box-content'">
                                    <a href="#" class="info-box-text h6 estados_gestiones CER"
                                        v-on:click="currentTab = 9;"
                                        :class="{ link_seleccionado: currentTab === 9 }"
                                        onclick="datatableCargar('CER',1)">
                                        Cerrado</a>
                                </div>
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <div class="col-12 col-sm-9 col-md-2 float-left">
                            <div class="info-box info-box-t">
                                <span
                                    :class="currentTab === 8 ?
                                        'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo' :
                                        'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                    v-text="cumplido">
                                </span>
                                <div
                                    :class="currentTab === 8 ? 'info-box-content btnActivo' :
                                        'info-box-content'">
                                    <a href="#" class="info-box-text h6 estados_gestiones CUM"
                                        v-on:click="currentTab = 8;"
                                        :class="{ link_seleccionado: currentTab === 8 }"
                                        onclick="datatableCargar('CUM',1)">
                                        Cumplido</a>
                                </div>
                            </div>
                            <!-- /.info-box -->
                        </div>
                    </div>
                </div>
                <div class="col-md-12"
                    style="padding-bottom:0px!important;padding-top:0px!important;background:#007bff17">
                    <div class="col-12 col-sm-9 col-md-2 float-left hidden">
                        <div class="info-box info-box-t">
                            <span
                                :class="currentTab === 1 ?
                                    'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo' :
                                    'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                v-text="optimo">
                            </span>
                            <div
                                :class="currentTab === 1 ? 'info-box-content btnActivo' :
                                    'info-box-content'">
                                <a href="#" class="info-box-text h6 estados_gestiones OPT"
                                    v-on:click="currentTab = 1;"
                                    :class="{ link_seleccionado: currentTab === 1 }"
                                    onclick="datatableCargar('OPT',2)">
                                    Óptimo</a>
                            </div>
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <div class="col-12 col-sm-9 col-md-2 float-left">
                        <div class="info-box info-box-t">
                            <span
                                :class="currentTab === 2 ?
                                    'info-box-icon info-box-icon-t bg-primary elevation-1 btnActivo' :
                                    'info-box-icon info-box-icon-t bg-primary elevation-1 '"
                                v-text="bueno">
                            </span>
                            <div
                                :class="currentTab === 2 ? 'info-box-content btnActivo' :
                                    'info-box-content'">
                                <a href="#" class="info-box-text h6 estados_gestiones BUE"
                                    v-on:click="currentTab = 2;"
                                    :class="{ link_seleccionado: currentTab === 2 }"
                                    onclick="datatableCargar('BUE',2)">
                                    Bueno</a>
                            </div>
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <div class="col-12 col-sm-9 col-md-2 float-left">
                        <div class="info-box info-box-t">
                            <span
                                :class="currentTab === 3 ?
                                    'info-box-icon info-box-icon-t bg-warning elevation-1 btnActivo' :
                                    'info-box-icon info-box-icon-t bg-warning elevation-1 '"
                                v-text="leve" style="color: #ffffff!important;">
                            </span>
                            <div
                                :class="currentTab === 3 ? 'info-box-content btnActivo' :
                                    'info-box-content'">
                                <a href="#" class="info-box-text h6 estados_gestiones LEV"
                                    v-on:click="currentTab = 3;"
                                    :class="{ link_seleccionado: currentTab === 3 }"
                                    onclick="datatableCargar('LEV',2)">
                                    Atraso Leve</a>
                            </div>
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <div class="col-12 col-sm-9 col-md-2 float-left">
                        <div class="info-box info-box-t">
                            <span
                                :class="currentTab === 4 ?
                                    'info-box-icon info-box-icon-t bg-warning elevation-1 btnActivo' :
                                    'info-box-icon info-box-icon-t bg-warning elevation-1 '"
                                v-text="moderado" style="color: #ffffff!important;">
                            </span>
                            <div
                                :class="currentTab === 4 ? 'info-box-content btnActivo' :
                                    'info-box-content'">
                                <a href="#" class="info-box-text h6 estados_gestiones MOD"
                                    v-on:click="currentTab = 4;"
                                    :class="{ link_seleccionado: currentTab === 4 }"
                                    onclick="datatableCargar('MOD',2)">
                                    Atraso Moderado</a>
                            </div>
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <div class="col-12 col-sm-9 col-md-2 float-left">
                        <div class="info-box info-box-t">
                            <span
                                :class="currentTab === 5 ?
                                    'info-box-icon info-box-icon-t bg-danger elevation-1 btnActivo' :
                                    'info-box-icon info-box-icon-t bg-danger elevation-1 '"
                                v-text="grave">
                            </span>
                            <div
                                :class="currentTab === 5 ? 'info-box-content btnActivo' :
                                    'info-box-content'">
                                <a href="#" class="info-box-text h6 estados_gestiones GRA"
                                    v-on:click="currentTab = 5;"
                                    :class="{ link_seleccionado: currentTab === 5 }"
                                    onclick="datatableCargar('GRA',2)">
                                    Atraso Grave</a>
                            </div>
                        </div>
                        <!-- /.info-box -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="row">
                <div :class="rolMinistro == 0 ? 'col-md-12' : 'hidden'">
                    <div class="info-box info-box-t">
                        <span
                            :class="btnTemporal ?
                                'info-box-icon info-box-icon-t bg-primary  btnActivo' :
                                'info-box-icon info-box-icon-t bg-primary elevation-1'"
                            v-text="temporales_">
                        </span>
                        <div
                            :class="btnTemporal ? 'info-box-content btnActivo' :
                                'info-box-content'">
                            <a href="#" class="info-box-text h6"
                                v-on:click="currentTab_ = 13;buscarTemporales()"
                                :class="{ link_seleccionado: currentTab_ === 13 }">
                                Temporales</a>
                        </div>
                    </div>
                    <!-- /.info-box -->
                </div>
                <div :class="rolMinistro == 0 ? 'col-md-12' : 'hidden'">
                    <div class="info-box info-box-t">
                        <span
                            :class="asignaciones ?
                                'info-box-icon info-box-icon-t bg-primary  btnActivo' :
                                'info-box-icon info-box-icon-t bg-primary elevation-1'"
                            v-text="asignaciones_">
                        </span>
                        <div
                            :class="asignaciones ? 'info-box-content btnActivo' :
                                'info-box-content'">
                            <a href="#" class="info-box-text h6"
                                v-on:click="currentTab_ = 11;buscarAsignaciones()"
                                :class="{ link_seleccionado: currentTab_ === 11 }">
                                Mis asignaciones</a>
                        </div>
                    </div>
                    <!-- /.info-box -->
                </div>
                <div :class="rolMinistro == 0 ? 'col-md-12' : 'hidden'">
                    <div class="info-box info-box-t">
                        <span
                            :class="btnPendientes ?
                                'info-box-icon info-box-icon-t bg-primary btnActivo' :
                                'info-box-icon info-box-icon-t bg-primary elevation-1'"
                            v-text="pendientes_">
                        </span>
                        <div
                            :class="btnPendientes ? 'info-box-content btnActivo' :
                                'info-box-content'">
                            <a href="#" class="info-box-text h6 estados_gestiones"
                                v-on:click="currentTab_ = 12; buscarPendientes()"
                                :class="{ link_seleccionado: currentTab_ === 12 }">
                                Mis Pendientes</a>
                        </div>
                    </div>
                    <!-- /.info-box -->
                </div>
            </div>

        </div>
    </div>
</div>
