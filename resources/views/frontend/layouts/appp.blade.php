
<!DOCTYPE html>
<html lang="es">
<script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	$("#pdfDownloader").click(function() {
			var doc = new jsPDF('p', 'pt', 'a4', true);

					doc.fromHTML($('#renderMe').get(0), 15, 15, {
					'width': 500
					},
					function(){
						doc.save('thisMotion.pdf');
					});
				});
})

    </script>
<head>
@include('frontend.partials.head')
</head>

<body>

<div id="renderMe">

	<hr/>
	<button type="button" id="pdfDownloader">PDF</button>

</div>
<div>
	<div class="container" >
	<div class="row">
	  <div class="col-md-10 col-md-offset-1">
		<div class="panel panel-default">
		  <div class="panel-heading">Hola, {!!$usuario=session('usuario')!!} </div>
			
	
				
	<div class="modal-content">

		<div class="modal-header">
			<center><h3 class="modal-title">
						FICHA DE INSCRIPCIÓN</h3>
						<h3 class="modal-title">
						COORDINACIÓN DE VINCULACIÓN</h3>
						<h3 class="modal-title">
						PRÁCTICAS PRE PROFESIONALES</h3>
						 
			</center>
		</div>

				<div class="modal-body" >

					<div class="agileits-w3layouts-info">
					<div style="background: url('/images/fondo.jpeg') no-repeat center;background-size: 500px 600px;">
			<form method="POST" action="{{ route ('storage.create')}}" accept-charset="UTF-8" enctype="multipart/form-data">
					
					<fieldset>

					<!-- Form Name -->
					<div class="form-group">
					  <label class="col-md-2 control-label" for="Escuela">Escuela</label>  
						<div class="col-md-4">

						{!! Form::select('carrera', ['Derecho'=>'Derecho','Sociologia'=>'Sociologia'], session('data')["carrera"],['class' => 'form-control select2','placeholder'=>'Carrera',"style"=>"width:100%","id"=>"carrera","name"=>"carrera"]) !!}
						</div>
						<label class="col-md-2 control-label" for="modalidad">Modalidad</label>  
						<div class="col-md-4">

						{!! Form::select('modalidad', ['SEMESTRAL'=>'SEMESTRAL','ANUAL'=>'ANUAL','MODULAR'=>'MODULAR'], session('data')["modalidad"],['class' => 'form-control select2','placeholder'=>'Modalidad',"style"=>"width:100%","id"=>"modalidad","name"=>"modalidad"]) !!}
						</div>
						<br/>
						<hr/>
					</div>
					<div class="form-group">
						<div class="col-md-4">
					  <label class="col-md-2 control-label" for="Escuela">Nivel</label>  
						{!! Form::select('nivel', ['7'=>'7','8'=>'8','9'=>'9','EGRESADO'=>'EGRESADO'], session('data')["semestre"],['class' => 'form-control select2','placeholder'=>'nivel',"style"=>"width:100%","id"=>"nivel","name"=>"nivel"]) !!}
						</div>
						<div class="col-md-4">
						<label class="col-md-2 control-label" for="modalidad">Paralelo</label>  

						{!! Form::text('paralelo', session('data')["paralelo"],['class' => 'form-control','placeholder'=>'paralelo',"style"=>"width:100%","id"=>"paralelo","name"=>"paralelo"]) !!}
						</div>
						<div class="col-md-4">
						<label class="col-md-2 control-label" for="modalidad">Horario</label>  

						{!! Form::select('horario', ['MATUTINO'=>'MATUTINO','VESPERTINO'=>'VESPERTINO','NOCTURNO'=>'NOCTURNO'], session('data')["horario"],['class' => 'form-control select2','placeholder'=>'horario',"style"=>"width:100%","id"=>"horario","name"=>"horario"]) !!}
						</div>
						<br/>
						<hr/>
					</div>
					<div class="form-group">
						  <div class="col-md-4">
							<label class="col-md-4 control-label" for="Identificacion">Identificacion</label>  

						{!! Form::text('identificacion', session('data')["identificacion"],['class' => 'form-control','placeholder'=>'identificacion',"maxlength"=>"10","onKeypress"=>"return soloNumeros(event)","style"=>"width:100%","id"=>"identificacion","name"=>"identificacion"]) !!}
						  </div>
						<div class="col-md-4">
						<label class="col-md-2 control-label" for="modalidad">Nombres</label>  

						{!! Form::text('nombres', session('data')["nombres"],['class' => 'form-control','placeholder'=>'nombres',"style"=>"width:100%","id"=>"nombres","name"=>"nombres"]) !!}
						</div>
						<div class="col-md-4">
						<label class="col-md-2 control-label" for="modalidad">Apellidos</label>  

						{!! Form::text('apellidos', session('data')["apellidos"],['class' => 'form-control ','placeholder'=>'apellidos',"style"=>"width:100%","id"=>"apellidos","name"=>"apellidos"]) !!}
						</div>
						<br/>
					</div>
					<div class="form-group">
					 <div class="col-md-4">
						  <label class="col-md-12 control-label" for="convencional">Provincia</label>  
						{!! Form::text('provincia', session('data')["provincia_id"],['class' => 'form-control','placeholder'=>'Provincia',"style"=>"width:100%","id"=>"provincia","name"=>"provincia"]) !!}
					  </div>
					  <div class="col-md-4">
						  <label class="col-md-4 control-label" for="convencional">Ciudad</label>  
						<!--<input type="text" id="dciudad" value='{{session("data")["ciudad_id"]}}'>-->

						{!! Form::text('ciu', session('data')["ciudad_id"],['class' => 'form-control','placeholder'=>'Ciudad',"style"=>"width:100%","id"=>"ciu","name"=>"ciu"]) !!}
					  </div>
					  <div class="col-md-4">
						<label class="col-md-12 control-label" for="celular">Direccion</label>  
						{!! Form::textarea('direccion', session('data')["direccion"],['class' => 'form-control-t','placeholder'=>'direccion',"style"=>"max-height:100px!important;max-width:210px!important","id"=>"direccion","name"=>"direccion"]) !!}

					  </div>

					</div>
						
				
					<div class="form-group">
					 <div class="col-md-4">
						  <label class="col-md-12 control-label" for="convencional">Estado Civil</label>  

						{!! Form::select('estado_civil', ['SOLTERO'=>'SOLTERO','CASADO'=>'CASADO','VIUDO'=>'VIUDO','DIVORCIADO'=>'DIVORCIADO','UNION LIBRE'=>'UNION LIBRE'],  session('data')["estado_civil"],['class' => 'form-control select2','placeholder'=>'Estado civil',"style"=>"width:100%","id"=>"estado_civil","name"=>"estado_civil"]) !!}
					  </div>
					  <div class="col-md-4">
						  <label class="col-md-4 control-label" for="convencional">Edad</label>  
						{!! Form::text('edad', session('data')["edad"],['class' => 'form-control','placeholder'=>'edad',"onKeypress"=>"return soloNumeros(event)","style"=>"width:100%","id"=>"edad","name"=>"edad"]) !!}

					   </div>
					  <div class="col-md-4">
						<label class="col-md-12 control-label" for="celular">Fecha de Nacimiento</label>  
						{!! Form::date('fecha_nacimiento', session('data')["fecha_nacimiento"],['class' => 'form-control','placeholder'=>'fecha_nacimiento',"style"=>"width:100%","id"=>"fecha_nacimiento","name"=>"fecha_nacimiento"]) !!}

					  </div>

					</div>
					<div class="form-group">
					 <div class="col-md-4">
						  <label class="col-md-12 control-label" for="convencional">Correo</label>  
						{!! Form::email('correo', session('data')["correo"],['class' => 'form-control','placeholder'=>'correo',"style"=>"width:100%","id"=>"correo","onblur"=>"validarEmail('correo')","name"=>"correo"]) !!}

					  </div>
						<div class="col-md-4">
						<label class="col-md-12 control-label" for="celular">Correo Institucional</label>  
						{!! Form::text('correo_institucional', session('data')["correo_institucional"],['class' => 'form-control','placeholder'=>'Correo Institucional',"style"=>"width:100%","onblur"=>"validarEmail('correo_institucional')","id"=>"correo_institucional","name"=>"correo_institucional"]) !!}

					  </div>
					  <div class="col-md-4">
						  <label class="col-md-4 control-label" for="convencional">Convencional</label>  
						{!! Form::text('convencional', session('data')["convencional"],['class' => 'form-control', "maxlength"=>"10",'placeholder'=>'convencional',"style"=>"width:100%","onKeypress"=>"return soloNumeros(event)","id"=>"convencional","name"=>"convencional"]) !!}

					  </div>
					  </div>
					  <div class="col-md-4">
						<label class="col-md-12 control-label" for="celular">Teléfono móvil</label>  
						{!! Form::text('celular', session('data')["celular"],['class' => 'form-control', "maxlength"=>"10",'placeholder'=>'telefono laboral',"style"=>"width:100%","onKeypress"=>"return soloNumeros(event)","id"=>"celular","name"=>"celular"]) !!}

					  </div>


					</div>
					<div class="form-group">
					 <div class="col-md-4">
						  <label class="col-md-12 control-label" for="convencional">labora</label>  

						{!! Form::select('labora', ['SI'=>'SI','NO'=>'NO'], session('data')["labora"],['class' => 'form-control select2',"style"=>"width:100%","id"=>"labora","name"=>"labora"]) !!}
					  </div>
					  <div class="col-md-4">
						  <label class="col-md-12 control-label" for="convencional">Profesión  y Ocupación</label>  
						{!! Form::text('ocupacion', session('data')["ocupacion"],['class' => 'form-control','placeholder'=>'Ocupación laboral',"style"=>"width:100%","id"=>"ocupacion","name"=>"ocupacion"]) !!}

					  </div>
					
					  <div class="col-md-4">
						  <label class="col-md-12 control-label" for="convencional">Horario de Trabajo</label>  
						{!! Form::text('horario_t', session('data')["horario_t"],['class' => 'form-control','placeholder'=>'horario laboral',"style"=>"width:100%","id"=>"horario_t","name"=>"horario_t"]) !!}

					  </div>
					   <div class="col-md-8">
						  <label class="col-md-12 control-label" for="convencional">Direccion Laboral</label>  
						{!! Form::textarea('direccion_t', session('data')["direccion_t"],['class' => 'form-control-t','placeholder'=>'direccion laboral',"style"=>"max-height:100px!important;max-width:100%!important","id"=>"direccion_t","name"=>"direccion_t"]) !!}

					  </div>
					  <div class="col-md-4">
						<label class="col-md-12 control-label" for="celular">Teléfono Laboral</label>  
						{!! Form::text('telefono_t', session('data')["telefono_t"],['class' => 'form-control', "maxlength"=>"10",'placeholder'=>'telefono laboral',"style"=>"width:100%","onKeypress"=>"return soloNumeros(event)","id"=>"telefono_t","name"=>"telefono_t"]) !!}

				
						</div>
						
					</div>
					<div class="form-group">
					 <div class="col-md-4">
						  <label class="col-md-12 control-label" for="convencional">discapacidad</label>  

						{!! Form::select('discapacidad', ['SI'=>'SI','NO'=>'NO'], session('data')["discapacidad"],['class' => 'form-control select2',"style"=>"width:100%","id"=>"discapacidad","name"=>"discapacidad"]) !!}
					  </div>
					  <div class="col-md-4">
						  <label class="col-md-12 control-label" for="convencional">Carnet de CONADIS</label>  
						{!! Form::text('carnet', session('data')["carnet"],['class' => 'form-control', "maxlength"=>"10",'placeholder'=>'carnet CONADIS',"style"=>"width:100%","id"=>"carnet","name"=>"carnet"]) !!}

					  </div>
					  
					   <div class="col-md-4">
						  <label class="col-md-12 control-label" for="convencional">Area de preferencia</label>  

						{!! Form::select('area', ['CIVIL'=>'CIVIL','PENAL'=>'PENAL','LABORAL'=>'LABORAL','FAMILIA'=>'FAMILIA','VIOLENCIA INTRAFAMILIAR'=>'VIOLENCIA INTRAFAMILIAR','INQUILINATO'=>'INQUILINATO','FISCALIA'=>'FISCALIA','DEFENSORIA PUBLICA'=>'DEFENSORIA PUBLICA','CONSTITUCIONAL'=>'CONSTITUCIONAL'], session('data')["area"],['class' => 'form-control select2',"style"=>"width:100%","id"=>"area","name"=>"area"]) !!}
					  </div>
					  <div class="col-md-12">
	<hr/></div>
					</div>

					<!-- Button (Double) -->
				
					</fieldset>
					
					</div>
											
						</div>
				
				  
		  <hr/>
					<div class="panel-body">
						
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" value='{{session("id")}}' name="idusuario">

						<div class="form-group">
						  <label class="col-md-4 control-label">Cedula</label>
						  <div class="col-md-6">
							<input type="file" class="form-control" name="cedula" accept="application/pdf" >
						  </div>
						</div>
						<div class="form-group">
						  <label class="col-md-4 control-label">Papeleta</label>
						  <div class="col-md-6">
							<input type="file" class="form-control" name="papeleta" accept="application/pdf">
						  </div>
						</div>
						<div class="form-group">
						  <label class="col-md-4 control-label">Foto</label>
						  <div class="col-md-6">
							<input type="file" class="form-control" name="foto"accept="application/pdf">
						  </div>
						</div>
						<div class="form-group">
						  <label class="col-md-4 control-label">Curriculum</label>
						  <div class="col-md-6">
							<input type="file" class="form-control" name="curriculum" accept="application/pdf">
						  </div>
						</div>
						<div class="form-group">
						  <label class="col-md-4 control-label">Certificado de Matricula</label>
						  <div class="col-md-6">
							<input type="file" class="form-control" name="certificado_matricula" accept="application/pdf" >
						  </div>
						</div>
						<div class="form-group">
						  <label class="col-md-4 control-label">Certificado de No Arrastre</label>
						  <div class="col-md-6">
							<input type="file" class="form-control" name="certificado_arrastre" accept="application/pdf" >
						  </div>
						</div>
						<div class="form-group">
						  <label class="col-md-4 control-label">Solicitud Sellada</label>
						  <div class="col-md-6">
							<input type="file" class="form-control" name="solicitud_sellada" accept="application/pdf" >
						  </div>
						</div>
						<div class="col-md-12">
						
						<hr/>
						</div>
						<div class="form-group">
						
						  <div class="col-md-6 col-md-offset-4">
							<button type="submit" class="btn btn-primary">Enviar</button>
						  </div>
						</div>
					  </form>
					</div>
					
				</div>
			
		</div>	
		  </div>
		</div>
	  </div>
	</div>
</div>


	<!-- //map -->
	<!-- footer -->
	<!-- //footer -->
	<!-- //footer -->
@include('frontend.partials.javascripts')

</body>

</html>
