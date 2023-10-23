@section('css')
    <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/style_moderno.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte3/plugins/tui-calendar/css/tui-calendar.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte3/plugins/tui-calendar/css/tui-date-picker.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte3/plugins/tui-calendar/css/icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('adminlte3/plugins/fullcalendar/main.css') }}">
@endsection
<script src="{{ url('adminlte3/plugins/tui-calendar/js/tui-code-snippet.min.js') }}" defer></script>
<script src="{{ url('adminlte3/plugins/tui-calendar/js/tui-time-picker.min.js') }}" defer></script>
<script src="{{ url('adminlte3/plugins/tui-calendar/js/tui-date-picker.min.js') }}" defer></script>
<script src="{{ url('adminlte3/plugins/tui-calendar/js/tui-calendar.js') }}" defer></script>
<script src="{{ url('js/modules/compromisos/calendario/templates.js?v=4') }}" defer></script>
<script src="{{ url('js/modules/compromisos/calendario/calendario_finalizacion.js?v=7') }}" defer></script>
<script defer>
    $(function() {
        $(".ic-arrow-line-left_").addClass("fa fa-arrow-left");
        $(".ic-arrow-line-right_").addClass("fa fa-arrow-right");
    });
</script>
<div class="col-md-12" v-show="calendario_finalizacion" id="calendario_finalizacion">
    <div id="menu">
        <span id="menu-navi">
            <button type="button" class="btn btn-default btn-sm move-today_"
                data-action="move-today_">Hoy</button>
            <button type="button" class="btn btn-default btn-sm move-day_" data-action="move-prev">
                <i class="calendar-icon ic-arrow-line-left_" data-action="move-prev"></i>
            </button>

            <button type="button" class="btn btn-default btn-sm move-day_" data-action="move-next">
                <i class="calendar-icon ic-arrow-line-right_" data-action="move-next"></i>
            </button>
        </span>

        <span id="renderRange_f" class="render-range_f"></span>
        <span class="input-group-btn">&nbsp;
            <button class="btn btn-default" type="button" v-on:click="exportarExcelCalendarioReportes('_f')">
                <img src="/images/icons/excel.png" width="25px" heigh="25px">Exportar Excel
            </button>
        </span>
    </div>
    <div id="calendar_ending"></div>

    <div id="modal-compromiso-calendario-finalizacion" class="modal fade">
        <div class='modal-dialog modal-md' style="min-width: 30%!important;">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            &nbsp;
                            <label id="codigo_compromiso_f" style="font-size:14px;font-weight:bold;">
                            </label>
                        </div>
                        <div class="col-sm-12">
                            <label style="font-size:13px;font-weight:bold;">
                                Inicio:
                            </label>&nbsp;
                            <label id="inicio_compromiso_f"
                                style="font-size:14px;font-weight:normal;">
                            </label>
                        </div>
                        <div class="col-sm-12">
                            <label style="font-size:13px;font-weight:bold;">
                                Final:
                            </label>&nbsp;
                            <label id="final_compromiso_f" style="font-size:14px;font-weight:normal;">
                            </label>
                        </div>
                        <div class="col-sm-12">
                            <label style="font-size:13px;font-weight:bold;">
                                Detalle:
                            </label>&nbsp;
                            <label id="detalle_compromiso_f"
                                style="font-size:14px;font-weight:normal;">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-calendario-compromiso-finalizacion">
    <div class="modal-dialog modal-dialog-1  modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="text-align:center">
                <label style="font-size:20px;">Detalles de Compromiso</label>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class=" table ">

                        <table class="table table-bordered table-striped" id="dtCompromisoCalendarioFinalizacion"
                            style="width:100%!important">
                            <thead>

                            </thead>
                            <tbody id="tbobymenu" class="menu-pen">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">

                <button class="btn btn-default btn-sm cerrarmodal" data-dismiss="modal"
                    id="cerrar_detalle_compromisos_finalizacion"
                    v-show="!cargando"><b><i class="fa fa-times"></i></b>&nbsp;Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
<script src="{{ url('js/vue.js') }}" defer></script>
<script src="{{ url('js/axios.js') }}" defer> </script>
<script src="{{ url('js/modules/compromisos/calendario/vue_calendario.js?v=1') }}" ></script>
