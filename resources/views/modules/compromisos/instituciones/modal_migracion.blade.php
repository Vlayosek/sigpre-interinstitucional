<div class="modal fade" id="modal-migracion-compromisos">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="col-md-12"> Instituci&oacute;n:</label>
                            <input type="text" name="identificacion_institucion" class="form-control form-control-sm"
                                v-model="formCompromiso.anterior_institucion" disabled>
                        </div>
                        <div class="col-sm-12">
                            <label>Nueva Institución:</label>
                            <select class="form-control select2 selectCompleto"
                                id="identificacion_institucion_"></select>
                        </div>
                        <div class="col-sm-12">
                            <label>Motivo:</label>
                            <textarea class="form-control" id="motivo" v-model="formCompromiso.motivo" rows="8"
                                placeholder="Ingrese Motivo de migración"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button class="btn btn-primary" disabled v-show="cargando"><img
                        src="{{ url('/spinner.gif') }}">&nbsp;Cargando
                </button>
                <button class="btn btn-primary btn-sm" v-on:click="migrarCompromisos()" v-show="!cargando"><b><i
                            class="fa fa-save"></i></b>
                    Migrar Compromisos</button>

                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal"
                    id="cerrar_modal_migracion_compromiso" v-show="!cargando"><b><i class="fa fa-times"></i></b>
                    Cerrar</button>

            </div>
        </div>
    </div>
</div>
