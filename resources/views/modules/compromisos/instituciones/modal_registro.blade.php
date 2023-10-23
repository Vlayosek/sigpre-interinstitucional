<div class="modal fade" id="modal-INSTITUCIONES">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tabbable" id="tabs-223118">
                            <ul class="nav nav-tabs">
                                <li class="nav-item ">
                                    <a class="nav-link active" href="#tab1" data-toggle="tab"
                                        id="principal_tab">Instituci&oacute;n</a>
                                </li>
                                <li class="nav-item" v-show="!disabled_institucion">
                                    <a class="nav-link " href="#tab2" data-toggle="tab">Gabinete</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <div class="modal-body">

                                        <div class="col-sm-12">
                                            <label>Nueva Institución:</label>
                                            <input type="text" class="form-control form-control-sm mayuscula_"
                                                id="nombre" v-model="formCrear.nombre"
                                                :disabled="disabled_institucion">
                                        </div>
                                        <div class="col-sm-12">
                                            <label>Siglas:</label>
                                            <input type="text" class="form-control form-control-sm mayuscula_"
                                                id="siglas" v-model="formCrear.siglas"
                                                :disabled="disabled_institucion">
                                        </div>
                                        <div class="col-md-12">
                                            <label>Gabinete:</label>
                                            {!! Form::select('filtro_institucion', $cqlInstitucion, null, [
                                                ':disabled' => 'disabled_institucion',
                                                'v-model' => 'formCrear.institucion_id',
                                                'class' => 'select2 form-control
                                                                                        form-control-sm',
                                                'id' => 'filtro_institucion',
                                                'name' => 'filtro_institucion_',
                                                'placeholder' => 'SELECCIONE UNA OPCION',
                                            ]) !!}
                                        </div>
                                        <div class="col-md-12">
                                            <label>Ministros:</label>
                                            {!! Form::select('ministro_usuario_id', $usuarios_ministros, null, [
                                                'v-model' => 'formCrear.ministro_usuario_id',
                                                'class' => 'select2
                                                                                        form-control form-control-sm',
                                                'id' => 'ministro_usuario_id',
                                                'placeholder' => 'SELECCIONE UNA OPCION',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-end">
                                        <button class="btn btn-primary" disabled v-show="cargando"><img
                                                src="{{ url('/spinner.gif') }}">&nbsp;Cargando
                                        </button>
                                        <button class="btn btn-primary btn-sm" v-on:click="guardarInstitucion()"
                                            v-show="!cargando"><b><i class="fa fa-save"></i></b>
                                            Guardar Instituci&oacute;n</button>

                                        <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal"
                                            id="cerrar_modal_institucion" v-show="!cargando"><b><i
                                                    class="fa fa-times"></i></b>
                                            Cerrar</button>

                                    </div>
                                </div>
                                <div class="tab-pane" id="tab2">
                                    <div class="modal-body">

                                        <div class="col-sm-12">
                                            <label>Nuevo Gabinete:</label>
                                            <input type="text" class="form-control form-control-sm mayuscula_"
                                                id="nombre" v-model="formGabinete.nombre">
                                        </div>
                                        <div class="col-sm-12">
                                            <label>Siglas:</label>
                                            <input type="text" class="form-control form-control-sm mayuscula_"
                                                id="siglas" v-model="formGabinete.siglas">
                                        </div>

                                    </div>
                                    <div class="modal-footer justify-content-end">
                                        <button class="btn btn-primary" disabled v-show="cargando"><img
                                                src="{{ url('/spinner.gif') }}">&nbsp;Cargando
                                        </button>
                                        <button class="btn btn-primary btn-sm" v-on:click="guardarGabinete(edita=false)"
                                            v-show="!cargando"><b><i class="fa fa-save"></i></b>
                                            Guardar Gabinete</button>

                                        <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal"
                                            id="cerrar_modal_gabinete" v-show="!cargando"><b><i
                                                    class="fa fa-times"></i></b>
                                            Cerrar</button>

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
<div class="modal fade" id="modal-EDITA_GABINETE">
    <div class="modal-dialog modal-md" style="min-width: 20%!important;">
        <div class="modal-content">
            <div class="modal-header" style="background:#E9ECEF;text-align:center;">
                <div class="col-sm-12">
                    <label style="font-size:16px;">
                        EDITAR GABINETE
                    </label>
                </div>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    <label><span style="color:red">*</span>Gabinete:</label>
                    <input type="text" class="form-control form-control-sm" id="institucion"
                        v-model="formGabinete.nombre">
                </div>
                <div class="col-sm-12">
                    <label><span style="color:red">*</span>Siglas:</label>
                    <input type="text" class="form-control form-control-sm mayuscula_" id="nombres"
                        v-model="formGabinete.siglas">
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="btn btn-primary btn-sm" v-on:click="guardarGabinete(edita=true)"><b><i
                            class="fa fa-save"></i></b>
                    Guardar</button>

                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal"
                    id="cerrar_modal_gabinete_"><b><i class="fa fa-times"></i></b>
                    Cerrar</button>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-EDITAR_INSTITUCIONES">
    <div class="modal-dialog modal-md" style="min-width: 20%!important;">
        <div class="modal-content">
            <div class="modal-header" style="background:#E9ECEF;text-align:center;">
                <div class="col-sm-12">
                    <label style="font-size:16px;">
                        EDITAR INSTITUCI&Oacute;N
                    </label>
                </div>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    <label><span style="color:red">*</span>Institución:</label>
                    <input type="text" class="form-control form-control-sm" id="institucion"
                        v-model="formCrear.nombre">
                </div>
                <div class="col-sm-12">
                    <label><span style="color:red">*</span>Siglas:</label>
                    <input type="text" class="form-control form-control-sm mayuscula_" id="nombres"
                        v-model="formCrear.siglas">
                </div>

                <div class="col-md-12">
                    <label>Gabinete:</label>
                    {!! Form::select('filtro_gabinete', $cqlInstitucion, null, [
                        ':disabled' => 'disabled_institucion',
                        'class' => 'select2
                                        form-control form-control-sm',
                        'id' => 'filtro_gabinete',
                        'name' => 'filtro_gabinete_',
                        'placeholder' => 'SELECCIONE UNA OPCION',
                    ]) !!}
                </div>
                <div class="col-md-12">
                    <label>Ministros:</label>
                    {!! Form::select('filtro_ministro_id', $usuarios_ministros, null, [
                        'class' => 'select2 form-control form-control-sm',
                        'id' => 'filtro_ministro_id',
                        'placeholder' => 'SELECCIONE UNA OPCION',
                    ]) !!}
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="btn btn-primary btn-sm" v-on:click="guardarInstitucion()"><b><i
                            class="fa fa-save"></i></b>
                    Guardar</button>

                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal"
                    id="cerrar_modal_institucion_"><b><i class="fa fa-times"></i></b>
                    Cerrar</button>

            </div>
        </div>
    </div>
</div>
