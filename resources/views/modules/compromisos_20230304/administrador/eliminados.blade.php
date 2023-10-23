<div class="col-md-12" v-show="tabEliminados" id="tab_eliminados">
    <div class="row">
        <!-- FILTROS BUSQUEDA -->
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <label>Institución:</label>
                    {!! Form::select('institucion_', $cqlInstitucion, null, [
                        'id' => 'filtro_institucion_',
                        'class' => 'form-control select2',
                        'placeholder' => 'SELECCIONE UNA INSTITUCIÓN',
                    ]) !!}
                </div>
                <div class="col-md-3">
                    <label>Fecha Inicio:</label>
                    <div class="input-group">
                        <input type="date" class="form-control" id="fecha_inicio" value="<?php echo date('Y-m-01'); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Fecha Fin:</label>
                    <div class="input-group">
                        <input type="date" class="form-control" id="fecha_fin" value="<?php echo date('Y-m-t'); ?>">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" onclick="datatableBuscarEliminados()">
                                <span class="fa fa-search"></span>&nbsp;Buscar
                                </button> 
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table table-responsive" id="tablaEliminados">
                <table class="table table-bordered table-striped" id="dtmenu_eliminados"
                    style="width:100%!important">
                    <thead>
                        <th>C&oacute;digo</th>
                        <th>Nombre del Compromiso</th>
                        <th>Instituci&oacute;n</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Eliminado</th>
                        <th>Motivo</th>
                    </thead>
                    <tbody id="tbobymenu" class="menu-pen">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>