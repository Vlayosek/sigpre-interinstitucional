const {
  host, hostname, href, origin, pathname, port, protocol, search
} = window.location

const error = function ( e, settings, techNote, message ) { 
  // * termino de sesión...
  if(parseInt(techNote) === 7) window.location.replace(origin);
  console.log( 'An error has been reported by DataTables: ', message);
};

const language = {
  "search": "Buscar",
  "lengthMenu": "Mostrar _MENU_",
  "zeroRecords": "Lo sentimos, no encontramos lo que estas buscando",
  "info": "Motrar página _PAGE_ de _PAGES_ (_TOTAL_)",
  "infoEmpty": "Registros no encontrados",
  "oPaginate": {
    "sFirst": "Primero",
    "sLast": "Último",
    "sNext": "Siguiente",
    "sPrevious": "Anterior"
  },
  "infoFiltered": "(Filtrado _TOTAL_  de _MAX_ registros totales)"
};
const dom = 'lBfrtip';
const destroy = true;
const responsive = true;
const processing = true;

const actualizaDataTable = function(oTable) {
  // * objeto datatable...
  oTable.ajax.reload(null, false);
}

const eventosFijosDataTable = function(oTable, callBack = null) {
  oTable
  .on('click', 'td.dt-control', callBack)
  .on('error.dt', function ( e, settings, techNote, message ) {
    error( e, settings, techNote, message );
  });
}

const objDataTable = {
  idTable: '', 
  ajax: {}, 
  columns: [], 
  buttons: [],
  initComplete: null
};

const retornaDataTable = function (objDataTable) {
  // * desestructura el objeto de parametro...
  const { ajax, buttons, columns, idTable, initComplete } = objDataTable;
  // * retorna DT...
  return $(idTable).DataTable({
    dom,
    language,
    destroy,
    processing,
    responsive,
    ajax,
    columns,
    buttons,
    initComplete
  });
};

export {
  dom,
  language,
  destroy,
  responsive,
  processing,
  objDataTable,
  actualizaDataTable,
  eventosFijosDataTable,
  error,
  retornaDataTable
}

 