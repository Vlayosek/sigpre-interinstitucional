<div class="tab-pane" id="antecedentes">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <input type="hidden" value="0"
                        v-model="formAntecedente.idAntecedente"
                        id="idAntecedente">
                    <div class="col-md-3 " v-show="rolMinistro==0">

                        <div class="col-md-12">
                            <label for="name"
                                class="control-label col-sm-12">Fecha
                                :</label>

                            <input autocomplete="off" class="form-control"
                                type="date" id="fecha_antecedente"
                                v-model="formAntecedente.fecha_antecedente" />

                        </div>
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <label>Antecedente</label>

                                <input type="text"
                                    class="form-control col-sm-12"
                                    id="antecedente" autocomplete="off"
                                    v-model="formAntecedente.antecedente">

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" name="upload"
                                        class="btn btn-block btn-default"
                                        v-on:click="limpiarFormularios()">
                                        Limpiar</button>
                                </div>

                                <div class="col-md-6">
                                    <button type="button" name="upload"
                                        class="btn btn-block btn-info"
                                        v-on:click="guardarAntecedente()">
                                        <i class="fa fa-save"></i>
                                        &nbsp;Guardar</button>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div
                        :class="rolMinistro == 0 ? 'col-md-9' :
                            'col-md-12 padding40px'">
                        <button id="BotonDatatableAntecedentes"
                            onclick="datatableCargarAntecedentes()"
                            class="hidden"></button>

                        <div class="table table-responsive tablaConsulta">
                            <table
                                class="table table-bordered table-striped"
                                id="dtmenuAntecedentes"
                                style="width:100%!important">
                                <thead>
                                    <th>No</th>
                                    <th>Fecha</th>
                                    <th>Antecedente</th>
                                    <th width="20%"></th>
                                </thead>
                                <tbody id="tbobymenuAntecedentes"
                                    class="menu-pen">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
