<div class="tab-pane " id="cronograma">
    <div class="col-md-12 padding-colMD">
        <div class="row">
            <div :class="formCronograma.numero == '--' ? 'hidden' : 'col-md-4'"
                style="background: rgba(0, 0, 0, 0.04);
                padding-left: 30px;
                padding-right: 30px;
                right: 30px;
                left: 0px;">
                <div class="row">
                    <h5 class="col-md-12 padding-colMD"
                        v-text="'Periodo: '+formCronograma.numero">

                    </h5>

                    <div class="col-md-12 padding-colMD">
                        <label>Descripci&oacute;n:</label>
                        <textarea class="form-control" v-model="formCronograma.descripcion_meta"></textarea>
                    </div>
                    <div class="col-md-12 padding-colMD hidden">
                        <label>Caracterizaci&oacute;n:</label>
                        <textarea class="form-control" v-model="formCronograma.caracterizacion"></textarea>
                    </div>

                    <div class="col-md-12 padding-colMD">
                        <div class="row">
                            <h6 class="col-md-12 padding-colMD">
                                <strong>Periodo</strong>
                            </h6>
                            <hr />
                            <div class="col-md-4 padding-colMD">
                                <label>Meta:</label>
                                <input type="text"
                                    class="form-control decimal"
                                    v-model="formCronograma.meta_periodo"
                                    v-on:keyup="calcular">
                            </div>
                            <div class="col-md-4 padding-colMD">
                                <label>Cumplimiento:</label>
                                <input type="text"
                                    class="form-control decimal"
                                    v-model="formCronograma.cumplimiento_periodo"
                                    v-on:keyup="calcular">
                            </div>
                            <div class="col-md-4 padding-colMD">
                                <label>Pendiente:</label>
                                <input type="text"
                                    class="form-control numero"
                                    v-model="formCronograma.pendiente_periodo"
                                    disabled>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 padding-colMD">
                        <div class="row">
                            <h6 class="col-md-12 padding-colMD">
                                <strong>Acumulado</strong>
                            </h6>
                            <hr />
                            <div class="col-md-4 padding-colMD">
                                <label>Meta:</label>
                                <input type="text"
                                    class="form-control numero"
                                    v-model="formCronograma.meta_acumulada"
                                    disabled>
                            </div>
                            <div class="col-md-4 padding-colMD">
                                <label>Cumplimiento:</label>
                                <input type="text"
                                    class="form-control numero"
                                    v-model="formCronograma.cumplimiento_acumulado"
                                    disabled>
                            </div>
                            <div class="col-md-4 padding-colMD">
                                <label>Pendiente:</label>
                                <input type="text"
                                    class="form-control numero"
                                    v-model="formCronograma.pendiente_acumulado"
                                    disabled>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-12 padding-colMD">
                        <label>Observaci&oacute;n:</label>
                        <textarea class="form-control" v-model="formCronograma.observaciones"></textarea>
                    </div>
                    <div class="col-md-12 padding-colMD">
                        <br />
                    </div>
                    <div class="col-md-6 padding-colMD">
                        <button class="btn btn-block btn-default"
                            v-on:click="formCronograma.numero='--'">Ocultar</button>
                    </div>
                    @if (!$corresponsable)
                        <div class="col-md-6 padding-colMD">
                            <button class="btn btn-block btn-info"
                                v-on:click="guardarPeriodo()"><i
                                    class="fa fa-save"></i>&nbsp;Registrar
                                Cumplimiento</button>
                        </div>
                    @endif
                </div>

            </div>
            <div :class="formCronograma.numero == '--' ? ' col-md-12' : ' col-md-8'"
                style="padding:15px">
                <div class="row">
                    <div class="col-md-4">
                        <label>Seleccione un Objetivo:</label>
                        <select id="objetivo_id"
                            v-model='formCronograma.objetivo_id'
                            class='form-control'>
                            <option value="" selected>SELECCIONE UNA
                                OPCION
                            </option>
                            <option v-for="vv in arregloObjetivos"
                                :value="vv.id"
                                v-text="(vv.numero+'. '+vv.objetivo).toUpperCase()">
                            </option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <div class="table table-responsive tablaConsulta">
                            <button id="BotonDatatablePeriodos"
                                onclick="datatableCargarPeriodos()"
                                class="hidden"></button>

                            <table
                                class="table table-bordered table-striped"
                                id="dtmenuPeriodos"
                                style="width:100%!important">
                                <thead>
                                    <th></th>
                                    <th>#</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Temporalidad</th>
                                    <th>Descripcion</th>
                                    <th>Meta</th>
                                    <th>Cumplimiento</th>
                                    <th>Pendiente</th>
                                    <th>Meta Acumulada</th>
                                    <th>Cumplimiento acumulado</th>
                                    <th>% Avance Periodo</th>
                                    <th>% Avance/Meta Total</th>

                                </thead>
                                <tbody id="dtbodyPeriodos"
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
