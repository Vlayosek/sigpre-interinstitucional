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
                                        <button id="BotonDatatableMensaje" onclick="datatableCargarMensajes()"
                                            class="hidden"></button>
                                        <div class="table table-responsive tablaConsulta">
                                            <table class="table table-bordered table-striped" id="dtmenuMensajes"
                                                style="width:100%!important">
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
                                                <tbody id="tbobymenuMensajes" class="menu-pen">

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
        </div>
    </div>
</div>
