
<!DOCTYPE html>
<html lang="es">


<head>
   <style>
     @page { margin: 160px 30px; }
     #header { position: fixed; 
	 
	 left: 0px; 
	 top: -130px;
	  right: 0px; 
	  height: 30px;
	   background-color: #741b47;
	    text-align: center; 
		padding:50px 50px 50px 50px;
		}
     #footer { position: fixed; left: 0px; bottom: -60px; right: 0px; height: 50px; background-color: #ffffff; }
	 td {
		font-family: Arial, Helvetica, Verdana;
		color:#ffffff;
		font-size:13px;
		padding-left:3px;
	}
   	.container {
  border: 2px solid #dedede;
  background-color: #9fc5f8;
  border-radius: 5px;
  padding: 10px;
  margin: 10px 0;
  top: -200px;
}
.contenido{
	background-color: #ffffff;
	height: 90%;
	color:#000;
	padding:20px;
}
.darker {
  border-color: #ccc;
  background-color: #ddd;
}

.container::after {
  content: "";
  clear: both;
  display: table;
}

.container img {
  float: left;
  max-width: 60px;
  width: 100%;
  margin-right: 20px;
  border-radius: 50%;
}

.container img.right {
  float: right;
  margin-left: 20px;
  margin-right:0;
}

.time-right {
  float: right;
  color: #aaa;
}

.time-left {
  float: left;
  color: #999;
}
  </style>
  <body>
   <div id="header">
		 <table style="width:100%" >
		 		 <tr>
					  <td colspan="5" style="font-weight:bold;text-align:center;font-size:20px">
					  Datos Solicitud de Autorización de Servicios y Recursos Tecnológicos Adicionales </td>
				</tr>
				
		</table>
		
   </div>
   <div id="footer">
   <p class="page" style="text-align:center;margin-right:5px">
    <img src="data:image/svg+xml;base64,{{ base64_encode($codigoQR) }}"></p>
   </div>
   <div id="content" class="contenido">
   			<table width="100%" >
 			   <tr>
					<td width="10%"  style="font-weight:bold;color:#000"><span>Fecha Solicitud:</span></td>
					<td width="50%" style="font-weight:normal;color:#000">{{$fecha}}</td>
					<td width="10%" style="font-weight:bold;color:#000"># Solicitud:</td>
					<td width="30%" colspan="2" style="font-weight:normal;color:#000">{{$id}}</td>
				</tr>
				<tr>
					<td width="10%"  style="font-weight:bold;color:#000"><span>Nombres Completos:</span></td>
					<td width="50%" style="font-weight:normal;color:#000">{{$nc}}</td>
					<td width="10%" style="font-weight:bold;color:#000">Cedula:</td>
					<td width="30%" colspan="2" style="font-weight:normal;color:#000">{{$cedula}}</td>
				</tr>
				<tr>
					<td width="10%"  style="font-weight:bold;color:#000"><span>Area:</span></td>
					<td width="50%" style="font-weight:normal;color:#000">{{$area}}</td>
					<td width="10%" style="font-weight:bold;color:#000">Cargo:</td>
					<td width="30%" colspan="2" style="font-weight:normal;color:#000">{{$cargo}}</td>
				</tr>
				<tr>
					<td width="20%" style="font-weight:bold;color:#000">Ciudad:</td>
					<td width="80%" colspan="4" style="font-weight:normal;color:#000">
					{{$ciudad}}
					</td>
				</tr>
				<tr>
					<td width="100%" style="font-weight:bold;color:#000" colspan="5"><hr/></td>
				</tr>
				<tr>
					<td width="20%" style="font-weight:bold;color:#000">Categoria:</td>
					<td width="80%" colspan="4" style="font-weight:normal;color:#000">
					{{$categoria}}
					</td>
				</tr>
				<tr>
					<td width="20%" style="font-weight:bold;color:#000">Aciones:</td>
					<td width="40%" colspan="2" style="font-weight:normal;color:#000">
					{!!$acciones1!!}
					</td>
					<td width="40%" colspan="2" style="font-weight:normal;color:#000">
					{!!$acciones2!!}
					</td>
				</tr>
				<tr>
					<td width="100%" style="font-weight:bold;color:#000" colspan="5"><hr/></td>
				</tr>
				<tr>
					<td width="20%" style="font-weight:bold;color:#000">Dispositivo:</td>
					<td width="40%" colspan="2" style="font-weight:normal;color:#000">
					{{$dispositivo}}
					</td>
					<td width="40%" colspan="2" style="font-weight:normal;color:#000">
					{!!$descripcion_bien!!}
					</td>
				</tr>
				<tr>
					<td width="20%" style="font-weight:bold;color:#000">Ip/Equipo:</td>
					<td width="40%" colspan="2" style="font-weight:normal;color:#000">
					{{$ip}}
					</td>
					<td width="40%" colspan="2" style="font-weight:normal;color:#000">
					<span style="font-weight:bold;color:#000">Serie:</span>{{$serie}}
					</td>
				</tr>
				<tr>
					<td width="20%" style="font-weight:bold;color:#000">Anydesk:</td>
					<td width="40%" colspan="2" style="font-weight:normal;color:#000">
					{{$anydesk}}
					</td>
					<td width="40%" colspan="2" style="font-weight:normal;color:#000">
					{{$marca_modelo}}
					</td>
				</tr>
				<tr>
					<td width="100%" style="font-weight:bold;color:#000" colspan="5"><hr/></td>
				</tr>
				<tr>
					<td width="10%" style="font-weight:bold;color:#000">Fecha y Hora de Inicio:</td>
					<td width="40%"  style="font-weight:normal;color:#000">
					{{$finicio}}
					</td>
					<td width="10%" style="font-weight:bold;color:#000">Fecha y Hora de Inicio:</td>
					<td width="40%" colspan="2" style="font-weight:normal;color:#000">
					{{$ffin}}
					</td>
				</tr>
				<tr>
					<td width="20%" style="font-weight:bold;color:#000">Justificacion:</td>
					<td width="40%" colspan="4" style="font-weight:normal;color:#000">
					{{$descripcion}}
					</td>
				</tr>
				<tr>
					<td width="100%" style="font-weight:bold;color:#000" colspan="5"><hr/></td>
				</tr>
				<tr>
					<td width="100%" colspan="5" style="font-weight:bold;color:#000;text-align:center">
					Autorización Jefe Inmediato</td>
				</tr>
				<tr>
					<td width="20%" style="font-weight:bold;color:#000">Nombres Completos:</td>
					<td width="40%" colspan="4" style="font-weight:normal;color:#000">
					{{$jefe}}
					</td>
				</tr>
				<tr>
					<td width="20%" style="font-weight:bold;color:#000">Cargo:</td>
					<td width="40%" colspan="4" style="font-weight:normal;color:#000">
					{{$jefecargo}}
					</td>
				</tr>
				
   </table>
		
   </div>
 </body>

</html>
