
<!DOCTYPE html>
<html lang="es">


<head>
   <style>
     @page { margin: 180px 30px; }
     #header { position: fixed; left: 0px; top: -180px; right: 0px; height: 250px; background-color: #17a2b8; text-align: center; }
     #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 80px; background-color: #ffffff; }
     #footer .page:after { content: counter(page, upper-roman); }
	 td {
		font-family: Arial, Helvetica, Verdana;
		color:#ffffff;
		font-size:13px;
		padding-left:3px;
	}
   	.container {
  border: 2px solid #dedede;
  background-color: #f1f1f1;
  border-radius: 5px;
  padding: 10px;
  margin: 10px 0;
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
					  <td colspan="5" style="font-weight:bold;text-align:center;font-size:20px">DETALLE DEL CHAT, TICKET {{$id}}</td>
				</tr>
				<tr>
					  <td colspan="5"  style="font-weight:bold;text-align:center;"><hr/></td>
				</tr>
				<tr>
					  <td colspan="5"  style="font-weight:bold;text-align:center;"><br/></td>
				</tr>
				<tr>
					<td width="10%"  style="font-weight:bold"><span>#Ticket:</span></td>
					<td width="50%" style="font-weight:normal">{{$id}}</td>
					<td width="10%" style="font-weight:bold">Fecha:</td>
					<td width="30%" colspan="2" style="font-weight:normal">{{$fecha}}</td>
				</tr>
				<tr>
					<td width="20%" style="font-weight:bold">Usuario Requirente:</td>
					<td width="80%" colspan="4" style="font-weight:normal">{{$nombreCompleto}}</td>
				</tr>
				<tr>
					<td width="10%" style="font-weight:bold">Tipo Soporte:</td>
					<td width="50%" style="font-weight:normal">{{$tipo}}</td>
					<td width="10%" style="font-weight:bold">Categoría:</td>
					<td width="30%"colspan="2" style="font-weight:bold">{{$categoria}}</td>
				</tr>
				<tr>
					<td width="20%" style="font-weight:bold">Descripción:</td>
					<td width="80%" colspan="4" style="font-weight:normal">
					{{$descripcion}}
					</td>
				</tr>
		</table>
   </div>
   <div id="footer">
     <p class="page" style="text-align:right;margin-right:5px">Pagina <?php $PAGE_NUM ?></p>
   </div>
   <div id="content" >
			{!! $html !!}
			{!! $htmlAnexo !!}
   </div>
 </body>

</html>
