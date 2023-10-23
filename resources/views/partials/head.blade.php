<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ config('app.name', 'Laravel') }}</title>
<link rel="stylesheet" href="{{ url('fonts/nunion.css') }}">
<link rel="stylesheet" href="{{ url('adminlte3/plugins/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="{{ url('adminlte3/dist/css/adminlte.min.css') }}">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link href="{{ url('adminlte/plugins/fileinput/fileinput.min.css') }}"rel="stylesheet">
<link href="{{ url('js/jquery/jqueryuitime.css') }}" rel="stylesheet" type="text/css">
<link href="{{ url('adminlte/plugins/notifications/pnotify.custom.min.css') }}" rel="stylesheet">
<link href="{{ url('adminlte/plugins/datatables/jquery.dataTables2.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ url('adminlte3/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ url('adminlte/plugins/datepicker/datepicker3.css') }}" rel="stylesheet" type="text/css">
<link href="{{ url('adminlte/plugins/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet"
    type="text/css">
<link href="{{ url('adminlte/plugins/datatables/select.dataTables.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ url('adminlte/plugins/datatables/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ url('adminlte/plugins/datatables/colReorder.dataTables.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ url('adminlte/plugins/datatables/responsive.dataTables.min.css') }}" rel="stylesheet" type="text/css">
<link href="{{ url('web-fonts-with-css/css/fontawesome-all.min.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ url('adminlte/plugins/daterange/daterangepicker.css') }}" />
<link href="{{ url('adminlte/plugins/fileinput/fileinput.min.css') }}"rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="{{ url('adminlte3/plugins/chosen/css/chosen.min.css') }}" rel="stylesheet">

<style>
    .button-numerico.qtyminus {
        margin-right: 0.3rem;
    }

    .button-numerico.qtyplus {
        margin-left: 0.3rem;
    }

    .pink {
        background: pink !important;
    }

    .chosen-container.chosen-container-multi {
        width: 100% !important;
    }

    .chosen-select {
        width: 100% !important;
    }

    /* .chosen-container-single .chosen-single {
      background: none !important;
      border-radius: 0px !important;
      border: 1px solid #ccc;
      box-shadow: none;
    }

    .chosen-container .chosen-drop {
      border-color: #ccc;
      box-shadow: none;
    }

    .chosen-container-active.chosen-with-drop .chosen-single {
      background-image:none;
      border: 1px solid #ccc;
      box-shadow: none;
    }*/
    table.dataTable.display tbody tr.odd>.sorting_3,
    table.dataTable.order-column.stripe tbody tr.odd>.sorting_3 {
        background-color: #ffbebe00 !important;
    }

    table.dataTable.display tbody tr:hover>.sorting_3,
    table.dataTable.order-column.hover tbody tr:hover>.sorting_3 {
        background-color: #ffbebe00 !important;
    }

    table.dataTable.display tbody tr:hover>.sorting_3 {
        background-color: #ffbebe00 !important;

    }

    table.dataTable.display tbody tr.even>.sorting_3,
    table.dataTable.order-column.stripe tbody tr.even>.sorting_3 {
        background-color: #fefefe00;
    }

    .label_marcacion_multiple {
        background: #fff;
        padding: 2px;
        padding-top: 2px !important;
        margin: 1px;
        border: 1px solid #d7d0d0;
    }

    .label_tele_multiple {
        background: #ec6616;
        color: #fff;
        padding: 1px;
        margin-bottom: 0px;
    }

    .label_pres_multiple {
        background: #2c8241;
        color: #fff;
        padding: 1px;
        margin-bottom: 0px;
    }

    .label_si_multiple {
        background: #acacac;
        color: #fff;
        padding: 1px;
        margin-bottom: 0px;
    }

    .label_tele {
        background: #ec6616;
        color: #fff;
        padding: 3px;
        margin-bottom: 0px;
    }

    .label_pres {
        background: #2c8241;
        color: #fff;
        padding: 3px;
        margin-bottom: 0px;
    }

    .label_si {
        background: #acacac;
        color: #fff;
        padding: 3px;
        margin-bottom: 0px;
    }

    .label_marcacion {
        background: #fff;
        padding: 3px;
        margin: 3px;
        border: 1px solid #d7d0d0;
    }

    .label_marcacion_normal {
        background: #fff;
        padding: 0px !important;
        margin: 0px;
        border: 1px solid #d7d0d0;
    }

    .tr_sf {
        background-color: rgba(255, 255, 255, 0);
        border: 0px;
    }
</style>
<style>
    .mayuscula {
        text-transform: uppercase;
    }

    .dataTables_processing {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 100%;
        height: 40px;
        margin-left: -50%;
        margin-top: -25px;
        padding-top: 20px;
        text-align: center;
        font-size: 1.2em;
        background-color: white;
        background: -webkit-gradient(linear, left top, right top, color-stop(0%, rgba(255, 255, 255, 0)), color-stop(25%, rgba(255, 255, 255, 0.9)), color-stop(75%, rgba(255, 255, 255, 0.9)), color-stop(100%, rgba(255, 255, 255, 0)));
        background: -webkit-linear-gradient(left, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.9) 25%, rgba(255, 255, 255, 0.9) 75%, rgba(255, 255, 255, 0) 100%);
        background: -moz-linear-gradient(left, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.9) 25%, rgba(255, 255, 255, 0.9) 75%, rgba(255, 255, 255, 0) 100%);
        background: -ms-linear-gradient(left, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.9) 25%, rgba(255, 255, 255, 0.9) 75%, rgba(255, 255, 255, 0) 100%);
        background: -o-linear-gradient(left, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.9) 25%, rgba(255, 255, 255, 0.9) 75%, rgba(255, 255, 255, 0) 100%);
        background: linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.9) 25%, rgba(255, 255, 255, 0.9) 75%, rgba(255, 255, 255, 0) 100%);
    }

    .label {
        top: 10px !important;
    }

    .modal {
        overflow-y: auto;
    }

    .modal {
        padding-right: 0 !important;
        margin-right: 0 !important;
    }

    .modal-open {
        overflow: auto;
        overflow-x: hidden;
    }

    .hidden {
        display: none !important;
    }

    .select2 {
        width: 100%;
    }

    .alert-primary {
        color: #132233;
        background-color: #007bff;
        border-color: #006fe6;
    }

    .alert-danger {
        color: #132233;

    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #3498db;
        border: 1px solid #aaa;
        border-radius: 4px;
        cursor: default;
        float: left;
        margin-right: 5px;
        margin-top: 5px;
        padding: 0 5px;
    }

    .select2-container .select2-selection--single {
        box-sizing: border-box;
        cursor: pointer;
        display: block;
        height: 35px;
        user-select: none;
        -webkit-user-select: none;
    }

    .btnTop {
        top: 5px
    }

    .form-control-t {
        height: 60px !important;
        width: 100%;
        font-size: 12px;
    }

    .select2 {
        text-transform: uppercase;
    }

    scroll {
        scroll-behavior: smooth;
        height: 377px;
        overflow: scroll;
    }

    .modal-open {
        overflow: scroll;
    }

    .modal-header {
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-align-items: flex-start;
        -ms-flex-align: start;
        align-items: flex-start;
        -webkit-justify-content: space-between;
        -ms-flex-pack: justify;
        justify-content: space-between;
        padding: 0.5rem;
        border-bottom: 1px solid #e9ecef;
        border-top-left-radius: calc(.3rem - 1px);
        border-top-right-radius: calc(.3rem - 1px);
    }
</style>
<style>
    .form-control-sb {

        border: 1px solid #ffffff !important;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 10px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .alert-warning {
        color: #fdfeff;
    }

    .link_seleccionado {
        color: #0056b3;
        font-weight: bold;
    }

    .nav-link,
    .nav-item {
        font-size: 14px;
    }

    .control-sidebar-dark {
        height: 200px !important;
        width: 300px !important;

    }

    .nav-item-personas {
        font-size: 12px !important;
        color: #fff;
    }

    /* #### Mobile Phones Portrait #### */
    @media screen and (max-device-width: 480px) and (orientation: portrait) {

        /* some CSS here */
        .main-header {
            margin-top: -10px;
        }

        .navbar-nav {
            padding-top: 5px;
        }

        .escritorio {
            display: block;
        }

        .nav-item-personas {
            font-size: 12px !important;
            color: #fff;
        }
    }

    /* #### Mobile Phones Landscape #### */
    @media screen and (max-device-width: 640px) and (orientation: landscape) {
        .main-header {
            margin-top: -10px;
        }

        .navbar-nav {
            padding-top: 5px;
        }

        /* some CSS here */
        .escritorio {
            display: none;
        }

        .movil {
            display: block;
        }

        .control-sidebar-dark {
            height: 100% !important;
            width: 100% !important;

        }

        .nav-item-personas {
            width: 100% !important;
            font-size: 12px !important;
            color: #fff;
        }
    }

    /* #### Mobile Phones Portrait or Landscape #### */
    @media screen and (max-device-width: 640px) {
        .main-header {
            margin-top: -10px;
        }

        .navbar-nav {
            padding-top: 5px;
        }

        /* some CSS here */
        .escritorio {
            display: none;
        }

        .movil {
            display: block;
        }

        .control-sidebar-dark {
            height: 100% !important;
            width: 100% !important;

        }

        .nav-item-personas {
            width: 100% !important;
            font-size: 12px !important;
            color: #fff;
        }
    }

    /* #### iPhone 4+ Portrait or Landscape #### */
    @media screen and (max-device-width: 480px) and (-webkit-min-device-pixel-ratio: 2) {
        .main-header {
            margin-top: -10px;
        }

        .navbar-nav {
            padding-top: 5px;
        }

        /* some CSS here */
        .escritorio {
            display: none;
        }

        .movil {
            display: block;
        }

        .control-sidebar-dark {
            height: 100% !important;
            width: 100% !important;

        }

        .nav-item-personas {
            width: 100% !important;
            font-size: 12px !important;
            color: #fff;
        }
    }

    /* #### Tablets Portrait or Landscape #### */
    @media screen and (min-device-width: 768px) and (max-device-width: 1024px) {
        .main-header {
            margin-top: -10px;
        }

        .navbar-nav {
            padding-top: 5px;
        }

        /* some CSS here */
        .escritorio {
            display: none;
        }

        .movil {
            display: block;
        }

        .control-sidebar-dark {
            height: 100% !important;
            width: 100% !important;

        }

        .nav-item-personas {
            width: 100% !important;
            font-size: 12px !important;
            color: #fff;
        }

    }

    .flex-column-movil {
        flex-direction: unset !important;
    }

    /* #### Desktops #### */
    @media screen and (min-width: 1024px) {
        .escritorio {
            display: block;
        }

        .movil {
            display: none;
        }

        .flex-column-movil {
            flex-direction: column !important;
        }

        .control-sidebar-dark {
            height: 200px !important;
            width: 300px !important;
        }

        .nav-item-personas {
            font-size: 12px !important;
            color: #fff;
        }

        /* some CSS here */
    }

    .form-control-t1 {
        height: 200px !important;
        width: 100%;
    }

    .outline {
        background-color: transparent;
        color: inherit;
        transition: all .25s;
    }

    label,
    th,
    span,
    td {
        font-size: 12px;
    }

    .card-title {
        font-size: 14px !important;
    }

    .card-header {
        padding: 8px !important;
    }
</style>

<style>
    .styled-table {
        border-collapse: collapse;
        margin: 0px;
        font-size: 0.9em;
        font-family: sans-serif;
        min-width: 400px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }

    .styled-table thead tr {
        background-color: #243e57;
        color: #ffffff;
        text-align: left;
    }

    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
    }

    .styled-table tbody tr {
        border-bottom: 1px solid #dddddd;
    }



    .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid #3498db24;
    }

    .styled-table tbody tr.active-row {
        font-weight: bold;
        color: #243e57;
    }


    .form-control {
        display: block;
        width: 100%;
        height: calc(2.25rem + 2px);
        padding: .375rem .75rem;
        font-size: 0.8rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: .25rem;
        box-shadow: inset 0 0 0 transparent;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    .brighttheme-info {
        background-color: #fefefe;
        border-color: #fefefe;
    }

    .brighttheme {
        border: 0px !important;
    }

    .ui-pnotify-container {
        padding: 0px;
        height: 100%;
        position: relative;
        left: 0;
        margin: 0;
        border-radius: 3px;
    }

    .ui-pnotify-title {
        border-bottom: 1px #ccc solid;
        padding: 6px;
        margin-bottom: 0px;
        background: #3498db;
        font-size: 16px;
        font-weight: bold;
        color: #ffffff;
    }

    .ui-pnotify-sticker {
        padding: 0px 5px;
        color: #ffffff;
        visibility: visible !important;

    }

    .ui-pnotify-closer {
        padding: 0px 15px;
        color: #ffffff;
        visibility: visible !important;
    }

    .ui-pnotify-text {
        font-size: 13px;
        padding: 10px;
    }

    .brighttheme-error {
        background-color: #ffffff;
    }

    .brighttheme-error>.ui-pnotify-title {
        border-bottom: 1px #ccc solid;
        padding: 6px;
        margin-bottom: 0px;
        background: #f39c12;
        font-size: 16px;
        font-weight: bold;
        color: #ffffff;
    }

    .brighttheme-error>.ui-pnotify-text {
        color: #000;
        font-size: 13px;
        padding: 10px;
    }

    .input-file {
        visibility: hidden;
        position: absolute;
    }

    .btnLetra {

        font-size: 14px !important;

    }

    .nav-link,
    .nav-item {
        font-size: 12px !important;
    }

    .mayuscula_ {
        text-transform: uppercase;
    }
</style>
<style>
    .nav-item-personas {
        font-size: 12px !important;
        color: #fff;
    }

    .nav-link-personas {
        font-size: 12px !important;
        color: #fff;
    }

    .menuAnexo {
        background: #0c005e;
    }

    a:hover {
        color: #ccc !important;
        text-decoration: none;
    }

    .file-normal {
        display: block !important;
    }
</style>
<style>
    .form-control-sm {
        height: 30px !important;
    }

    .h6-sm {
        font-size: 12px;
    }

    .colorCeldaEliminado {
        background-color: #e89191 !important;
        color: #ffffff !important;
    }

    .colorCelda {
        background-color: #0f678a87 !important;
        color: #ffffff !important;
    }

    .colorCeldaTemporal {
        background-color: #1170959c !important;
        color: #ffffff !important;
    }

    .content-header {
        padding: 15px .5rem;
        padding-top: 15px;
        padding-top: 0px;
    }

    .table-striped tbody tr:nth-of-type(2n+1) {
        background-color: rgba(255, 255, 255, 0.05);
    }

    .dataTables_wrapper {
        font-family: tahoma;
        font-size: 12px;
    }

    td {
        font-size: 12px;
    }

    .dtr-details {
        width: 100%;
    }

    .history-tl-container {
        font-family: "Roboto", sans-serif;
        width: 100%;
        margin: auto;
        display: block;
        position: relative;
    }

    .history-tl-container ul.tl {
        margin: 20px 0;
        padding: 0;
        display: inline-block;

    }

    .history-tl-container ul.tl li {
        list-style: none;
        margin: auto;
        margin-left: auto;
        margin-left: 200px;
        min-height: 30px;
        background: rgba(255, 255, 255, 0.1);
        border-left: 1px dashed #86D6FF;
        padding: 0 0 0px 30px;
        position: relative;
        font-size: 12px;
    }

    .history-tl-container ul.tl li:last-child {
        border-left: 0;
    }

    .history-tl-container ul.tl li::before {
        position: absolute;
        left: -18px;
        top: -5px;
        content: " ";
        border: 8px solid rgba(255, 255, 255, 0.74);
        border-radius: 500%;
        background: #258CC7;
        height: 20px;
        width: 20px;
        transition: all 500ms ease-in-out;

    }



    .history-tl-container ul.tl li.seleccionado::before {
        border-color: #258CC7;
        transition: all 1000ms ease-in-out;
    }

    ul.tl li .item-title {}

    ul.tl li .item-detail {
        color: rgba(0, 0, 0, 0.5);
        font-size: 12px;
    }

    ul.tl li .timestamp {
        color: #8D8D8D;
        position: absolute;
        width: 200px;
        left: -110%;
        text-align: right;
        font-size: 12px;
    }
</style>
@yield('css')
