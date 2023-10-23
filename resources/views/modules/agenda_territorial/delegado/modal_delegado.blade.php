<div class="modal fade" id="modal-ASIGNAR_DELEGADO">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-body">
                    <!--<input type="hidden" id="id" v-model="formCrear.id" value="0">

                    <div class="col-md-12">
                        <label>Usuario:</label>
                        {!! Form::select('usuario_id', [], null, ['v-model' => 'formCrear.usuario_id', 'class' => 'form-control select2', 'required' => '', 'id' => 'usuario_id']) !!}
                    </div>-->
                    <div class="col-sm-12">
                        <label>Cedula de identidad:</label>
                        <input type="text" class="form-control form-control-sm numero" id="identificacion"
                        v-model="formDelegado.identificacion"> 
                    </div>
                    <div class="col-sm-12">
                        <label>Nombres completos:</label>
                        <input type="text" class="form-control form-control-sm mayuscula_" id="nombres"
                        v-model="formDelegado.nombres"> 
                    </div>
                    <div class="col-sm-12">
                        <label>Correo electrónico:</label>
                        <input type="email" class="form-control form-control-sm minuscula_" id="email"
                        v-model="formDelegado.email"> 
                    </div>
                    <div class="col-sm-12">
                        <label>Cargo:</label>
                        <input type="text" class="form-control form-control-sm mayuscula_" id="cargo"
                        v-model="formDelegado.cargo"> 
                    </div>
                    <div class="col-sm-12">
                        <label>Telefono:</label>
                        <input type="text" class="form-control form-control-sm " id="telefono"
                        v-model="formDelegado.telefono"> 
                    </div>
                    <div class="col-sm-12">
                        <label>Celular:</label>
                        <input type="text" class="form-control form-control-sm numero" id="celular"
                        v-model="formDelegado.celular"> 
                    </div>
                    <div class="col-md-12">
                        <label>Institucion:</label>
                        {!! Form::select('filtro_institucion', $cqlInstitucion, null, ['v-model' => 'formDelegado.institucion_id','class' => 'select2 form-control form-control-sm', 'id' => 'filtro_institucion', 'name'=>'filtro_institucion_','placeholder' => 'SELECCIONE UNA OPCION']) !!}
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button class="btn btn-primary" disabled v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Cargando
                </button>
                    <button class="btn btn-primary btn-sm"
                    v-on:click="guardarDelegado()" v-show="!cargando"><b><i
                            class="fa fa-save"></i></b>
                    Guardar</button>
                
                    <button class="btn btn-default btn-sm cerrarmodal"
                        data-dismiss="modal" id="cerrar_delegado" v-show="!cargando"><b><i
                                class="fa fa-times"></i></b>
                        Cerrar</button>
                    
                </div>
            </div>
        </div>
    </div>