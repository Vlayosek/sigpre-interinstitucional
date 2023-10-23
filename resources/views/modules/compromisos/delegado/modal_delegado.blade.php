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
                    <label><span style="color:red">*</span>Cédula de identidad:</label>
                    <input type="text" class="form-control form-control-sm numero" id="identificacion"
                    v-model="formDelegado.identificacion"> 
                </div>
                <div class="col-sm-12">
                    <label><span style="color:red">*</span>Nombres completos:</label>
                    <input type="text" class="form-control form-control-sm mayuscula_" id="nombres"
                    v-model="formDelegado.nombres"> 
                </div>
                <div class="col-sm-12">
                    <label><span style="color:red">*</span>Correo electrónico:</label>
                    <input type="email" class="form-control form-control-sm minuscula_" id="email"
                    v-model="formDelegado.email"> 
                </div>
                <div class="col-sm-12">
                    <label><span style="color:red">*</span>Cargo:</label>
                    <input type="text" class="form-control form-control-sm mayuscula_" id="cargo"
                    v-model="formDelegado.cargo"> 
                </div>
                <div class="col-sm-12">
                    <label><span style="color:red">*</span>Telefono:</label>
                    <input type="text" class="form-control form-control-sm " id="telefono"
                    v-model="formDelegado.telefono"> 
                </div>
                <div class="col-sm-12">
                    <label><span style="color:red">*</span>Celular:</label>
                    <input type="text" class="form-control form-control-sm numero" id="celular"
                    v-model="formDelegado.celular"> 
                </div>
                <div class="col-md-12">
                    <label><span style="color:red">*</span>Institución:</label>
                    {!! Form::select('filtro_institucion', $cqlInstitucion, null, ['v-model' => 'formDelegado.institucion_id','class' => 'select2 form-control form-control-sm', 'id' => 'filtro_institucion', 'name'=>'filtro_institucion_','placeholder' => 'SELECCIONE UNA OPCION']) !!}

                    <!--<select id="filtro_institucion__" class = 'select2 form-control form-control-sm hidden'
                         name = 'filtro_institucion__'>
                            <option value="" :selected="formDelegado.institucion_id==''">SELECCIONE UNA OPCION</option>
                            <option v-for="(value,index) in arregloInstituciones" :value="index" v-text="value" :selected="index==formDelegado.institucion_id"></option>
                    </select>-->
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="btn btn-primary btn-sm"
                v-on:click="guardarDelegadoInstitucion()"><b><i
                        class="fa fa-save"></i></b>
                Guardar</button>
            
                <button class="btn btn-default btn-sm cerrarmodal"
                    data-dismiss="modal" id="cerrar_delegado"><b><i
                            class="fa fa-times"></i></b>
                    Cerrar</button>
                
            </div>
        </div>
    </div>
</div>