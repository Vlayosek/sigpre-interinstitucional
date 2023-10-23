function formarGraficoEstado(data, id) {
    // Year data in the foreground
    var result = Object.keys(data).map((key) => [data[key].estado_gestion, data[key].contador]);
    data = result;
    return Highcharts.chart(id, {
        chart: {
            type: 'column',
            name: '',
            style: {
                fontFamily: 'Roboto Condensed, sans-serif'
            },
        },
        title: {
            text: '-'
        },
        subtitle: {
            text: ''
        },
        plotOptions: {
            series: {
                grouping: false,
                borderWidth: 0
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            shared: true,
            backgroundColor: '#666',
            borderColor: '#666',
            borderRadius: 2,
            style: {
                color: '#fff',
                fontSize: '12px',
            },
            headerFormat: '<span style="font-size: 12px">{point.point.name}</span><br/>',
            pointFormat: ' <b>{point.y} <br/>'
        },
        xAxis: {
            type: 'category',
            labels: {
                useHTML: true,
                animate: true,
            }
        },
        yAxis: [{
            title: {
                text: ''
            },
        }],
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.1f}'
                }
            }
        },
        series: [{
            color: '#9e9fa3',
            pointPlacement: -0.2,
            linkedTo: 'main',

        }, {
            color: '#5fb6f2',
            dataSorting: {
                enabled: true,
                matchByName: true
            },

            data: data
        }],
        credits: {
            enabled: false
        }
    });
}

function formarGrafico_tc(titulo, porcentaje1, porcentaje2, porcentaje3) {
    total = porcentaje1 + porcentaje2 + porcentaje3;
    porcentaje1 = (porcentaje1 * 100) / total;
    porcentaje2 = (porcentaje2 * 100) / total;
    porcentaje3 = (porcentaje3 * 100) / total;
    Highcharts.setOptions({
        colors: ['#01BAF2', '#CCC', '#FAA74B']
    });
    let imagen = Highcharts.chart('containerAmbito', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: ''
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true
                },
                showInLegend: true
            },
            series: {
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y:.1f}%'
                }
            }

        },
        series: [{
            name: 'Composition',
            colorByPoint: true,
            data: [{
                    name: 'NACIONAL',
                    y: porcentaje1,
                    sliced: true,
                    selected: true
                },
                {
                    name: 'PROVINCIA',
                    y: porcentaje2,
                },
                {
                    name: 'CANTONAL',
                    y: porcentaje3,
                },
            ]
        }]
    });

}

function exportChart() {
    var svg = canvg(document.getElementById('canvas_grafico_ambito'), getSVG(), {
        //ignoreDimensions: true
    });
    return "data:image/svg+xml," + svg;
}

function getSVG() {
    var chart = $('#containerAmbito').highcharts();
    var svg = chart.getSVG();
    return svg;
}