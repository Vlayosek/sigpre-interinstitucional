@extends('layouts.app')

@section('template_title')
    Libro
@endsection
@section('contentheader_title')
 Modulo 
@endsection

@section('contentheader_description')
Libro
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Libro') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('libros.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Nuevo') }} {{ __('Libro') }}
                                </a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success errores">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover dt-select dataTable" id="dtmenu">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Nombre</th>
										<th>Apellidos</th>
										<th>Cedula</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($libros as $libro)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $libro->nombre }}</td>
											<td>{{ $libro->apellidos }}</td>
											<td>{{ $libro->cedula }}</td>

                                            <td>
                                                <form action="{{ route('libros.destroy',$libro->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('libros.show',$libro->id) }}"><i class="fa fa-fw fa-eye"></i> Ver</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('libros.edit',$libro->id) }}"><i class="fa fa-fw fa-edit"></i> Editar</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-fw fa-trash"></i> Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $libros->links() !!}
            </div>
        </div>
    </div>
    @section('javascript') 
    <script>
        $("#dtmenu").dataTable({
                destroy: true,
                responsive:true,
                dom: 'lBfrtip',
                "lengthMenu": [[10,20,30,-1], [10,20,30,"TODOS"]],
                buttons: [
                  {
                      extend:    'excelHtml5',
                      text:      '<img src="/images/icons/excel.png" width="15px" heigh="10px">Descargar',
                      titleAttr: 'Excel'
                  },
                 
              ],
              "language": {
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
                        "infoFiltered": "(Filtrado _TOTAL_  de _MAX_ registros totales)",
                    },

      });
     var delayInMilliseconds = 3000; //1 second
     setTimeout(function() {
         $(".errores").addClass("hidden");
     }, delayInMilliseconds);
    </script>
    @endsection
@endsection
