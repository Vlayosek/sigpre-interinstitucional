<div class="tab-pane" id="historico">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <button id="BotonDatatableHistorico"
                            onclick="datatableCargarHistorico()"
                            class="hidden"></button>

                        <div class="table table-responsive tablaConsulta">
                            <table class="table table-bordered table-striped"
                                id="dtmenuHistorico" style="width:100%!important">
                                <thead>
                                    <th>Fecha</th>
                                    <th>Descripcion</th>
                                    <th>Usuario</th>
                                    <th>Institucion</th>
                                </thead>
                                <tbody id="tbobymenuHistorico" class="menu-pen">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
