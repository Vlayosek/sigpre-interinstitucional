
<!DOCTYPE html>
<html lang="es">
<head>
@include('frontend.partials.head')

</head>
<style>
/* DivTable.com */
.divTable{
	display: table;
	width: 100%;
}
.divTableRow {
	display: table-row;
}
.divTableHeading {
	background-color: #EEE;
	display: table-header-group;
}
.divTableCell, .divTableHead {
	border: 1px solid #999999;
	display: table-cell;
	padding: 3px 10px;
}
.divTableHeading {
	background-color: #EEE;
	display: table-header-group;
	font-weight: bold;
}
.divTableFoot {
	background-color: #EEE;
	display: table-footer-group;
	font-weight: bold;
}
.divTableBody {
	display: table-row-group;
}
</style>
<body>

<div id="renderMe">
			
									<div class="modal-content">

															<div class="modal-header">
															<table width="100%" >
															<tr>
															<td>
															<img src="{{public_path('images/')}}ug.png"  width="100px" height="120px">
															</td>
															<td>	 <center>
																			<h5>UNIVERSIDAD DE GUAYAQUIL</h5>
																		
																</center></td>
																
																<td>
																
																<img src="{{public_path('images/')}}juris.png"style="float:right" width="80px" height="120px">

																</td>
																</tr>
																</table>
															<center><strong>	
															<h3>
																			INFORME SEMANAL</h3>
																			<h3>
																		
																			</strong>
																			</center>
															</div>

																<div class="modal-body" >

																	<div class="agileits-w3layouts-info">
																	<div style="background: url('/images/fondo1.png') no-repeat center;background-size: 200px 300px;">
																	<div class="divTable"  class="ab">
																			<div class="divTableBody">
																			<div class="divTableRow">
																			<div class="divTableCell">&nbsp;<span  class="ab">Estudiante</span></div>
																			<div class="divTableCell">&nbsp;<span  class="ab">{{$objPostulant->apellidos.' '.$objPostulant->nombres}}</span></div>
																			</div>
																			<div class="divTableRow">
																			<div class="divTableCell">&nbsp;<span  class="ab">Facultad</span></div>
																			<div class="divTableCell">&nbsp;<span  class="ab">Facultad de Jurisprudencia y Ciencias Sociales</span></div>
																			</div>
																			<div class="divTableRow">
																			<div class="divTableCell">&nbsp;<span  class="ab">Carrera</span></div>
																			<div class="divTableCell">&nbsp;<span  class="ab">{{$objPostulant->carrera}}</span></div>
																			</div>
																			<div class="divTableRow">
																			<div class="divTableCell">&nbsp;<span  class="ab">Supervisor</span></div>
																			<div class="divTableCell">&nbsp;<span  class="ab">{{$supervisor}}</span></div>
																			</div>
																			<div class="divTableRow">
																			<div class="divTableCell">&nbsp;<span  class="ab">Semana</span></div>
																			<div class="divTableCell">&nbsp;<span  class="ab">{{$semana}}</span></div>
																			</div>
																			</div>
																			</div>
														<br/>
																	<table width="100%" border="1" style="text-align:left;" cellpadding="0" cellspacing="0" class="ab" >
																	<tr>
																		<td><strong>Fecha</strong></td>
																		<td><strong>Horas diarias</strong></td>
																		<td><strong>Descripcion de tareas desarrolladas</strong></td>
																	</tr>
																	<div style="display:none">{!!$cchoras=0!!}</div>
																	@foreach ($objAsistencia as $asistencia)
																	<tr>
																		<td>{{$asistencia["fecha"]}}</td>
																			<div style="display:none">{!! $cchoras=$cchoras+$asistencia["horas"]!!}</div>
																		<td>{{$asistencia["horas"]}} de horas laborales</td>
																		<td>{{$asistencia["descripcion"]}}</td>
																	</tr>																	</tr>
																	@endforeach
																	<tr>
																	<td><strong>Total de horas</strong></td>
																	<td>{{$cchoras}}</td>
																	<td></td>
																	</tr>
																	</table>
																	<br/>
																	<table width="100%" class="ab">
																	<tr>
																	<td><strong>Observaciones:</strong>
																	</td>
																	<td>{{$observaciones}}
																	</td>
																	</table>

															
																	


																	<table style="text-align:center;margin-top:120px" width="100%">
																	<tr>
																	<td style="">Firma
																	</td>
																	
																	<td>Sello de la Institucion
																	</td>
																	
																	</tr>
																		<tr>
																	<td>{{$supervisor}}
																	</td>
																	
																	<td>
																	</td>
																	
																	</tr>
																	</table>
																	</div>					
										 </div>
					</div>
		
<div id="editor"></div>


	<!-- //map -->
	<!-- footer -->
	<!-- //footer -->
	<!-- //footer -->


</body>

</html>
