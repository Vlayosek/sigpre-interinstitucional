var map, simpleMapScreenshoter, tiles, heatmapLayer, popup, marker, mapLayer, circle, arrPuntos = [],
    zoomMap;

function seteaTiles() {
    tiles = L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Mapbox',
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1,
            maxZoom: 18
        }
    );
    // agregando el identificador...
    tiles.addTo(map);

    simpleMapScreenshoter = L.simpleMapScreenshoter({
        hidden: true, // hide screen btn on map
    }).addTo(map)
}

function iniciaMapa() {
    // verfica si ya hay una instancia... blanquea el contenedor...
    if (map) map.remove();
    // latitud longitud Ecuador...
    map = L.map('heatmap').setView([-1.831239, -78.183406], 7);

    simpleMapScreenshoter = L.simpleMapScreenshoter({
        hidden: true, // hide screen btn on map
    }).addTo(map);
    /*
    // zoom para el mapa...
    zoomMap = {
      start:  map.getZoom(),
      end: map.getZoom()
    };
    */
}

function retornaRotuloCirculo(result) {
    // desestructura el objeto...
    const { nombre_compromiso, ubicacion, avances } = result;
    // realizamos la consulta sobre los compromisos a obtener...
    // retorna...
    return `
    Ubicación: ${ubicacion};
    Compromiso: ${nombre_compromiso};
    Avance: ${avances}%`
}

function regresaRandom(max, min, dec) {
    // randomico...
    return parseFloat(((Math.random() * (max - min) + min) * -1).toFixed(dec));
    // return parseFloat((Math.random() * -1).toFixed(dec));
}

function retornaLongitud(result) {
    // desestructura propiedades...
    let { latitud, longitud } = result;
    let latRan = regresaRandom(0, 0.2, 5);
    let lonRan = regresaRandom(0, 0.3, 5);
    // agrega al panel...
    latitud = (parseFloat(latitud) + latRan);
    longitud = (parseFloat(longitud) + lonRan);
    // retorna la longitud + 0.10000 para no sobreponer...
    return [latitud, longitud];
}

function verificaTipoUbicacion(ubicacion) {
    // solo grafica si no es exterior...
    if (ubicacion.toLowerCase() === "exterior" || ubicacion.toLowerCase() === "nacional") return true;
    // es una ubicacion exacta...
    return false;
}

function mofificaRadioCompromiso(results) {
    let arrResultados = [];
    // recorre el arreglo
    for (let result of results) {
        let data = {
            radio: 0,
            ubicacion: ''
        };
        // ubicacion...
        const { ubicacion } = result;
        // solo grafica si no es exterior...
        if (verificaTipoUbicacion(ubicacion)) return;
    }
}

function agregaDatosMapa(results) {
    // reinicia mapa...
    iniciaMapaDeCalor();
    // recorremos el arreglo de datos...
    for (let result of results) {
        // ubicacion...
        const { ubicacion, color } = result;
        // solo grafica si no es exterior...
        if (verificaTipoUbicacion(ubicacion)) return;
        // agregando el circulo...
        let circleData = L.circle(retornaLongitud(result), 15000, {
                color,
                fillColor: color,
                fillOpacity: 0.5,
            })
            .addTo(map);
        // agregnado la lista de circulos...
        arrPuntos.push(
            circleData
            .bindPopup(retornaRotuloCirculo(result).toString())
        );
        // zom por circulo...
        // setEventosZoom(circleData);
    }

}

function setEventosZoom(circle) {
    // zoom por ubicacion de ciruclos...
    map.on('zoomend', function(e) {
        zoomMap.end = map.getZoom();
        let diff = myZoom.start - myZoom.end;
        if (diff > 0) {
            circle.setRadius(circle.getRadius() * 2);
        } else if (diff < 0) {
            circle.setRadius(circle.getRadius() / 2);
        }
    });
}

function iniciaEventosZoom() {
    map.on('zoomstart', function(e) {
        zoomMap.start = map.getZoom();
    });
}

function iniciaMapaDeCalor() {
    // inicia mapa...
    iniciaMapa();
    // setea el titulo...
    seteaTiles();
    // eventos zoom...
    // iniciaEventosZoom();
}