<div class="tab-pane " id="avances">
    <div class="container-fluid">
        <div class="row">
            <div :class="rolMinistro == 0 ? 'hidden' : 'col-md-12'">
                <div
                    :class="formCrear.cerrado == 'true' && rolMinistro == 1 ?
                        'hidden row' : 'row'">
                    <div class="col-md-10">
                        <div class="col-md-12">
                            <input type="hidden" value="0" id="idAvance" v-model="formAvance.idAvance">
                            <label>Avance </label>
                            <textarea class="form-control-t" id="avance" autocomplete="off" v-model="formAvance.descripcion" maxlength="500">
                            </textarea>
                            <span style="font-size:7px">(Maximo 500 Caracteres)</span>
                        </div>

                    </div>
                    <div class="col-md-2">
                        <div class="row">
                            &nbsp;
                            <div class="col-md-12">
                                <button type="button" name="upload" class="btn btn-block btn-info btnTopM"
                                    v-on:click="guardarAvance()" style="height:60px" v-show="!cargando">
                                    <i class="fa fa-save"></i>
                                    &nbsp;Guardar Avance</button>
                                <button class="btn btn-primary btn-sm" disabled v-show="cargando"><img
                                        src="{{ url('/spinner.gif') }}">&nbsp;Guardando</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="col-md-12">
                    <button id="BotonDatatableAvances" onclick="datatableCargarAvances()" class="hidden"></button>


                    <div class="table table-responsive tablaConsulta">
                        <table class="table table-bordered table-striped" id="dtmenuAvances"
                            style="width:100%!important">
                            <thead>
                                <th>N&uacute;mero</th>
                                <th>Fecha</th>
                                <th>Avance</th>
                                <th>Emisor</th>
                                <th>Instituci&oacute;n Emisor</th>
                                <th>Fecha leido</th>
                                <th>Receptor</th>
                                <th>Instituci&oacute;n Receptor</th>
                                <th>Motivo</th>
                                <th></th>
                            </thead>
                            <tbody id="tbobymenuAvances" class="menu-pen">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
