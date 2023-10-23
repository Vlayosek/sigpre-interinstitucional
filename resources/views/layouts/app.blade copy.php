<!DOCTYPE html>
<html lang="es">

<head>

    @include('partials.head')
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

  
    <style>
    .contenedor_talento_humano{
      width:90px;
      height:240px;
      position:fixed;
      right:0px;
      bottom:0px;
    }
    
    .botonF1 {
    width: 150px;
    height: 50px;
    border-radius: 10%;
    background: #ffaa0e;
    right: 0;
    bottom: 0;
    position: absolute;
    margin-right: 0px;
    margin-bottom: 6px;
    border: none;
    outline: none;
    color: #FFF;
    font-size: 20px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
    transition: .3s;
}
span{
  transition:.5s;  
}
.botonF1:hover span{
  transform:rotate(360deg);
}
.botonF1:active{
  transform:scale(1.1);
}
.btnTalento{
  width:60px;
  height:60px;
  border-radius:100%;
  border:none;
  color:#FFF;
  box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
  font-size:28px;
  outline:none;
  position:absolute;
  right:0;
  bottom:0;
  margin-right:26px;
  transform:scale(0);
}
.botonF2{
  background:#ffaa0e;
  margin-bottom:85px;
  transition:0.5s;
}
.botonF3{
  background:#ffaa0e;
  margin-bottom:150px;
  transition:0.7s;
}
.botonF4{
  background:#ffaa0e;
  margin-bottom:215px;
  transition:0.9s;
}
.botonF5{
  background:#ffaa0e;
  margin-bottom:280px;
  transition:0.99s;
}
.animacionVer{
  transform:scale(1);
}


    .form-control-alterna {
        padding: 0px!important;
    }
    .fc-addEventButton-button{
      display:none!important;
    }
    .content-header{
      display:none!important;
    }
    .fc-toolbar .fc-center {
    text-transform: uppercase!important;
  }
  .input-group-addon {
    padding: 3px 5px;
    font-size: 12px;
    font-weight: 400;
    line-height: 1;
    color: #555;
    text-align: center;
    background-color: #eee;
    border: 1px solid #ccc;
    border-radius: 4px;
}
.content {
    padding-top: 0px!important;
}
.box.box-primary {
    border-top-color: #1921232e!important;
}

.fc-header-toolbar {
    background: #1e4e6a!important;
    color: #fff;
    padding: 10px;
}

.fc-toolbar {
    padding-top: 10px!important;
}
    .fc-center h2{
      font-size:20px;
    }
  

  table {border-collapse:collapse; border: none;}
  td {padding: 0;}
  table.tabla_sin {
    border-collapse:collapse;
    border: none;
  }


    td.celda_sin {
      padding: 0;
    }
    .dateForm{
      line-height: 10px!important;
    }
    .contenedor223{
            width: 50%;
            height: 0%;
            top: 85px;
            position: fixed;
            z-index: 1050;
    }
.conte223{
       width: 60%!important;

}
.botonF123:hover span{
  transform:rotate(360deg);
}
.botonF123:active{
  transform:scale(1.1);
}
.animacionVer{
  transform:scale(1);
}

.botonF123{
    width: 300px;
    height: 90px;
    border-radius: 5%;
    background: #2196f37d;
    right: 0;
    bottom: 0;
    position: absolute;
    margin-right: 16px;
    margin-bottom: 0px;
    border: none;
    outline: none;
    color: #FFF;
    font-size: 30px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
    transition: .3s;

}
span{
  transition:.5s;  
}
.fc-week
{
  min-height:80px!important;
}
.form-control-t-agenda{
  height: 40px!important;
  border: 1px solid #d2d2d2;
  width:100%;
}
.form-control-agenda {
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    border: 1px solid #d2d2d2;
}
.yellow-marcacion {
    background-color: #fbffeb!important;
}
    </style>
</head>


<body class="hold-transition skin-blue sidebar-mini">

<div id="wrapper">

@include('partials.topbar')
@include('partials.sidebar')
		
			<div class="modal fade" id="modal-container-10292" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 2000;">
				<div class="modal-dialog" role="document"  style="min-width: 95%!important;">
					<div class="modal-content" style="min-width: 100%!important;">
						<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">
								Actividades
							</h5> 
					
						</div>
						<div class="modal-body">
                <div class="row">
                        <div class="col-md-3" style="background:#f6f2f2;height:60 0px">
                            
                          
                            <!-- /btn-group -->
                                <div class="box box-primary">
                                  <div class="box-body">
                                  <input type="hidden" id="idCalendarioAct" value="0">
                                     <div class="col-md-12" id="crearnuevoAca">
                                         <button type="button" class="btn btn-primary btn-xs col-md-12" onclick="boton_nuevo_calendari()"><i class="fa fa-plus"></i>&nbsp;Agregar Nuevo</button>
                                      </div>
                                      <div class="col-md-12">
                                          <span style="font-size:14px;font-weight:bold">Fecha:</span>
                                      </div>
                                      <div class="col-md-12">
                                      <input type="date" value="" id="fechaCalendario" class="form-control-agenda">
                                      </div>

                                      <div class="col-md-12">
                                      <br/>
                                          <span style="font-size:14px;font-weight:bold">Hora Inicio:</span>
                                      </div>
                                      <div class="col-md-12">
                                      <input type="time" value="" id="horaCalendario" class="form-control-agenda">
                                      </div>
                                      <div class="col-md-12">
                                      <br/>
                                          <span style="font-size:14px;font-weight:bold">Hora Final:</span>
                                      </div>
                                      <div class="col-md-12">
                                      <input type="time" value="" id="horaCalendariof" class="form-control-agenda">
                                      </div>
                                      <div class="col-md-12">
                                      <br/>

                                      <span style="font-size:14px;font-weight:bold"> Usuario Asignado:</span>
                                      </div>
                                      <div class="col-md-12">
                                        <select class="form-control-alterna" id="usuarioCalendario">
                                        </select>
                                      </div>
                                      <div class="col-md-12">
                                      <br/>

                                      <span style="font-size:14px;font-weight:bold">  Descripcion</span>
                                      </div>
                                      <div class="col-md-12">
                                      <textarea value="" id="new-event" class="form-control-t-agenda" placeholder="Escriba una Actividad" maxlength="200"></textarea>
                                      </div>
                                      <div class="col-md-12">
                                      <div class="col-md-12">
                                      <br/>

                                          <span style="font-size:14px;font-weight:bold">Duracion:</span>
                                      </div>
                                      <div class="col-md-12">
                                        <div class="col-lg-4">
                                         <input type="text" placeholder="Duracion" value="1" id="duracionCalendario" maxlength="2" class="form-control-agenda" onKeypress="return soloNumeros(event)">
                                        </div>
                                        <div class="col-lg-4">
                                            Dias
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                      <hr/>

                                        <ul class="fc-color-picker" id="color-chooser" style="float:right:font-size:20px">
                                        <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
                                      <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
                                      <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                                      <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
                                      <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
                                      <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
                                      <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
                                      <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
                                        </ul>
                                        
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                      <br/>

                                         <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkCumplida">
                                            <label class="custom-control-label h4" for="checkCumplida">Actividad Cumplida</label>
                                          </div>
                                          <hr/>

                                      </div>
                                      <div class="col-md-12" id="botonAgregarAgendaDiv">
                                         <button id="add-new-event" type="button" class="btn btn-info btn-xs col-md-12">Guardar</button>
                                      </div>
                                      <div class="col-md-12 hidden"  id="botonActualizarAgendaDiv">
                                          <div class="col-md-6 offset-md-1">
                                             <button class="btn btn-primary btn-xs col-md-12" onclick="actualizaCumplidaCalendario()" id="botonactCalendario" disabled="disabled">Actualiza</button>
                                          </div>
                                          <div class="col-md-6 offset-md-1">
                                              <button class="btn btn-info btn-xs col-md-12" onclick="eliminarCalendario()" id="botonEliminaCalendario" disabled="disabled">Eliminar Actividad</button>
                                          </div>
                                      </div>

                                    <!-- THE CALENDAR -->
                                  </div>
                                  <!-- /.box-body -->
                                </div>
                          </div>
                        <div class="col-md-9">
                            
                          
                          <!-- /btn-group -->
                              <div class="box box-primary">
                                <div class="box-body no-padding">

                              
                                  <!-- THE CALENDAR -->
                                  <div id="calendar"></div>
                                </div>
                                <!-- /.box-body -->
                              </div>
                        </div>
                </div>
						</div>
						<div class="modal-footer">
				
							<button type="button" class="btn btn-danger" data-dismiss="modal">
								Cerrar
							</button>
						</div>
					</div>
					
				</div>
				
			</div>

<div class="hidden" id="Div_bienesActivos" data-arreglo='{!! json_encode($bienesActivos) !!}'></div>
<div class="hidden" id="Div_soporteUsuarioTicket" data-arreglo='{!! json_encode($soporteUsuarioTicket) !!}'></div>
<div class="hidden" id="Div_categoriaTicket" data-arreglo='{!! json_encode($categoriaTicket) !!}'></div>
<div class="hidden" id="Div_arregloEstadoTickets" data-arreglo='{!! json_encode($arregloEstadoTickets) !!}'></div>
<div class="hidden" id="Div_tipoTicket2" data-arreglo='{!! json_encode($tipoTicket2) !!}'></div>


      <input type="hidden" id="current_user" value="{{ \Auth::user()->id }}" />
<div class="hidden" id="activo_teletrabajo">{{ env('ACTIVO_TELETRABAJO') }}</div>
<div class="hidden" id="hora_entrada_trasvase">{{ env('HORA_ENTRADA_TRASVASE') }}</div>
<div class="hidden" id="hora_salida">{{ env('HORA_SALIDA')}}</div>
<div class="hidden" id="hora_entrada">{{ env('HORA_ENTRADA') }}</div>
<div class="hidden" id="hora_salida_teletrabajo">{{ env('HORA_SALIDA_TELETRABAJO')}}</div>
<div class="hidden" id="hora_entrada_teletrabajo">{{ env('HORA_ENTRADA_TELETRABAJO') }}</div>
<div class="hidden" id="hora_salida_trasvase" >{{ env('HORA_SALIDA_TRASVASE') }}</div>
<div class="hidden" id="horario_diferente" > {{ env('HORA_DIFERENTE') }}</div>
					<input type="hidden" id="pusher_app_key" value="{{ env('PUSHER_APP_KEY') }}" />
					<input type="hidden" id="pusher_cluster" value="{{ env('PUSHER_APP_CLUSTER') }}" />
<div class="container-fluid">

	<div class="row">
		<div class="col-md-12">
			
			<div class="modal fade" id="modal-container-564680" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 2000;">				
      <div class="modal-dialog" role="document" style="width: 80%!important;">
					<div class="modal-content" >
						<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">
                  Marcaciones y Actividades              
                     <a href="#" class="hidden" id="printocultoInformes" target="_blank">printocultoInformes</a>

							</h5> 
						</div>
						<div class="modal-body">
                           <div class="col-lg-10" >
                                          <div class="col-lg-5" >
                                                <div class="form-group">
                                                    <label for="name" class="control-label col-sm-4">Fecha/Inicio:</label>
                                                        <div class="input-group date pickadate-inicio-auditoria col-sm-8" data-date-format="mm-dd-yyyy">
                                                            <input autocomplete="off" class="form-control" type="text" readonly  id="inicio_auditoria"/>
                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar" style="color:#000"></i></span>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="col-lg-5" >
                                                <div class="form-group">
                                                    <label for="name" class="control-label col-sm-4">Fecha/Fin:</label>
                                                        <div class="input-group date pickadate-fin-auditoria col-sm-8" data-date-format="mm-dd-yyyy">
                                                            <input autocomplete="off" class="form-control" type="text" readonly  id="fin_auditoria"/>
                                                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar" style="color:#000"></i></span>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="col-lg-2" >
                                                <button class="btn btn-primary" onclick="agregarAuditoria('CONSULTA',1)">Buscar</button>
                                            </div>
                                            <hr/>
                                </div>
							<div class="row">
	              	<div class="col-md-12">
                    <div class="table table-responsive" id="tablaConsultaAuditoria">
                          <table class="table table-bordered" id="dtmenuAuditoria" style="width:100%!important">
                              <thead>

                              </thead>
                              <tbody id="tbobymenuAuditoria">

                              </tbody>
                          </table>
                      </div>
                    </div>
              </div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">
								Cerrar
							</button>
						</div>
					</div>
					
				</div>
				
			</div>
			
		</div>
	</div>
</div>
<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
        <div class="contenedor223 escritorio" >
            <div class="botonF123 ocultarMarcador">
                <p class="font-tiempo">
                <table class="font-tiempo" width="100%" cellspacing="1">
                <tr >
                <td><button class="btn btn-primary btn-xs btn-outline" onclick="agregarAuditoria('INI')">Inicio</button></td>
                <td><button class="btn btn-primary btn-xs btn-outline" onclick="agregarAuditoria('IPA')">Inicio Parada</button></td>
                <td><button class="btn btn-primary btn-xs btn-outline" onclick="agregarAuditoria('FPA')">Fin Parada</button></td>
                <td><button class="btn btn-primary btn-xs btn-outline" onclick="agregarAuditoria('FIN')">Fin</button></td>
                </tr>
                <tr >
                <td id="inicioAu">00:00</td>
                <td id="inicioAlAu">00:00</td>
                <td id="finAlAu">00:00</td>
                <td id="finAu">00:00</td>
                </tr>
                <tr >
                <td colspan="4">&nbsp;</td>
                </tr>
                <tr >
                <td colspan="4">
                <a href="#" role="button" class="btn btn-primary btn-xs" data-toggle="modal" onclick="cargarConsultaAuditoriaCompleta()"><i class="fa fa-eye"></i></a>
                <a id="modal-10292" href="#modal-container-10292" role="button" class="btn btn-primary btn-xs" data-toggle="modal"><i class="fa fa-calendar" onclick="ActualizacargarCalendar()"></i></a>
                <a class="btn btn-primary btn-xs " href="#" onclick="minMaxTele()"><i class="fa fa-plus btnminus" ></i></a>
                <a href="#modal-container-564680" role="button" class="hidden" data-toggle="modal" id="dataConsultoria"></a>
                </td>
                </tr>
                

                </table>  </p>
                        <p class="font-tiempo hidden" id="segundosSesion">
                                </p>
                                
            </div>
        </div>
           <ol class="breadcrumb float-sm-right" style="background-color: #f5f5f500;">
              <li class="breadcrumb-item" style="color:#6d6e6f;font-size:12px;font-weight:bold">@yield('contentheader_title')</li>
              <li class="breadcrumb-item" id="contenidoPaginaBread" style="color:#77b6f5;font-size:13px;font-weight:bold">@yield('contentheader_description')</li>
            </ol>
           </h4>
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
                <!-- Content Header (Page header) -->
                 
                    <div class="contenedor22 hidden" style="top:94%;">
                   
                      <div class="box box-success box-solid direct-chat direct-chat-success " id="chatAplicacionCerrada" >
                                <div class="box-header">
                                      <h3 class="box-title" style="margin-bottom:15px;font-size:12px">Chat Mesa/Ayuda</h3>

                                    <div class="box-tools pull-right">
                                      <button type="button" class="btn btn-box-tool" id="chatAplicacionCerradaM">
                                      <i class="fa fa-plus"></i>                     
                                       </button>
                                       <button type="button" class="btn btn-box-tool" onclick="buscarMensajes()">
                                          <i class="glyphicon glyphicon-refresh"></i>                     
                                       </button>
                                     
                                    </div><!-- /.box-header -->
                                  </div><!-- /.box-header -->

                        </div><!-- /.box-header -->

                  </div>
                      <div class="contenedor23"  style="top:35%;left:70%">
                   
                      <div class="box box-success box-solid direct-chat direct-chat-success hidden"  id="chatAplicacion" style="">
                    <div class="box-header" style="padding-bottom:0px;padding-top:5px">
                        <h3 class="box-title" style="margin-bottom:15px">Chatea con la DTIC</h3>
                        <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool"id="chatAplicacionM" ><i class="fa fa-minus"></i>
                        <button type="button" class="btn btn-box-tool" id="abreListaTicket">
                                          <i class="fa fa-arrow-circle-left"></i></button>
                         <button type="button" class="btn btn-box-tool" onclick="buscarMensajes()">
                                          <i class="glyphicon glyphicon-refresh"></i>                     
                                       </button>
                        </div><!-- /.box-header -->

                      </button>
                    </div><!-- /.box-header -->
                    <div class="box-body" style="overflow-y: hidden;">
                                                        <div class="col-lg-12"  style="margin-bottom:5px">
                                                                  <div class="col-lg-12" style="top:5px;margin-bottom:10px" name="">
                                                                                <div class="col-lg-1"> 
                                                                                    <button href="#modal-alertas" role="button" data-toggle="modal" class="hidden" id="precargarModalTickectDivi">    
                                                                                        <i class="fa fa-eye"></i>
                                                                                    </button>
                                                                                    <button class="btn btn-default btn-xs" onclick="precargarModalTickect()">    
                                                                                        <i class="fa fa-eye"></i>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="col-lg-11" >     
                                                                                        {!! Form::select('ticketIniciados',[],null,['class'=>'form-control select2','id'=>'ticketIniciados', 'placeholder'=>'SELECCIONE UN TICKET'])!!}
                                                                                </div>
                                                                               
                                                                  </div>
                                                              
                                                                 
                                                        </div>
                        <!-- Conversations are loaded here -->
                     
                        <div class="direct-chat-messages"  >
                            <div id="chatMe" >
                            </div>
                        </div><!--/.direct-chat-messages-->
                     
                    </div><!-- /.box-body -->
                    <div class="box-footer" >
                                                   <div class="col-lg-12 hidden" name="ticketsChatmeDiv">
                                                                  <div class="input-group btn-file22">
                                                                      
                                                                      <div class="col-lg-1" style="">
                                                                          <button class="btn btn-primary btn-file2 btn-xs">
                                                                              <i class="fa fa-upload"></i> <input class="hidden" name="banner_captura" type="file" id="archivoTicketAnexo">
                                                                            </button>
                                                                      </div>
                                                                      <div class="col-lg-9">     
                                                                             <input type="text" name="message" autocomplete="off" id="mensajeChatTexto" placeholder="Escribir Mensaje ..." class="form-control mensaje">
                                                                              <input class="" name="banner_captura" type="text" value="" style="width: 100%;border:0px;font-size:10px;margin-left:25px" readonly="">

                                                                      </div>
                                                                      <div class="col-lg-1">
                                                                          <button type="button" class="btn btn-primary btn-sm" onclick="enviarMensaje()">Enviar</button>
                                                                        </div>
                                                                  </div>
                                                    </div>
                    </div>
            </div>

               </div>
                    @yield('content')

                </div>
              </div>

           
        </section>
        

            
    </div>
</div>
<span href="#modal-alertas" 
class="label label-primary btn-xs hidden" 
title="Ver Solicitud" role="button" 
data-toggle="modal" id="modalClic"></span>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="modal fade" id="modal-alertas" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document" style="min-width:90%" id="ModalTicketInterno">
					<div class="modal-content" style="width: 100%;">
              <div class="modal-header">
                 <a href="#" class="hidden" id="printocultoChat" target="_blank">ImprimirChat</a>
                      <div class="col-lg-9">
                            <div class="col-lg-12 inicioDefaultMovil">
                                 <span class="titulo-modal-header"> USUARIO:
                                  <span id="usuarioTick" class="descripcion-modal-header"></span>
                                </span>
                              </div>
                              <div class="col-lg-12">
                              <span class="titulo-modal-header inicioDefaultMovil"> CATEGORIA: </span>   
                              <span id="categoriaTicket2Texto" class="descripcion-modal-header"></span>
                              &nbsp;(<span style="font-size:12px"id="tipoTicket2Texto"></span>)
                            </div>
                      
                      </div>
                      <div class="col-lg-3">
                             <div class="col-lg-12 titulo-modal-header">
                               <span style="font-weight:bold">Ticket #:</span>
                              <span id="nticket" ></span>
                              </div>
                              <div class="col-lg-12 descripcion-modal-header">
                               <span style="font-weight:bold">Creado:</span>
                              <span id="nticketCreacionInicial" ></span>
                              </div>
                      </div>
              </div>
						<div class="modal-body">
            <a name="Carro de compras" class="wds-user">
            <small name="Carro de compras" class="badge-success2 text-center">
            <label name="Carro de compras"><i name="Carro de compras" class="fa fa-check"></i>
             <span id="textoEstadoWidget">&nbsp;</span>
            </label>
            </small>
            </a>
                                   <div class="col-lg-12 hidden">
                                                                    <div class="col-lg-12">
                                                                        <div class="form-group">
                                                                          <label class="control-sidebar-subheading">
                                                                            Estado:
                                                                          </label>
                                                                          <select name="estadoTicket" class="form-control select2" id="estadoTicket2"></select>
                                                                        </div>
                                                                    </div>
                                    </div>
                           <div class="row" style="padding:10px">
                              <div id="DivAgregaActividad2" class="col-lg-3">
                                 
                                    <div id="DivAgregaActividad">
                                            <div class="col-lg-12">
                                                                   <div class="col-lg-12">
                                                                        <div class="form-group">
                                                                          <label class="control-sidebar-subheading">
                                                                            Actividad:
                                                                          </label>
                                                                          <textarea name="actividadSoporteTicket" class="form-control-t" id="actividadSoporteTicket" style="margin-top: 6px;"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                        <div class="form-group">
                                                                          <label class="control-sidebar-subheading">
                                                                            Archivo:
                                                                          </label>
                                                                            <div class="input-group">
                                                                              <div class="col-lg-2" style="">
                                                                                  <button class="btn btn-primary btn-file2 btn-xs">
                                                                                      <i class="fa fa-upload"></i> <input class="hidden" name="banner_captura" type="file" id="archivoSoporteTicket">
                                                                                    </button>
                                                                              </div>
                                                                                <div class="col-lg-9">     
                                                                                        <input class="" id="banner_captura" name="banner_captura" type="text" value="" style="width: 180%;" readonly="">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-12">
                                                                            <hr/>
                                                                        <div class="col-lg-12">
                                                                              <button type="button" class="btn btn-block btn-outline-info btn-xs" onclick="guardarActividadTicket(1)" name="btnActualizarDatosTicket">
                                                                                <i class="fa fa-plus"></i>&nbsp;Actualizar Datos 
                                                                              </button> 
                                                                        </div>
                                                                      </div>
                                                  
                                            </div>
                                            <div class="col-lg-12">
                                            <hr/>
                                            </div>
                                         </div>
                                    <div class="col-lg-12 ">
                                                                      <div class="form-group">
                                                                        <label class="control-sidebar-subheading">
                                                                        
                                                                        {!! Form::hidden('idTickectVigente', 0, ['class' => 'form-control','id'=>'idTickectVigente']) !!}
                                                                        {!! Form::hidden('idTickectVigenteSeguimiento', 0, ['class' => 'form-control','id'=>'idTickectVigenteSeguimiento']) !!}
                                                                        
                                                                          Tipo:
                                                                        </label>
                                                                        {!! Form::select('tipoTicket2',[],null,['class'=>'form-control select2','id'=>'tipoTicket2', 'placeholder'=>'SELECCIONE UN TIPO DE TICKET'])!!}

                                                                      </div>
                                    </div>
                                    <div class="col-lg-12 ">
                                                                    <div class="form-group">
                                                                      <label class="control-sidebar-subheading">
                                                                        Categoria:
                                                                      </label>
                                                                      <select name="categoriaTicket" class="form-control select2" id="categoriaTicket2"></select>
                                                                    </div>
                                    </div>
                                    <div class="col-lg-12">
                                                                      <div class="form-group">
                                                                        <label class="control-sidebar-subheading">
                                                                          Bienes:
                                                                        </label>
                                                                        <select name="bienesTicket" class="form-control select2" id="bienesTicket2"></select>
                                                                      </div>
                                    </div>
                                    <div class="col-lg-12" id="DivCargaCheckFinalizarTicket">
                                          <div class="col-lg-12">
                                              <input type="checkbox" id="CheckFinalizarTicket" style="margin-top:25px">
                                              Finalizar Ticket
                                          </div>
                                          <div class="col-lg-12">
                                              <input type="checkbox" id="CheckTicketDirector" style="margin-top:25px">
                                              <span id="textReasignarEstudiante"> Reasignaci&oacute;n al Director </span>
                                          </div> 
                                          <div class="col-lg-12" id="DivCargaCheckTicketOficial">

                                              <input type="checkbox" id="CheckTicketOficial" style="margin-top:25px">
                                              Notificar Oficial de Seguridad
                                          </div> 
                                    </div> 
                                    <div class="col-lg-12">
                                          <hr/>
                                      <div class="col-lg-12">
                                            <button type="button" class="btn btn-block btn-outline-info btn-xs" onclick="guardarActividadTicket()" name="btnActualizarDatosTicket">
                                              <i class="fa fa-plus"></i>&nbsp;Actualizar Datos 
                                            </button> 
                                      </div>
                                      <hr/>
                                    </div>
                                </div>
                                
                               <div class="col-lg-12" id="DivAgregaActividad3">
                                   
                                          <div class="col-lg-12">
                                            <button id="btnMostrarMovil" class="hidden">Mostrar Movil</button>
                                            
                                            <div class="table table-responsive" id="tablaConsultaActividadesTicket">
                                                        <table class="table  table-striped" id="dtmenuActividadesTicket" style="width:100%!important">
                                                            <thead>

                                                            </thead>
                                                            <tfoot>
                                                            
                                                            </tfoot>
                                                            <tbody id="tbobymenuActividadesTicket">

                                                            </tbody>
                                                        </table>
                                            </div>
                                          </div>
                                   
                       
                               </div>
                           
                      </div>
                    
            </div>
            <div class="modal-footer">
                        <div class="col-md-6 satifaccionCargada">
                        
                        </div>

                        <div class="col-md-6">
                            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="DescargarUrlPdf('reporteActividades',$('#nticket').text())" >
                            <img src="/images/icons/pdf.png" width="15px" heigh="10px">&nbsp;Reporte  
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="DescargarChat()" >
                            <i class="fa fa-comments" style="font-size:16px;color:#00a65a"></i>&nbsp;Chat 
                            </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancelarModalTicket" >
                              Cerrar 
                            </button>
                          </div>
						          </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Construct the box with style you want. Here we are using box-danger -->
<!-- Then add the class direct-chat and choose the direct-chat-* contexual class -->
<!-- The contextual class should match the box, so we are using direct-chat-danger -->

{!! Form::open(['route' => 'auth.logout', 'style' => 'display:none;', 'id' => 'logout']) !!}
<button type="submit">Logout</button>
{!! Form::close() !!}
<aside class="control-sidebar control-sidebar-dark hidden" id="mayuda" style="min-height: 100%;">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li ><a href="#control-sidebar-home-tab" data-toggle="tab" onclick="inicioCreacionTicke()">Consulta Ticket</a></li>
      <li class="active">
        <a href="#control-sidebar-settings-tab" class="vinculoSolicitud" data-toggle="tab">Crear Ticket</a>
      </li>
      <li class="hidden inicioMovil"><a href="#control-sidebar-home-tab" data-toggle="tab" onclick="$('#cerrarSesionGeneral').click()">Cerrar Sesi&oacute;n</a></li>

    </ul>		
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Mis solicitudes pendientes&nbsp;<button class="btn btn-success btn-xs" style="border-radius:100" onclick="BuscarTicket()"><i class="fa fa-sync"></button></i></h3>
        <ul class="control-sidebar-menu" style="" id="alertasTicket">

        </ul>

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab"></div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane active" id="control-sidebar-settings-tab">
   
          <div id="creacionTicket">
              <div class="form-group">
              @auth
                <span name="soporte" class="hidden"> {!! Auth::user()->evaluarole(['soporte'])!!}</span>
                <span name="oficialSeguridad" class="hidden"> {!! Auth::user()->evaluarole(['oficial de seguridad'])!!}</span>
                <span name="estandar" class="hidden"> {!! Auth::user()->evaluarole(['estandar'])!!}</span>
                <span name="director" class="hidden"> {!! Auth::user()->evaluarole(['DIRECTOR DTIC'])!!}</span>
                <span name="directorJ" class="hidden"> {!! Auth::user()->evaluarole(['DIRECTOR'])!!}</span>
                <span name="asignadorTickets" class="hidden"> {!! Auth::user()->evaluarole(['gestion de tickets'])!!}</span>
                @endauth
                @guest
                <span name="soporte" class="hidden">0</span>
                <span name="oficialSeguridad" class="hidden">0</span>
                <span name="estandar" class="hidden">0</span>
                <span name="director" class="hidden">0</span>
                <span name="directorJ" class="hidden">0</span>
                <span name="asignadorTickets" class="hidden">0</span>
         
              @endguest
                <label class="control-sidebar-subheading">
                  Tipo:
                <!-- <input type="checkbox" class="pull-right" checked>-->
                </label>
                {!! Form::select('tipoTicketUsuario',['SOPORTE'=>'SOPORTE GENERAL','SOLICITUD'=>'SOLICITUD ADICIONAL'],null,['class'=>'form-control select2','id'=>'tipoTicketUsuario', 'placeholder'=>'SELECCIONE UN TIPO DE TICKET'])!!}

                
                <div class="form-group">
                  <label class="control-sidebar-subheading">
                    Categoria:
                  <!-- <input type="checkbox" class="pull-right" checked>-->
                  </label>
                  <select name="categoriaTicketUsuario" class="form-control select2" id="categoriaTicketUsuario">
                  <option value="0">SELECCIONE UNA CATEGORIA</option>
                  </select>
                </div>

              <div class="hidden">
              <hr/>
                <label class="control-sidebar-subheading">
                  Tipo:
                <!-- <input type="checkbox" class="pull-right" checked>-->
                </label>
                {!! Form::select('tipoTicket2',[],0,['class'=>'form-control select2','id'=>'tipoTicket1', 'placeholder'=>'SELECCIONE UN TIPO DE TICKET'])!!}
                </div>
          </div>
          <div class="form-group hidden">
            <label class="control-sidebar-subheading">
              Categoria:
             <!-- <input type="checkbox" class="pull-right" checked>-->
            </label>
            <select name="categoriaTicket" class="form-control select2" id="categoriaTicket1">
            <option value="0">SELECCIONE UNA CATEGORIA</option>
            </select>
          </div>
          <div class="form-group">
            <label class="control-sidebar-subheading">
              Descripci&oacute;n:
             <!-- <input type="checkbox" class="pull-right" checked>-->
            </label>
            <textarea name="descripcionTicket" class="form-control-t" placeholder="DESCRIPCI&Oacute;N DEL TICKET" id="descripcionTicket1"style="color:#000;min-height:100px"></textarea>
          </div>
        
          <div class="form-group">
            <h5 style="color:#ffffff" class="hidden"><a href="/storage/Solicitud_de_servicios_adicionales.xls" target="_blank"><i class="fa fa-download"></i>&nbsp;Descargar</a> Solicitud de Recursos Adicionales </h5>
            <h5 style="color:#ffffff" id="crearSolicitud" onclick="crearSolicitudadicional()"><a href="#"><i class="fa fa-file"></i>&nbsp;Crear Solicitud de Recursos Adicionales </a> </h5>
          
            <label class="control-sidebar-subheading">
              Documento/Solicitud
             <!-- <input type="checkbox" class="pull-right" checked>-->
            </label>
            <span class="control-fileupload">
                <label for="file" name="labelFile">Subir Archivo </label>
                <input type="file" name="documentoTicket[]">
                <button style="float:right;margin:0px;" class="btn btn-info">SUBIR</button>
            </span>
          </div>
          <div class="col-lg-12">
            <hr/>
            <button class="btn btn-primary" name="guardaTicket"> Generar Ticket</button>
            <button class="btn btn-danger" name="limpiaTicket"> Borrar Ticket</button>
          </div>
      </div>
      <div id="solicitudAdicionaldiv">
            <h4 class="col-lg-12" style="color:#ffffff;text-align:center" >
                <a  href="#" onclick="inicioCreacionTicke()"class="fa fa-arrow-left" style="font-size:14px;color:#ffffff"></a>
                  Solicitud de Recursos Adicionales
            </h4>
       
            <div class="col-lg-12" >
                <div style="float:right"><label name="solicitudcampos" >Fecha:<span name="fechadia"></span></label></div>
            </div>
            <div class="form-group">
            <div class="col-lg-3">
              <label class="control-sidebar-subheading">
                Categoria:
              <!-- <input type="checkbox" class="pull-right" checked>-->
              </label>
              </div>
            <div class="col-lg-9">
              <textarea id="categoriaTexto" class="form-control-t" readonly></textarea>
              </div>
            </div>
  
            <div class="form-group">
            <div class="col-lg-3" id="accionesConfiuradas">
              <label class="control-sidebar-subheading">
                Acciones:
              <!-- <input type="checkbox" class="pull-right" checked>-->
              </label>
              </div>
              <div class="col-lg-9">
                <div  name="DivcargarComboCategoria" id="DivcargarComboCategoriaSIMPLE">
                  <select class="form-control select2" name="comboSolicitudSIMPLE"  id="cargarComboCategoriaSIMPLE">
                  </select>
                </div>
                <div  name="DivcargarComboCategoria" id="DivcargarComboCategoriaMULTIPLE">
                  <select class="form-control select2" name="comboSolicitudMULTIPLE" id="cargarComboCategoriaMULTIPLE" multiple="multiple" >
                  </select>
                </div>
                <div name="DivcargarComboCategoriaSE" id="DivcargarComboCategoriaSESIMPLE">
                  <select class="form-control select2" name="comboSolicitudSESIMPLE"  id="cargarComboCategoriaSESIMPLE">
                  </select>
                </div>
                <div  name="DivcargarComboCategoriaSE" id="DivcargarComboCategoriaSEMULTIPLE">
                  <select class="form-control select2" name="comboSolicitudSEMULTIPLE"  id="cargarComboCategoriaSEMULTIPLE" multiple="multiple" >
                  </select>
                </div>
                <div  name="DivcargarComboCategoria" id="formularioSolicitud">
               
                </div>
              </div>
              
                <div class="form-group" name="DivcargarComboCategoria" id="otros">
                    <div class="col-lg-12">
                    <br/>
                      <div class="col-lg-3">
                          <label class="control-sidebar-subheading">
                            Otros:
                          <!-- <input type="checkbox" class="pull-right" checked>-->
                          </label>
                      </div>
                      <div class="col-lg-9">
                      <input type="text" id="otrosSolicitud" class="form-control" placeholder="OTROS"> 
                      </div>
                    </div>
                </div>
                <div class="col-lg-12">
                  <hr/>
                </div>
          <div class="form-group">
             <div class="col-lg-6">
              <label class="control-sidebar-subheading">
                Tipo/Dispositivo:
              <!-- <input type="checkbox" class="pull-right" checked>-->
              </label>
                <select class="form-control select2" name="bienesTicketSolicitud"  id="bienesActivosUsuarios">
                </select>
                <label class="control-sidebar-subheading">
                Anydesk:&nbsp;<a href="#modal-container-494871" role="button" data-toggle="modal"><i class="fa fa-arrow-right"></i></a>
              <!-- <input type="checkbox" class="pull-right" checked>-->
              </label>
                <input type="text" id="anydesk" class="form-control" placeholder="ANYDESK">
            </div>
           <div class="col-lg-6">
            <textarea name="descripcionBien" class="form-control-t" style="min-height:100px" placeholder="Descripcion/Bien" readonly></textarea>
            </div>
          </div>
           <div class="container-fluid">
              <div class="row">
                <div class="col-md-6">
                  <div class="col-md-6">
                    <label class="control-sidebar-subheading">
                      Fecha/Inicio:
                    </label>
                  </div>
                 <div class="col-md-7">
                 <br>

                     <input type="date" class="form-control dateForm dateFormfi" id="finicioSolicitud" placeholder="Fecha/Inicio"> 
                  </div>
                  <div class="col-md-5">
                 <br>

                      <input type="time" class="form-control dateForm dateFormti" id="hinicioSolicitud" placeholder="hora/Inicio"> 
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="col-md-6">
                    <label class="control-sidebar-subheading">
                      Fecha/Fin:
                    </label>
                  </div>
                 <div class="col-md-7">
                 <br>
                     <input type="date" class="form-control dateForm dateFormff" id="ffinSolicitud" placeholder="Fecha/Fin"> 
                  </div>
                  <div class="col-md-5">
                 <br>

                      <input type="time" class="form-control dateForm dateFormtf" id="hfinSolicitud" placeholder="hora/Fin"> 
                  </div>
                  <div style="float:right">
                       <input type="checkbox" class="pull-right" id="indefinidoSolicitud" checked><span style="font-size:11px;color:#ffffff;font-weigth:bold">Indefinido&nbsp;</span>
                    </div> 
                </div>
              </div>
            </div>
     
            <div class="form-group">
              <label class="control-sidebar-subheading">
                Descripci&oacute;n:
              <!-- <input type="checkbox" class="pull-right" checked>-->
              </label>
              <textarea name="descripcionTicket" class="form-control-t" placeholder="JUSTIFICACI&Oacute;N DEL TICKET" id="descripcionTicketSolicitud1"style="color:#000;min-height:50px"></textarea>
            </div>
        <div class="col-lg-12" id="botonesAccion">
            <button class="btn btn-info" name="guardaTicket"> Generar Ticket</button>
            <button class="btn btn-default" onclick="inicioCreacionTicke()"> Cancelar Solicitud</button>
          </div>
      </div>
   
    
          <!-- /.form-group -->
        
      </div>
      <div id="satisfaccionTicket">
      
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
    <input type="hidden"id="direccionDocumentos" name="direccionDocumentos" value="{{ url('storage/') }}">
  <div id="dh_data_satisfaccion" data-data='@json($conVoto)'></div>
  <div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			 <a href="#modal-container-146500" role="button" class="hidden" data-toggle="modal" data-backdrop="static" data-keyboard="false"  id="modalSatisfaccion">Launch demo modal</a>
			
			<div class="modal fade" id="modal-container-146500" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4  class="modal-title" id="myModalLabel" style="text-align:center">
								Tickets Pendientes de Calificar
							</h4> 
						
						</div>
						<div class="modal-body" style="
    background: #ece3e3;
">
							<div class="panel panel-body satisfaccionTicket" style="
    background: #337ab7;
"></div> 
						</div>
            <button type="button" class="btn btn-secondary hidden" data-dismiss="modal" id="CerrarModalSatisfaccion">
								Cerrar
							</button>
					
					</div>
					
				</div>
				
			</div>
			
		</div>
	</div>
</div>

@include('partials.javascripts')
  <script src="{{ url('js/modules/gestion/agenda.js') }}"></script>
  	<script src="{{ url('adminlte/plugins/datepicker/') }}/bootstrap-datepicker.js"></script>
    <script src="{{ url('adminlte/plugins/fullcalendar/') }}/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
    <script src="{{ url('adminlte/plugins/fullcalendar/') }}/bower_components/fastclick/lib/fastclick.js"></script>
    <script src="{{ url('adminlte/plugins/fullcalendar/') }}/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="{{ url('adminlte/plugins/fullcalendar/') }}/bower_components/fullcalendar/dist/locale/es.js"></script>
    
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
<script src="{{ url('js/inicio.js?v=3') }}"></script>
<script src="{{ url('serviceworker.js') }}"></script>
<script>
    $('.dateFormfi').attr('type','text');
    $('.dateFormti').attr('type','text');
    $('.dateFormff').attr('type','text');
    $('.dateFormtf').attr('type','text');

    $(".dateFormfi").on("focus",function(){
      $('.dateFormfi').attr('type','date');
    });
    $(".dateFormff").on("focus",function(){
      $('.dateFormff').attr('type','date');
    });
    $(".dateFormti").on("focus",function(){
      $('.dateFormti').attr('type','time');
    });
    $(".dateFormtf").on("focus",function(){
      $('.dateFormtf').attr('type','time');
    });

      
    $(".dateFormfi").on("blur",function(){
      $('.dateFormfi').attr('type','text');
    });
    $(".dateFormff").on("blur",function(){
      $('.dateFormff').attr('type','text');
    });
    $(".dateFormti").on("blur",function(){
      $('.dateFormti').attr('type','text');
    });
    $(".dateFormtf").on("blur",function(){
      $('.dateFormtf').attr('type','text');
    });
</script>
<script>
        var base_url = '{{ url("/") }}';
    </script>

<!-- Tile for Win8 -->
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/images/icons/icon-512x512.png">



<!-- Construct the box with style you want. Here we are using box-danger -->
<!-- Then add the class direct-chat and choose the direct-chat-* contexual class -->
<!-- The contextual class should match the box, so we are using direct-chat-danger -->

<!--/.direct-chat -->

			<div class="modal fade" id="modal-container-494871" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document" style="min-width:60%">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="myModalLabel">
							 Anydesk
							</h5> 
						</div>
						<div class="modal-body">
							<img src="/manualAnydesk.jpg" width="auto">
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">
								Cerrar
							</button>
						</div>
					</div>
					
				</div>
		
</div> 


</body>
</html>