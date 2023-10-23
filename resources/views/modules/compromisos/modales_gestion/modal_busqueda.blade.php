<div class="modal fade" id="modal-filtro-busqueda">
    <div class="modal-dialog  ">
        <div class="modal-content modal-content_">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">
                    Filtrado de Datos en Excel
                </h5>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Gabinete:</label>
                            <div class="input-group">
                                <select class="form-control form-control-sm select2" id="gabinete_id_busqueda"
                                    name="gabinete_id_busqueda">
                                    <option value="" selected>TODOS LOS GABINETES</option>
                                    <option v-for="value in arrayGabineteBusqueda" :value="value.id"
                                        v-text="value.descripcion">
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label>Institución responsable:</label>
                            <div class="input-group">
                                <select class="form-control form-control-sm select2" id="institucion_id_busqueda"
                                    name="institucion_id_busqueda">
                                    <option value="" selected>TODAS LAS INSTITUCIONES</option>
                                    <option v-for="value in arrayInstitucionBusqueda" :value="value.id"
                                        v-text="value.descripcion">
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label>Monitor:</label>
                            {!! Form::select('monitor_busqueda', $monitores, null, [
                                'placeholder' => 'TODOS LOS MONITORES',
                                'class' => 'form-control ',
                                'id' => 'monitor_busqueda',
                            ]) !!}
                        </div>
                        <span class="input-group-btn">&nbsp;

                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">

                <button class="btn btn-default" data-dismiss="modal" type="button"
                    onclick="datatableCompromisosBusquedas();app.filtro_busqueda=true">
                    <span class="fa fa-search">&nbsp;Buscar</span>
                </button>

                <button class="btn btn-default cerrarmodal" data-dismiss="modal"><b><i class="fa fa-times"></i></b>
                    Cerrar</button>
            </div>
        </div>
    </div>
</div>
