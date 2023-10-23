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
                <button class="btn btn-primary " data-dismiss="modal" v-on:click="rechazarAvance()"><b><i
                            class="fa fa-save"></i></b>
                    Guardar Motivo</button>
                <button class="btn btn-default cerrarmodal" data-dismiss="modal"><b><i class="fa fa-times"></i></b>
                    Cerrar</button>
            </div>
        </div>
    </div>
</div>
