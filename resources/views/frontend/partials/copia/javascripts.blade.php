{{--<script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>--}}
<script type="text/javascript" src="{{asset('js/jquery/jquery2.js')}}"></script>
	<script>

	$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
		// You can also use "$(window).load(function() {"
		$(function () {
			   var a = $("#dciudad").val();

			 $("#ciudad").val(a).change();
			 //revisar
			// Slideshow 4
			$("#slider4").responsiveSlides({
				auto: true,
				pager: true,
				nav: true,
				speed: 500,
				namespace: "callbacks",
				before: function () {
					$('.events').append("<li>before event fired.</li>");
				},
				after: function () {
					$('.events').append("<li>after event fired.</li>");
				}
			});

		});
	</script>
	<script>
		// You can also use "$(window).load(function() {"
		$(function () {
			// Slideshow 4
			$("#slider3").responsiveSlides({
				auto: true,
				pager: false,
				nav: false,
				speed: 500,
				namespace: "callbacks",
				before: function () {
					$('.events').append("<li>before event fired.</li>");
				},
				after: function () {
					$('.events').append("<li>after event fired.</li>");
				}
			});

		});
    </script>
	<script src="{{ asset('js/modules') }}/utils.js?v=2"></script>
    <script src="{{ asset('js/modules') }}/Core.js"></script>

    <script src="{{ asset('frontend/js') }}/responsiveslides.min.js"></script>
    <script src="{{ asset('frontend/js') }}/bars.js"></script>
    <script src="{{ asset('frontend/js') }}/jarallax.js"></script>
    <script src="{{ asset('frontend/js') }}/SmoothScroll.min.js"></script>
	<script src="{{ asset('adminlte/plugins/notifications/pnotify.min.js') }}"></script>
	<script type="text/javascript">
		/* init Jarallax */
		$('.jarallax').jarallax({
			speed: 0.5,
			imgWidth: 1366,
			imgHeight: 768
		})
    </script>
        <script type="text/javascript" src="{{ asset('frontend/js') }}/easing.js"></script>

	<script type="text/javascript">
		jQuery(document).ready(function ($) {
			$(".scroll").click(function (event) {
				event.preventDefault();
				$('html,body').animate({
					scrollTop: $(this.hash).offset().top
				}, 1000);
			});
		});
	</script>
	<!-- here stars scrolling icon -->
	<script type="text/javascript">
		$(document).ready(function () {
			$("#dependencia").hide();				

				var defaults = {
				containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear' 
				};

			$().UItoTop({
				easingType: 'easeOutQuart'
			});

		});
	</script>
    <!-- //here ends scrolling icon -->
            <script src="{{ asset('frontend/js') }}/move-top.js"></script>
            <script src="{{ asset('frontend/js') }}/bootstrap.js"></script>
<script>
function valida(campo) {
    var numero = campo;
    var suma = 0;
    var residuo = 0;
    var ju = false;
    var pub = false;
    var nat = false;
    var numeroProvincias = 22;
    var modulo = 11;

    d1 = numero.substr(0, 1);
    d2 = numero.substr(1, 1);
    d3 = numero.substr(2, 1);
    d4 = numero.substr(3, 1);
    d5 = numero.substr(4, 1);
    d6 = numero.substr(5, 1);
    d7 = numero.substr(6, 1);
    d8 = numero.substr(7, 1);
    d9 = numero.substr(8, 1);
    d10 = numero.substr(9, 1);

    /* El tercer digito es: */
    /* 9 para sociedades privadas y extranjeros */
    /* 6 para sociedades publicas */
    /* menor que 6 (0,1,2,3,4,5) para personas naturales */

    if (d3 == 7 || d3 == 8) {
        alert('El tercer dígito ingresado es inválido');
    }

    /* Solo para personas naturales (modulo 10) */
    if (d3 < 6) {
        nat = true;
        p1 = d1 * 2;
        if (p1 >= 10) p1 -= 9;
        p2 = d2 * 1;
        if (p2 >= 10) p2 -= 9;
        p3 = d3 * 2;
        if (p3 >= 10) p3 -= 9;
        p4 = d4 * 1;
        if (p4 >= 10) p4 -= 9;
        p5 = d5 * 2;
        if (p5 >= 10) p5 -= 9;
        p6 = d6 * 1;
        if (p6 >= 10) p6 -= 9;
        p7 = d7 * 2;
        if (p7 >= 10) p7 -= 9;
        p8 = d8 * 1;
        if (p8 >= 10) p8 -= 9;
        p9 = d9 * 2;
        if (p9 >= 10) p9 -= 9;
        modulo = 10;
    }

    /* Solo para sociedades publicas (modulo 11) */
    /* Aqui el digito verficador esta en la posicion 9, en las otras 2 en la pos. 10 */
    else if (d3 == 6) {
        pub = true;
        p1 = d1 * 3;
        p2 = d2 * 2;
        p3 = d3 * 7;
        p4 = d4 * 6;
        p5 = d5 * 5;
        p6 = d6 * 4;
        p7 = d7 * 3;
        p8 = d8 * 2;
        p9 = 0;
    }

    /* Solo para entidades privadas (modulo 11) */
    else if (d3 == 9) {
        ju = true;
        p1 = d1 * 4;
        p2 = d2 * 3;
        p3 = d3 * 2;
        p4 = d4 * 7;
        p5 = d5 * 6;
        p6 = d6 * 5;
        p7 = d7 * 4;
        p8 = d8 * 3;
        p9 = d9 * 2;
    }

    suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
    residuo = suma % modulo;

    /* Si residuo=0, dig.ver.=0, caso contrario 10 - residuo*/
    digitoVerificador = residuo == 0 ? 0 : modulo - residuo;

    var b = 0;
    /* ahora comparamos el elemento de la posicion 10 con el dig. ver.*/

    if (pub == true) {

        if (digitoVerificador != d9) {
            alert('El ruc de la empresa del sector público es incorrecto.', 3500);
            b = 1;
        }
        /* El ruc de las empresas del sector publico terminan con 0001*/
        if (numero.substr(9, 4) != '0001') {
            alert('El ruc de la empresa del sector público debe terminar con 0001', 3500);
            b = 1;
        }
        if (b == 0) {
            b = 2;
        }
    }
    else if (ju == true) {
        if (digitoVerificador != d10) {
            alertToast('El ruc de la empresa del sector privado o Juridico es incorrecto.', 3500);
            b = 1;
        }
        if (numero.substr(10, 3) != '001') {
            alert('El ruc de la empresa del sector privado  o Juridico debe terminar con 001', 3500);
            b = 1;
        }
        if (b == 0) {
            b = 3;
        }
    }

    else if (nat == true) {

        if (digitoVerificador != d10) {
            alert('El número de cédula de la persona natural es incorrecto.', 3500);

            b = 1;
        }
        if (numero.length > 10 && numero.substr(10, 3) != '001') {
            alert('El ruc de la persona natural debe terminar con 001', 3500);
            b = 1;
        }
        if (b == 0 && numero.length > 10) {
            b = 4;
        } else if (b == 0 && numero.length == 10) {
            b = 5;

        }

    }

return b;
}
function disabledDisc(){
  var discapacidad=  document.getElementById("discapacidad").value;
    if(discapacidad=='NO')
    {
    var carnet=document.getElementById("carnet").disabled=true;
   
    }else{
        var carnet=document.getElementById("carnet").disabled=false;

    }
}
function disabledlabo(){
   var labora= document.getElementById("labora").value;
   

    if(labora=='NO')
    {
    var ocupacion=document.getElementById("ocupacion").disabled=true;
    var horario_t= document.getElementById("horario_t").disabled=true;
    var direccion_t=document.getElementById("direccion_t").disabled=true;
    var telefono_t=document.getElementById("telefono_t").disabled=true;
    }
    else{
        var ocupacion=document.getElementById("ocupacion").disabled=false;
    var horario_t= document.getElementById("horario_t").disabled=false;
    var direccion_t=document.getElementById("direccion_t").disabled=false;
    var telefono_t=document.getElementById("telefono_t").disabled=false;
    }
}

  
$('#btnverif').on('click', function (e) {

var labora=$("#labora").val();
var discapacidad=$("#discapacidad").val();
var b=0;
    if(labora=='SI')
    {
        var ocupacion=$("#ocupacion").val();
        var horario_t= $("#horario_t").val();
        var direccion_t=$("#direccion_t").val();
        var telefono_t=$("#telefono_t").val();
        if(ocupacion==''||ocupacion==null||horario_t==null||telefono_t==null||direccion_t==''||horario_t==''||telefono_t==''||direccion_t=='')
        {
            alert("Debe llenar los campos laborales de: ocupacion, horario, direccion y telefono")

            b=1;
        }
    }
    if(discapacidad=='SI')
    {
        var carnet=$("#carnet").val();
        if(carnet==''||carnet==null)
        {
            alert("Debe llenar el campo de carnet si tiene una discapacidad");
            b=1;
        }
    }
    if(b==0)
    {
        document.getElementById("btnvg").click()

    }

});
$('#verif').on('click', function (e) {
		var dato = $('input[name^="identificacion"]').val();
		var tipo=$('[name^="tipo_identificacion"]').val();
    if(tipo!='cedula')
    {
        $("#dependencia").show();	

    }else{
        if(dato.length<10)
		{
		alert("Formato incorrecto de cedula , verifique el numero sea igual a 10 digitos")
		$("#dependencia").hide();	

		}else{
			verifica(dato); 
        }
    }
});
function validarEmail(dato) {
	var valor=$('#'+dato+'').val();
	switch(dato)
	{
		case 'correo':
		if(!validar_emailtoc( valor ) )
		{
			alert("Verifique el email ingresado ,debe ingresar formato de correo valido ");
			$('#'+dato+'').val('');

		}
		break;
		default:
		if(!validar_emailto( valor ) )
		{
			alert("Verifique el email ingresado ,debe ingresar formato de correo institucional ");
			$('#'+dato+'').val('');

		}
		break;
	}
	
  }

function validar_emailto( email ) 
{
    var regex = /^([a-zA-Z0-9_\.\-])+\@ug.edu.ec/;
    return regex.test(email) ? true : false;
}
function validar_emailtoc( email ) 
{
    var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email) ? true : false;
}
function verifica(dato) {

		var verifica = valida($('[name^="identificacion"]').val());
		if(verifica==5)
		{
			$("#dependencia").show();				
		}else{
			
			$("#dependencia").hide();	
		}
}
</script>
<script type="text/javascript" src="{{asset('js/jspdf.min.js')}}"></script>
@yield('javascript')
