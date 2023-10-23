<div class="tab-pane" id="mensajes">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div
                    :class="formCrear.cerrado == 'true' && rolMinistro == 1 ?
                        'hidden row' : 'row'">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Enviar Mensaje</label>
                            </div>

                                <div class="col-md-6" v-show="rolMinistro != 1">
                                    <label>Corresponsable</label>
                                    <input type="checkbox" name="chk_corresponsable" id="chk_corresponsable">
                                </div>
                        </div>
                        <div class="col-md-12">
                            <textarea class="form-control-t" id="mensaje" autocomplete="off" v-model="formMensaje.descripcion" maxlength="500">  </textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            &nbsp;
                            <div class="col-md-4">
                                <button type="button" class="btn btn-block btn-default btnTopM"
                                    v-on:click="limpiarMensajes()" style="height:60px;margin-top: 25px!important;"
                                    v-show="!cargando">
                                    <i class="fa fa-eraser"></i>
                                    &nbsp;Limpiar</button>
                            </div>
                            <div class="col-md-6">
                                <button type="button" name="upload" class="btn btn-block btn-info btnTopM"
                                    v-on:click="guardarMensaje()" style="height:60px;margin-top: 25px!important;"
                                    v-show="!cargando">
                                    <i class="fa fa-paper-plane"></i>
                                    &nbsp;Enviar Mensaje</button>
                                <button class="btn btn-primary btn-sm" disabled v-show="cargando"><img
                                        src="{{ url('/spinner.gif') }}">&nbsp;Guardando</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button id="BotonDatatableMensaje" onclick="datatableCargarMensajes()" class="hidden"></button>
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
