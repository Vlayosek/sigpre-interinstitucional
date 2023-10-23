<div class="modal fade" id="modal-Usuario">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-body">

                <div class="col-sm-12">
                    <label>Cedula de identidad:</label>
                    <input type="text" class="form-control form-control-sm numero" id="identificacion"
                        v-model="formCrear.identificacion" maxlength="10">
                </div>
                <div class="col-sm-12">
                    <label>Nombres completos:</label>
                    <input type="text" class="form-control form-control-sm mayuscula_" id="nombres"
                        v-model="formCrear.nombres">
                </div>
                <div class="col-sm-12">
                    <label>Correo electrónico: <span name="errorCorreo"style="color:red"></span></label>
                    <input type="email" class="form-control correo form-control-sm minuscula_" id="email"
                        v-model="formCrear.email">
                        
                </div>
                <div class="col-sm-12">
                    <label>Cargo:</label>
                    <input type="text" class="form-control form-control-sm mayuscula_" id="cargo"
                        v-model="formCrear.cargo">
                </div>
                <div class="col-sm-12">
                    <label>Telefono:</label>
                    <input type="text" class="form-control form-control-sm " v-model="formCrear.extension">
                </div>
            
                <div class="col-md-12">
                    <label>Contrase&ntilde;a:</label>

                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm " v-model="formCrear.password" minlength="5">
                        <span class="input-group-btn">&nbsp;
                            <button class="btn btn-default btn-sm" type="button" v-on:click="generacion_clave()">
                               Generar</span>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="btn btn-primary" disabled v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Cargando
                </button>
                <button class="btn btn-primary btn-sm" v-show="!cargando" v-on:click="guardarUsuario()"><b><i
                            class="fa fa-save"></i></b>
                    Guardar Usuario</button>

                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal" id="cerrar_modal"
                    v-show="!cargando"><b><i class="fa fa-times"></i></b>
                    Cerrar</button>

            </div>
        </div>
    </div>
</div>
