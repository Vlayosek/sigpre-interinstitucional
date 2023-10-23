<div class="panel-heading">Hola, {!!$usuario=$datos['usuario']!!} </div>
					
					
						
			<div class="modal-content">

	
						<div class="modal-body" >

							<div class="agileits-w3layouts-info">
							<div style="background: url('/images/fondo.jpeg') no-repeat center;background-size: 500px 600px;">
							<form method="POST" action="{{ route ('admin.estudianteasignacion')}}" accept-charset="UTF-8" enctype="multipart/form-data">
							<div class=" panel panel-body col-md-12">

							<input type="hidden" value="{{$cc}}" id="cc" name="cc">
									<div class="col-md-12">
									<div class="col-md-2">
									<label>Lugar:</label>
									</div>
									<div class="col-md-4">
										{!! Form::select('lugar', $lugares, $lugar_id,['class' => 'form-control select2','placeholder'=>'Lugares',"style"=>"width:100%","id"=>"lugar","name"=>"lugar"]) !!}

									</div>
									</div>
											<div class="col-md-12">

									<div class="col-md-2">
									<label>Supervisor:</label>

									</div>
									<div class="col-md-4">
									{!! Form::select('supervisor',$supervisor, $user_doc,['class' => 'form-control select2','placeholder'=>'Supervisores',"style"=>"width:100%","id"=>"supervisor","name"=>"supervisor"]) !!}

									</div>
									<div class="col-md-12">
									<div class="col-md-2">
									<label>Horario/entrada:</label>

									</div>
									<div class="col-md-2">
									
								<!--	{!! Form::select('horario',$horario, $horario_id,['class' => 'form-control select2','placeholder'=>'Horario',"style"=>"width:100%","id"=>"horariosd","name"=>"horario"]) !!}-->
									<input type="time" value="{{$horario_inicio}}" step="3600" id="horario_inicio" name="horario_inicio"  min="9:00" max="15:00" required onclick="agregahora2()" onkeyup="agregahora2()" />
									</div>
									</div>
									<div class="col-md-12">

									<div class="col-md-2">
									<label>Horas/trabajo:</label>

									</div>
									<div class="col-md-3">
								<!--	{!! Form::select('horario',$horario, $horario_id,['class' => 'form-control select2','placeholder'=>'Horario',"style"=>"width:100%","id"=>"horariosd","name"=>"horario"]) !!}-->
									<input type="text" value="{{$cant_horas}}" id="cant_horas"  name="cant_horas" onkeyup="agregahora2()" onKeypress="return soloNumeros6(event)" maxlength="1" required />
									
									</div>
									<div class="col-md-2">
								<!--	{!! Form::select('horario',$horario, $horario_id,['class' => 'form-control select2','placeholder'=>'Horario',"style"=>"width:100%","id"=>"horariosd","name"=>"horario"]) !!}-->
										<h6><strong>Solo rango de 2 - 6</strong></h6>									
									</div>
									</div>
									<div class="col-md-12">

									<div class="col-md-2">
									<label>Horario/salida:</label>

									</div>
									<div class="col-md-2">
									<div style="display:none"> 
									<input type="time" value="{{$horario_fin}}" name="idhf"  id="idhf">
										</div>
									<input type="time" value="{{$horario_fin}}" id="horario_fin" name="horario_fin"  min="9:00" max="18:00"/>

									</div>
									</div>
									</div>
</div>							
							</div>
													
								</div>
						
						  
				 				 <hr/>
							<div class="panel-body">
								
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" value="{{$datos['data']['id']}}" name="idusuario">


								<div class="col-md-12">
								
								<hr/>
								<div class="alert alert-{{$tipoM}}" id="divmensaje">
 
								  {{$message}}.
								</div>
								
								</div>
								<div class="form-group">
								  <div class="col-md-6 col-md-offset-4">
								<button type="submit" style="display:none" class="btn btn-primary" value="3" name="btnvgenv"id="btnvgenv">Grabar</button>

								  </div>
								</div>
							  </form>
							  <div id="btnvgverificadiv"> 
							  <div class="col-md-4">
								<span class="btn btn-primary" value="3" name="btnvgverifica" id="btnvgverifica">Grabar</span>
								<a href="".route('/admin/estudianteperfil')."" class="btn btn-danger" name="btncancelar">Cancelar</a>
								</div>
								</div>
							</div>
							
							</div>

				 