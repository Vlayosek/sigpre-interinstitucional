/*
let reloj_general = document.getElementById("hora_general");
function muestraReloj () {
        fetch(base_url+"/reloj_virtual/reloj.php")
        .then(response => response.text())
        .then(data => reloj_general.innerHTML = data.split(' ')[1])
}
setInterval(muestraReloj, 1000);

*/
var relojServidor = '';
var contadorInicialHora = 0;
let reloj_general = document.getElementById("hora_general");
var time = null;
var fecha_actual_servidor = null;
muestraReloj();
function reloj() {
        if (relojServidor != '') {
                time = new Date(relojServidor);
                if(contadorInicialHora>0)
                time.setSeconds(time.getSeconds() + 1);

                contadorInicialHora = contadorInicialHora + 1;
                if (contadorInicialHora == 1) {
                        fecha_actual_servidor = relojServidor.split(' ')[0]
                }
                
                horas = time.getHours();
                minutos = time.getMinutes();
                segundos = time.getSeconds();

                if (horas >= 12) {
                        porcentajeHoras = horas / 12 * 360;
                } else {
                        porcentajeHoras = horas / 24 * 360;
                }

                porcentajeHoras += minutos / 60 * 30;
                porcentajeMinutos = minutos / 60 * 360;
                porcentajeSegundos = segundos / 60 * 360;

                var hora_servidor = (horas > 9 ? horas : '0' + horas.toString()) + ":" + (minutos > 9 ? minutos : '0' + minutos.toString()) + ":" + (segundos > 9 ? segundos : '0' + segundos.toString());
                reloj_general.innerHTML = hora_servidor;
            
                relojServidor = fecha_actual_servidor + " " + hora_servidor;

        }

        // document.getElementById("p-content").innerHTML = horas + ":" + minutos + ":" + segundos; 
}

setInterval(reloj, 1000);
function muestraReloj() {
        fetch(base_url + "/reloj_virtual/reloj.php")
                .then(response => response.text())
                .then(data => relojServidor = data)
}
//setInterval(muestraReloj, 1000)