<div class="modal fade" id="modal-notificaciones">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background:#E9ECEF;text-align:center;">
                <h5 class="modal-title">Notificaciones de Compromisos</h5>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    <div class="table table-responsive" id="tablaConsulta">
                        <table class="table table-bordered table-striped" id="dtmenuNotCompromisos"
                            style="width:100%!important">
                            <thead>
                                <th>Codigo</th>
                                <th>Descripcion</th>
                                <th>Institucion</th>
                                <th>Compromiso</th>
                                <th>Estado</th>
                            </thead>
                            <tbody id="tbobymenu" class="menu-pen">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button class="btn btn-default" onclick="appNotificacion.cambiarEstadoLeido()" data-dismiss="modal"
                        id="cerrar_modal_notificaciones"><b><i class="fa fa-times"></i></b>
                        Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
