<!DOCTYPE html>
<html lang="es">
<head>
  @include('partials.headSR')
	@laravelPWA

  <link href="/images/icons/splash-640x1136.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
  <link href="/images/icons/splash-750x1334.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
  <link href="/images/icons/splash-1242x2208.png" media="(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
  <link href="/images/icons/splash-1125x2436.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
  <link href="/images/icons/splash-828x1792.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
  <link href="/images/icons/splash-1242x2688.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
  <link href="/images/icons/splash-1536x2048.png" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
  <link href="/images/icons/splash-1668x2224.png" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
  <link href="/images/icons/splash-1668x2388.png" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
  <link href="/images/icons/splash-2048x2732.png" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />

      <link href="{{ url('adminlte/plugins/notifications/sweetalert.css') }}" rel="stylesheet">
      <link rel="stylesheet" href="{{ url('adminlte/plugins/fullcalendar/') }}/bower_components/fullcalendar/dist/fullcalendar.min.css">
      <link rel="stylesheet" href="{{ url('adminlte/plugins/fullcalendar/') }}/bower_components/fullcalendar/dist/fullcalendar.print.min.css" media="print">
      <link href="{{ url('adminlte/plugins/datepicker/') }}/datepicker3.css" rel="stylesheet">

      <script src="{{url('adminlte3/plugins/jquery/jquery.min.js')}}"></script>
      <script src="{{ url('adminlte/plugins/select2/') }}/select21.full.min.js"></script>

</head>

<body class="hold-transition sidebar-mini" >
  <input type="hidden"id="direccionDocumentos" name="direccionDocumentos" value="{{ url('storage/') }}">

<div class="wrapper">
  @include('partials.topbar')
  @include('partials.sidebar')
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">@yield('contentheader_title')</a></li>
              <li class="breadcrumb-item active">@yield('contentheader_description')</li>
            </ol>
          </div><!-- /.col -->
          <div class="col-sm-12">
            <div class="row">
              <div class="col-md-12">

                  @if (Session::has('message'))
                      <div class="note note-info">
                          <p>{{ Session::get('message') }}</p>
                      </div>
                  @endif
                  @if ($errors->count() > 0)
                      <div class="note note-danger alert alert-danger">
                          <ul class="list-unstyled">
                              @foreach($errors->all() as $error)
                                  <li>- {{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                  @endif
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <span id="texto_usuario_nombreCompleto" class="hidden">{!! str_replace("CN=","",explode(",",Auth::user()->nombreCompleto)[0]) !!}</span>
      <div class="">
        <div class="">
         
          @yield('content')
        </div>
      </div>
    </div>
  </div>

  <aside class="control-sidebar control-sidebar-dark">
  </aside>

</div>
<script src="{{url('adminlte3/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{url('adminlte3/dist/js/adminlte.js')}}"></script>
<script src="{{url('adminlte3/dist/js/demo.js')}}"></script>

@include('partials.javascriptsSR')
    <script>
       
        $('.pickadate').datepicker({
            formatSubmit: 'yyyy-mm-dd',
            format: 'yyyy-mm-dd',
            selectYears: true,
            editable: true,
            autoclose: true,
            todayHighlight: true,
            orientation: 'top'
        }).datepicker('update', new Date());
        
    </script>
<script src="{{ url('adminlte/plugins/fileinput/fileinput.min.js') }}"></script>
<script src="{{ url('serviceworker.js') }}"></script>
<script>
        var base_url = '{{ url("/") }}';
</script>
<input type="hidden" name="_token"  value="{{ csrf_token() }}">

</body>
</html>
