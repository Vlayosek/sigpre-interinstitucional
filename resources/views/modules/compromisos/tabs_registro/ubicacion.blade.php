<div class="tab-pane " id="ubicacion">
    <div class="container-fluid">
        <div class="row">
            <div :class="rolMinistro == 1 ? 'hidden' : 'col-md-4'">
                <div class="row">
                    <div class="col-md-12">

                        <div class="card card-primary">
                            <div class="card-body p-0"
                                style="height:600px;overflow-y:scroll">
                                <div class="tree_main">
                                    <ul id="bs_main" class="main_ul">
                                        <li :id="'bs_' + valueProvincia.id"
                                            v-for="valueProvincia in arregloProvincias">
                                            <span class="abrirplus plus"
                                                :name="'bs_l_' + valueProvincia.id"
                                                onclick="abrirCantones(this)">&nbsp;</span>
                                            <input type="checkbox"
                                                :id="'c_bs_' + valueProvincia.id"
                                                name="ubicacion_"
                                                :value="valueProvincia.id"
                                                onclick="checkearCanton(this)">
                                            <span
                                                v-text="valueProvincia.descripcion"></span>
                                            <ul :id="'bs_l_' + valueProvincia.id"
                                                class="sub_ul hidden"
                                                name="ul_ubicaciones">
                                                <li :id="'bf_' + valueCanton.id"
                                                    v-for="valueCanton in valueProvincia.lista_detalle">
                                                    <span
                                                        class="abrirplus plus"
                                                        :name="'bf_l_' +
                                                        valueCanton.id"
                                                        onclick="abrirParroquias(this)">&nbsp;</span>
                                                    <input type="checkbox"
                                                        :id="'c_bs_' +
                                                        valueCanton.id"
                                                        name="ubicacion_"
                                                        :value="valueCanton.id"
                                                        onclick="checkearCanton(this)">
                                                    <span
                                                        v-text="valueCanton.descripcion">
                                                    </span>
                                                    <ul :id="'bf_l_' + valueCanton.id"
                                                        class="inner_ul hidden"
                                                        name="ul_ubicaciones">
                                                        <li :id="'io_' +
                                                        valueParroquia.id"
                                                            v-for="valueParroquia in valueCanton.lista_detalle"
                                                            class="li_cbs">
                                                            <input
                                                                type="checkbox"
                                                                :id="'c_bs_' +
                                                                valueParroquia
                                                                    .id"
                                                                name="ubicacion_"
                                                                :value="valueParroquia
                                                                    .id" />&nbsp;<span
                                                                v-text="valueParroquia.descripcion">
                                                            </span>
                                                        </li>
                                                    </ul>
                                                </li>

                                            </ul>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <button type="button" name="upload"
                            class="btn btn-block btn-info"
                            v-on:click="guardarUbicacion()">
                            <i class="fa fa-save"></i>
                            &nbsp;Guardar Ubicaci&oacute;n</button>
                    </div>
                </div>

            </div>
            <div :class="rolMinistro == 1 ? 'col-md-12' : 'col-md-8'">
                <button id="BotonDatatableUbicaciones"
                    onclick="datatableCargarUbicaciones()"
                    class="hidden"></button>


                <div class="table table-responsive tablaConsulta">
                    <table class="table table-bordered table-striped"
                        id="dtmenuUbicacion" style="width:100%!important">
                        <thead>
                            <th>Provincia</th>
                            <th>Ciudad</th>
                            <th>Parroquia</th>
                        </thead>
                        <tbody id="tbobymenuUbicacion" class="menu-pen">

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
