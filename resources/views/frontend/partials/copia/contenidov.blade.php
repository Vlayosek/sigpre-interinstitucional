<div class="panel-heading">Hola, {!!$usuario=$datos['usuario']!!} </div>
					
					
						
			<div class="modal-content">

	
						<div class="modal-body" >

											<div class="agileits-w3layouts-info">
														<div style="background: url('/images/fondo.jpeg') no-repeat center;background-size: 500px 600px;">
																<form method="POST" action="{{ route ('storage.create')}}" accept-charset="UTF-8" enctype="multipart/form-data">
																		
																		<fieldset>

																						<div class="form-group">
																							<label class="col-md-2 control-label" for="Escuela">Escuela</label>  
																							<div class="col-md-4">

																							{!! Form::select('carrera', ['Derecho'=>'Derecho','Sociologia'=>'Sociologia'], $datos['data']["carrera"],['class' => 'form-control select2',"style"=>"width:100%","id"=>"carrera","name"=>"carrera"]) !!}
																							</div>
																							<label class="col-md-2 control-label" for="modalidad">Modalidad</label>  
																							<div class="col-md-4">

																							{!! Form::select('modalidad', ['SEMESTRAL'=>'SEMESTRAL','ANUAL'=>'ANUAL','MODULAR'=>'MODULAR'], $datos['data']["modalidad"],['class' => 'form-control select2',"style"=>"width:100%","id"=>"modalidad","name"=>"modalidad"]) !!}
																							</div>
																							<br/>
																							<hr/>
																						</div>
																						<div class="form-group">
																							<div class="col-md-4">
																							<label class="col-md-2 control-label" for="Escuela">Nivel</label>  
																							{!! Form::select('nivel', ['7'=>'7','8'=>'8','9'=>'9','EGRESADO'=>'EGRESADO'], $datos['data']["semestre"],['class' => 'form-control select2',"style"=>"width:100%","id"=>"nivel","name"=>"nivel"]) !!}
																							</div>
																							<div class="col-md-4">
																							<label class="col-md-2 control-label" for="modalidad">Paralelo</label>  

																							{!! Form::text('paralelo', $datos['data']["paralelo"],['class' => 'form-control','placeholder'=>'paralelo',"style"=>"width:100%","id"=>"paralelo","name"=>"paralelo"]) !!}
																							</div>
																							<div class="col-md-4">
																							<label class="col-md-2 control-label" for="modalidad">Horario</label>  

																							{!! Form::select('horario', ['MATUTINO'=>'MATUTINO','VESPERTINO'=>'VESPERTINO','NOCTURNO'=>'NOCTURNO'], $datos['data']["horario"],['class' => 'form-control select2','placeholder'=>'horario',"style"=>"width:100%","id"=>"horario","name"=>"horario"]) !!}
																							</div>
																							<br/>
																							<hr/>
																						</div>
																						<div class="form-group">
																								<div class="col-md-4">
																								<label class="col-md-4 control-label" for="Identificacion">Identificacion</label>  

																							{!! Form::text('identificacion', $datos['data']["identificacion"],['class' => 'form-control','placeholder'=>'identificacion',"maxlength"=>"10","onKeypress"=>"return soloNumeros(event)","style"=>"width:100%","id"=>"identificacion","name"=>"identificacion","readonly"]) !!}
																								</div>
																							<div class="col-md-4">
																							<label class="col-md-2 control-label" for="modalidad">Nombres</label>  

																							{!! Form::text('nombres', $datos['data']["nombres"],['class' => 'form-control','placeholder'=>'nombres',"style"=>"width:100%","id"=>"nombres","name"=>"nombres"]) !!}
																							</div>
																							<div class="col-md-4">
																							<label class="col-md-2 control-label" for="modalidad">Apellidos</label>  

																							{!! Form::text('apellidos', $datos['data']["apellidos"],['class' => 'form-control ','placeholder'=>'apellidos',"style"=>"width:100%","id"=>"apellidos","name"=>"apellidos"]) !!}
																							</div>
																							<br/>
																						</div>
																						<div class="form-group">
																						<div class="col-md-4">
																								<label class="col-md-12 control-label" for="convencional">Provincia</label>  
																							{!! Form::text('provincia', $datos['data']["provincia_id"],['class' => 'form-control','placeholder'=>'Provincia',"style"=>"width:100%","id"=>"provincia","name"=>"provincia"]) !!}
																							</div>
																							<div class="col-md-4">
																								<label class="col-md-4 control-label" for="convencional">Ciudad</label>  
																							<!--<input type="text" id="dciudad" value='{{session("data")["ciudad_id"]}}'>-->

																							{!! Form::text('ciu', $datos['data']["ciudad_id"],['class' => 'form-control','placeholder'=>'Ciudad',"style"=>"width:100%","id"=>"ciu","name"=>"ciu"]) !!}
																							</div>
																							<div class="col-md-4">
																								<label class="col-md-12 control-label" for="celular">Direccion</label>  
																							{!! Form::textarea('direccion', $datos['data']["direccion"],['class' => 'form-control-t','placeholder'=>'direccion',"style"=>"max-height:100px!important;max-width:210px!important","id"=>"direccion","name"=>"direccion"]) !!}

																							</div>

																						</div>
																							
																					
																						<div class="form-group">
																						<div class="col-md-4">
																								<label class="col-md-12 control-label" for="convencional">Estado Civil</label>  

																							{!! Form::select('estado_civil', ['SOLTERO'=>'SOLTERO','CASADO'=>'CASADO','VIUDO'=>'VIUDO','DIVORCIADO'=>'DIVORCIADO','UNION LIBRE'=>'UNION LIBRE'],  $datos['data']["estado_civil"],['class' => 'form-control select2',"style"=>"width:100%","id"=>"estado_civil","name"=>"estado_civil"]) !!}
																							</div>
																							<div class="col-md-4">
																								<label class="col-md-4 control-label" for="convencional">Edad</label>  
																							{!! Form::text('edad', $datos['data']["edad"],['class' => 'form-control','placeholder'=>'edad',"onKeypress"=>"return soloNumeros(event)","style"=>"width:100%","id"=>"edad","name"=>"edad"]) !!}

																							</div>
																							<div class="col-md-4">
																								<label class="col-md-12 control-label" for="celular">Fecha de Nacimiento</label>  
																							{!! Form::date('fecha_nacimiento', $datos['data']["fecha_nacimiento"],['class' => 'form-control','placeholder'=>'fecha_nacimiento',"style"=>"width:100%","id"=>"fecha_nacimiento","name"=>"fecha_nacimiento"]) !!}

																							</div>

																						</div>
																						<div class="form-group">
																						<div class="col-md-4">
																								<label class="col-md-12 control-label" for="convencional">Correo</label>  
																							{!! Form::email('correo', $datos['data']["correo"],['class' => 'form-control','placeholder'=>'correo',"style"=>"width:100%","id"=>"correo","onblur"=>"validarEmail('correo')","name"=>"correo"]) !!}

																							</div>
																							<div class="col-md-4">
																								<label class="col-md-12 control-label" for="celular">Correo Institucional</label>  
																							{!! Form::text('correo_institucional', $datos['data']["correo_institucional"],['class' => 'form-control','placeholder'=>'Correo Institucional',"style"=>"width:100%","onblur"=>"validarEmail('correo_institucional')","id"=>"correo_institucional","name"=>"correo_institucional"]) !!}

																							</div>
																							<div class="col-md-4">
																								<label class="col-md-4 control-label" for="convencional">Convencional</label>  
																							{!! Form::text('convencional', $datos['data']["convencional"],['class' => 'form-control', "maxlength"=>"10",'placeholder'=>'convencional',"style"=>"width:100%","onKeypress"=>"return soloNumeros(event)","id"=>"convencional","name"=>"convencional"]) !!}

																							</div>
																							</div>
																							<div class="col-md-4">
																								<label class="col-md-12 control-label" for="celular">Teléfono móvil</label>  
																							{!! Form::text('celular', $datos['data']["celular"],['class' => 'form-control', "maxlength"=>"10","style"=>"width:100%","onKeypress"=>"return soloNumeros(event)","id"=>"celular","name"=>"celular"]) !!}

																							</div>


																						</div>
																						<div class="form-group">
																						<div class="col-md-4">
																								<label class="col-md-12 control-label" for="convencional">labora</label>  

																							{!! Form::select('labora', ['SI'=>'SI','NO'=>'NO'], $datos['data']["labora"],['class' => 'form-control select2',"style"=>"width:100%","onchange"=>"disabledlabo()","id"=>"labora","name"=>"labora"]) !!}
																							</div>
																							<div class="col-md-4">
																								<label class="col-md-12 control-label" for="convencional">Profesión  y Ocupación</label>  
																							{!! Form::text('ocupacion', $datos['data']["ocupacion"],['class' => 'form-control','placeholder'=>'Ocupación laboral',"style"=>"width:100%","id"=>"ocupacion","name"=>"ocupacion"]) !!}

																							</div>
																						
																							<div class="col-md-4">
																								<label class="col-md-12 control-label" for="convencional">Horario de Trabajo</label>  
																							{!! Form::text('horario_t', $datos['data']["horario_t"],['class' => 'form-control','placeholder'=>'horario laboral',"style"=>"width:100%","id"=>"horario_t","name"=>"horario_t"]) !!}

																							</div>
																							<div class="col-md-8">
																								<label class="col-md-12 control-label" for="convencional">Direccion Laboral</label>  
																							{!! Form::textarea('direccion_t', $datos['data']["direccion_t"],['class' => 'form-control-t','placeholder'=>'direccion laboral',"style"=>"max-height:100px!important;max-width:100%!important","id"=>"direccion_t","name"=>"direccion_t"]) !!}

																							</div>
																							<div class="col-md-4">
																								<label class="col-md-12 control-label" for="celular">Teléfono Laboral</label>  
																							{!! Form::text('telefono_t', $datos['data']["telefono_t"],['class' => 'form-control', "maxlength"=>"10",'placeholder'=>'telefono laboral',"style"=>"width:100%","onKeypress"=>"return soloNumeros(event)","id"=>"telefono_t","name"=>"telefono_t"]) !!}

																					
																							</div>
																							
																						</div>
																						<div class="form-group">
																						<div class="col-md-4">
																								<label class="col-md-12 control-label" for="convencional">discapacidad</label>  

																							{!! Form::select('discapacidad', ['SI'=>'SI','NO'=>'NO'], $datos['data']["discapacidad"],['class' => 'form-control select2',"style"=>"width:100%","id"=>"discapacidad","name"=>"discapacidad","onchange"=>"disabledDisc()"]) !!}
																							</div>
																							<div class="col-md-4">
																								<label class="col-md-12 control-label" for="convencional">Carnet de CONADIS</label>  
																							{!! Form::text('carnet', $datos['data']["carnet"],['class' => 'form-control', "maxlength"=>"10",'placeholder'=>'carnet CONADIS',"style"=>"width:100%","id"=>"carnet","name"=>"carnet"]) !!}

																							</div>
																							
																							<div class="col-md-12">
																							<hr/>

																							<label class="col-md-12 control-label" for="convencional">Area de preferencia</label>  
																							<hr/>
																							<div class="col-md-4"> 
																							<label>CIVIL</label>{!!Form::checkbox('civil', $datos['data']['civil'] ,  $datos['data']['civil']) !!}
																							</div>
																							<div class="col-md-4"> 
																							<label>PENAL</label>{!!Form::checkbox('penal', $datos['data']['penal'] , $datos['data']['penal']) !!}
																							</div>
																							<div class="col-md-4"> 
																							<label>LABORAL</label>{!!Form::checkbox('laboral', $datos['data']['laboral'] , $datos['data']['laboral']) !!}
																							</div>
																							<div class="col-md-4"> 
																							<label>FAMILIA</label>{!!Form::checkbox('familia', $datos['data']['familia'] , $datos['data']['familia']) !!}
																							</div>
																							<div class="col-md-4"> 
																							<label>VIOLENCIA INTRAFAMILIAR</label>{!!Form::checkbox('violenciaf', $datos['data']['violenciaf'] , $datos['data']['violenciaf']) !!}
																							</div>
																							<div class="col-md-4"> 
																							<label>INQUILINATO</label>{!!Form::checkbox('inquilinato', $datos['data']['inquilinato'] , $datos['data']['inquilinato']) !!}
																							</div>
																							<div class="col-md-4"> 
																							<label>FISCALIA</label>{!!Form::checkbox('fiscalia', $datos['data']['fiscalia'] , $datos['data']['fiscalia'] ) !!}
																							</div>
																							<div class="col-md-4"> 
																							<label>DEFENSORIA PUBLICA</label>{!!Form::checkbox('defensoria', $datos['data']['defensoria'] , $datos['data']['defensoria']) !!}
																							</div>
																							<div class="col-md-4"> 
																							<label>CONSTITUCIONAL</label>{!!Form::checkbox('constitucional', $datos['data']['constitucional'] , $datos['data']['constitucional']) !!}
																							</div>

																							</div>
																							<div class="col-md-12">
																							<hr/></div>
																						</div>

																	
																		</fieldset>
																		
														</div>
																	
											</div>
											<hr/>
						  
				  
											<div class="panel-body">
												
												<input type="hidden" name="_token" value="{{ csrf_token() }}">
												<input type="hidden" value='{{$datos['data']['id']}}' name="idusuario">

												<div class="form-group">

													<label class="col-md-4 control-label">Cedula</label>
													<div class="col-md-4">
													@if($datos['data']['cedula_archivo'])
													<span class="label label-success label-sm"><i class="fa fa-check"></i></span>
													@endif
													@if(!$datos['data']['cedula_archivo'])
													<span class="label label-warning label-sm"><i class="fa fa-times"></i></span>
													@endif
													</div>
													<input type="file" class="form-control" name="cedula" accept="application/pdf" >
													
													<label class="col-md-4 control-label">Papeleta/votacion</label>
													@if($datos['data']['papeleta_archivo'])
													<span class="label label-success label-sm"><i class="fa fa-check"></i></span>
													@endif
													@if(!$datos['data']['papeleta_archivo'])
													<span class="label label-warning label-sm"><i class="fa fa-remove"></i></span>
													@endif
													<input type="file" class="form-control" name="papeleta" accept="application/pdf">
													
													<label class="col-md-4 control-label">Foto</label>
													@if($datos['data']['foto_archivo'])
													<span class="label label-success label-sm"><i class="fa fa-check"></i></span>
													@endif
													@if(!$datos['data']['foto_archivo'])
													<span class="label label-warning label-sm"><i class="fa fa-remove"></i></span>
													@endif
													<input type="file" class="form-control" name="foto"accept="application/pdf">
												
													<label class="col-md-4 control-label">Curriculum</label>
													@if($datos['data']['curriculum_archivo'])
													<span class="label label-success label-sm"><i class="fa fa-check"></i></span>
													@endif
													@if(!$datos['data']['curriculum_archivo'])
													<span class="label label-warning label-sm"><i class="fa fa-remove"></i></span>
													@endif
													<input type="file" class="form-control" name="curriculum" accept="application/pdf">
												
													<label class="col-md-4 control-label">Certificado de Matricula</label>
													@if($datos['data']['certificado_matricula'])
													<span class="label label-success label-sm"><i class="fa fa-check"></i></span>
													@endif
													@if(!$datos['data']['certificado_matricula'])
													<span class="label label-warning label-sm"><i class="fa fa-remove"></i></span>
													@endif
													<input type="file" class="form-control" name="certificado_matricula" accept="application/pdf" >
													
													<label class="col-md-4 control-label">Certificado de No Arrastre</label>
													@if($datos['data']['certificado_no_arrastre'])
													<span class="label label-success label-sm"><i class="fa fa-check"></i></span>
													@endif
													@if(!$datos['data']['certificado_no_arrastre'])
													<span class="label label-warning label-sm"><i class="fa fa-remove"></i></span>
													@endif
													<input type="file" class="form-control" name="certificado_arrastre" accept="application/pdf" >
												
													<label class="col-md-4 control-label">Solicitud Sellada</label>
													@if($datos['data']['solicitud_sellada'])
													<span class="label label-success label-sm"><i class="fa fa-check"></i></span>
													@endif
													@if(!$datos['data']['solicitud_sellada'])
													<span class="label label-warning label-sm"><i class="fa fa-remove"></i></span>
													@endif
													<input type="file" class="form-control" name="solicitud_sellada" accept="application/pdf" >
												
												</div>
												
												<div class="col-md-12">
												
													<hr/>
												</div>
												<div class="form-group">
												
													<div class="col-md-6 col-md-offset-4">
													<button type="submit" class="btn btn-primary" value="3" name="btnvg">Grabar</button>
												</form>
														<a href="{{route('student.imprimirFicha')}}" target="_blank" class="btn btn-success">Imprimir</a>
													</div>
												</div>
											</div>
							
						</div>

		

				 