@extends('layouts.app')

@section('template_title')
Logs
@endsection
@section('contentheader_title')
 Modulo 
@endsection

@section('contentheader_description')
Logs
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                               Logs de Ingreso
                            </span>

                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success errores">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    <div class="col-sm-4">
                        <div class="row">
                            <div class="col-md-6">
                                <label>fecha inicio:</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" id="fecha_inicio"
                                        value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>fecha fin:</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" id="fecha_fin"
                                        value="<?php echo date('Y-m-d'); ?>">
                                    <span class="input-group-btn">&nbsp;
                                        <button class="btn btn-default" type="button"
                                            onclick="datatable()">
                                            <span class="fa fa-search">&nbsp;Buscar</span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="dtmenu">
                                <thead class="thead">
                                    <tr>
                                        <th>Fecha/Hora</th>
                                        <th>Nombre</th>
                                        <th>Cargo</th>
                                        <th>Cedula</th>
                                        <th>Ip</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                               Asignación de Roles
                            </span>

                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success errores">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                            <table class="table table-striped table-hover" id="dtmenuRegistrosRoles">
                                <thead class="thead">
                                    <tr>
                                        <th>Reg</th>
                                        <th>descripcion</th>
                                        <th>identificacion</th>
                                        <th>funcionario</th>
                                        <th>fecha_inserta</th>
                                        <th>funcionario_inserta</th>
                                        <th>usuario_inserta</th>
                                        <th>tipo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('javascript') 
    <script>
$(document).ready(function () {
    $(function () {
        $("body").addClass("sidebar-collapse");
        datatable();
        datatableRegistroRoles();
    });
});

function datatableRegistroRoles() {
   
  $("#dtmenuRegistrosRoles").dataTable({
        dom: 'lBfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/admin/getDatatableRegistroRolesServerSide",
        buttons: [{
                extend: 'excelHtml5',
                text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">',
                titleAttr: 'Excel'
            },

            {
                extend: 'pdfHtml5',
                text: '<img src="/images/icons/pdf.png" width="25px" heigh="25px">',
                titleAttr: 'PDF',
                orientation: 'landscape',
                title: 'DATOS REGISTRADOS',
                footer: true,
                pageSize: 'A4'
            },
        ],
        stateSave:true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [5, 10, 20, -1],
            [5, 10, 20, "TODOS"]
        ],
        "lengthChange": true,
        "searching": true,
        "language": {
            "search": "Buscar",
            "lengthMenu": "Mostrar _MENU_",
            "zeroRecords": "Lo sentimos, no encontramos lo que estas buscando",
            "info": "Motrar página _PAGE_ de _PAGES_ (_TOTAL_)",
            "infoEmpty": "Registros no encontrados",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "infoFiltered": "(Filtrado _TOTAL_  de _MAX_ registros totales)",
        },
        "order": [
            [0, "desc"]
        ],
        "columns": [

            {
                data: 'id',
                "width": "5%",
                "searchable": true,
              
            },
            {
                data: 'descripcion',
                "width": "5%",
                "searchable": true,
              

            },
            {
                data: 'identificacion',
                "width": "5%",
                "searchable": true,
              
            },
            {
                data: 'funcionario',
                "width": "5%",
                "searchable": true,
              
            },
            {
                data: 'fecha_inserta',
                "width": "5%",
                "searchable": true,
              
            },
            {
                data: 'funcionario_inserta',
                "width": "5%",
                "searchable": true,
              
            },
            {
                data: 'usuario_inserta',
                "width": "5%",
                "searchable": true,
              
            },
            {
                data: 'tipo',
                "width": "5%",
                "searchable": true,
              
            },
        ] 
         
    });
}
function datatable() {
    var fecha_inicio=$("#fecha_inicio").val();
    var fecha_fin=$("#fecha_fin").val();
    var error=validarFechasEntradas(fecha_inicio,fecha_fin,1);
    if(!error) return false;
  $("#dtmenu").dataTable({
        dom: 'lBfrtip',
        'destroy': true,
        serverSide: true,
        "ajax": "/admin/getDatatableLogsServerSide/"+fecha_inicio+"/"+fecha_fin+"/",
        buttons: [{
                extend: 'excelHtml5',
                text: '<img src="/images/icons/excel.png" width="25px" heigh="25px">',
                titleAttr: 'Excel'
            },

            {
                extend: 'pdfHtml5',
                text: '<img src="/images/icons/pdf.png" width="25px" heigh="25px">',
                titleAttr: 'PDF',
                orientation: 'landscape',
                title: 'SOLICITUDES REGISTRADOS',
                footer: true,
                pageSize: 'A4'
            },
        ],
        stateSave:true,
        responsive: true,
        "processing": true,
        "lengthMenu": [
            [5, 10, 20, -1],
            [5, 10, 20, "TODOS"]
        ],
        "lengthChange": true,
        "searching": true,
        "language": {
            "search": "Buscar",
            "lengthMenu": "Mostrar _MENU_",
            "zeroRecords": "Lo sentimos, no encontramos lo que estas buscando",
            "info": "Motrar página _PAGE_ de _PAGES_ (_TOTAL_)",
            "infoEmpty": "Registros no encontrados",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "infoFiltered": "(Filtrado _TOTAL_  de _MAX_ registros totales)",
        },
        "order": [
            [0, "desc"]
        ],
        "columns": [

            {
                data: 'fecha',
                "width": "5%",
                "searchable": true,
              
            },
            {
                data: 'nombre',
                "width": "5%",
                "searchable": true,
              

            },
            {
                data: 'cargo',
                "width": "5%",
                "searchable": true,
              
            },
            {
                data: 'cedula',
                "width": "5%",
                "searchable": true,
              
            },
            {
                data: 'ip_registro',
                "width": "5%",
                "searchable": true,
              
            },
        ] 
         
    });
}
    </script>
    @endsection
@endsection
