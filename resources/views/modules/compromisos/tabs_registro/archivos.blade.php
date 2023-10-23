<div class="tab-pane" id="archivos">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div
                    :class="formCrear.cerrado == 'true' && rolMinistro == 1 ?
                        'hidden row' : 'row'">
                    <div class="col-sm-10 ">
                        <button id="BotonDatatableArchivo" onclick="datatableCargarArchivos()" class="hidden"></button>
                        <div class="form-group">
                            <label><strong>Cargar
                                    Archivos</strong></label>
                            <div class="custom-file">
                                <input type="file" name="files" multiple class="custom-file-input form-control"
                                    id="customFile">
                                <label class="custom-file-label" id="customfilelabel" for="customFile">Archivo</label>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-2">
                        <div class="row">
                            &nbsp;
                            <div class="col-md-12">
                                <button type="button" name="upload" class="btn btn-block btn-info"
                                    v-on:click="guardarArchivo()" v-show="!cargando">
                                    <i class="fa fa-save"></i>
                                    &nbsp;Guardar Archivos</button>
                                <button class="btn btn-primary btn-sm" disabled v-show="cargando"><img
                                        src="{{ url('/spinner.gif') }}">&nbsp;Guardando</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table table-responsive tablaConsulta">
                            <table class="table table-bordered table-striped" id="dtmenuArchivos"
                                style="width:100%!important">
                                <thead>
                                    <th>Fecha</th>
                                    <th>Nombre</th>
                                    <th>Emisor</th>
                                    <th>Instituci&oacute;n Emisor</th>
                                    <th>Fecha leido</th>
                                    <th>Receptor</th>
                                    <th>Instituci&oacute;n Receptor</th>
                                    <th width="20%"></th>
                                </thead>
                                <tbody id="tbobymenuArchivos" class="menu-pen">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
