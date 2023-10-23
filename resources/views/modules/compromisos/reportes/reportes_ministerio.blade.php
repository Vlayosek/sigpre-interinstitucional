@extends('layouts.app')

@section('contentheader_title')
    Reportes
@endsection

@section('contentheader_description')
    Compromisos presidenciales por ministerio
@endsection

@section('css')
    <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/style_moderno2.css') }}" rel="stylesheet">
    <style>
        label {
            font-size: 14px;
        }
        .table{
            font-family: Calibri;
            font-size: 12pt;
        }
    </style>
@endsection
@section('javascript')
    <script src="{{ url('adminlte/plugins/datepicker/') }}/bootstrap-datepicker.js"></script>
    <!--<script src="{{ url('js/modules/compromisos/reportes/script_reportes_ministerio.js') }}"></script>-->
@endsection
@section('content')
    <div id="main">
        <div class="card">
            <div class="card-heading">
                <div class="col-md-12 btnTop">
                    <div class="row">
                        <div class="col-md-4">
                            <br/>
                            <h5 style="color:#223580;font-weight:bold;text-align:center" class="btnTop">Reporte por Ministerio</h5>
                            <div class="row">
                                <br>
                                <div class="col-12">
                                    <label>Institución</label>
                                    {!! Form::select('institucion', $institucion_filtro, null, ['id' => 'filtro_institucion', 'class' => 'form-control select2', 'placeholder' => 'SELECCIONE UNA INSTITUCIÓN']) !!}
                                    <!--<span class="input-group-btn">&nbsp;
                                        <button class="btn btn-default" type="button" onclick="datatableCargarReporteMinisterio()">
                                            <span class="fa fa-search">&nbsp;Buscar</span>
                                        </button>-->
                                        <span class="input-group-btn">&nbsp;
                                            <button class="btn btn-default" type="button"  v-on:click="exportarExcelMinisterio()">
                                                <img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel
                                            </button>
                                            <a href="{{url('/reporte_compromisos_ministerio.xlsx')}}" class="hidden" type="button"  id="hrefMinisterioGenerado" target="_blank"></a>
                                        </span>
                                </div>
                            </div>                        
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <br/>
                            <h5 style="color:#223580;font-weight:bold;text-align:center" class="btnTop">Reporte por Gabinete</h5>
                            <div class="row">
                                <br>
                                <div  class="col-12">
                                    <label>Gabinete</label>
                                    {!! Form::select('gabinete', $gabinete_filtro, null, ['id' => 'filtro_gabinete', 'class' => 'form-control select2', 'placeholder' => 'SELECCIONE UN GABINETE']) !!}
                                    <button class="btn btn-default" type="button"  v-on:click="exportarExcelGabinete()">
                                        <img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel
                                    </button>
                                    <a href="{{url('/reporte_compromisos_gabinete.xlsx')}}" class="hidden" type="button"  id="hrefGabineteGenerado" target="_blank"></a>
                                </div>
                            </div>                        
                        </div>
                        <br/>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>

    <script src="{{ url('js/vue.js') }}"></script>
    <script src="{{ url('js/axios.js') }}"></script>
    <script src="{{ url('js/modules/compromisos/reportes/vue_reportes_ministerio.js') }}"></script>
@endsection