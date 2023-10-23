@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')
@section('contentheader_title')
    Administrador
@endsection

@section('contentheader_description')
    Usuarios y Roles
@endsection
@section('content')

    <div class="card">
        <div class="card-heading">
            <div style="row">
                <div class="col btnTop">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary" id="crearUsuarios"><i
                            class="fa fa-plus"></i>&nbsp;Nuevo Usuario</a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <br />
            <div class="table-responsive">
                <table class="table table-striped {{ count($users) > 0 ? 'datatable' : '' }} dt-select" id="dtUser">
                    <thead>
                        <tr>
                            <th>Instituci&oacute;n</th>
                            <th>Identificacion</th>
                            <th>Nombres</th>
                            <th>Usuario</th>
                            <th>Correo</th>
                            <th>@lang('global.users.fields.roles')</th>
                            <th>Estado</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($users) > 0)
                            @foreach ($users as $user)
                                <tr data-entry-id="{{ $user->id }}">
                                    <td width="7%">{!! $user->institucion != null ? $user->institucion->nombre : '--' !!}</td>
                                    <td width="7%">{{ $user->identificacion }}</td>
                                    <td width="7%">{{ $user->nombres }}</td>
                                    <td width="7%">{{ $user->name }}</td>
                                    <td width="7%">{{ $user->email }}</td>
                                    <td width="25%" style="text-align:justify">
                                        @foreach ($user->roles()->pluck('name') as $role)
                                            <span class="label label-info label-many">{!! strtoupper($role) . ',' !!}</span>
                                        @endforeach
                                    </td>
                                    <td width="5%">
                                        @if ($user->estado == 'A')
                                            <span class="label label-primary label-many">Activo</span>
                                        @endif
                                        @if ($user->estado == 'I')
                                            <span class="label label-danger label-many">Inactivo</span>
                                        @endif
                                    </td>
                                    <td width="15%">
                                        <table width="100%">
                                            <tr>
                                                <td style="padding:1px"> <a href="{{ route('admin.users.edit', [$user->id]) }}"
                                                        class="btn btn-xs btn-info btn-block">
                                                        <i class="fa fa-edit"></i>
                                                    </a></td>
                                                <td style="padding:1px"> {!! Form::open([
                                                            'style' => 'display: inline-block;',
                                                            'method' => 'DELETE',
                                                            'onsubmit' => "return confirm('" . trans('global.app_are_you_sure') . "');",
                                                            'route' => ['admin.users.destroy', $user->id],
                                                        ]) !!}
                                                    <button type="submit" class="btn btn-xs btn-danger hidden"><i
                                                            class="fa fa-trash"></i></button>
                                                </td>
                                                <td style="padding:1px">
                                                    {!! Form::close() !!}
                                                    <a href="{{ route('admin.userstate', [$user->id]) }}"
                                                        class="btn btn-xs btn-warning btn-block">
                                                        <i class="fa fa-sync"></i>
                                                    </a>
                                                </td>
                                                <td style="padding:1px">
                                                    <a href="{{ route('impersonate', $user->id) }}"
                                                        class="btn btn-xs btn-default btn-block">
                                                        Impersonate
                                                    </a>
                                                </td>
                                                @if (!$user->token_expire == true)
                                                    <td style="padding:1px"> <a href="{{ route('admin.token_expire.2fa', $user->id) }}"
                                                            class="btn btn-xs btn-danger btn-block" style="font-size:9px">
                                                            SIN TOKEN
                                                        </a></td>
                                                    @else
                                                    <td style="padding:1px"> <a href="{{ route('admin.token_expire.2fa', $user->id) }}"
                                                            class="btn btn-xs btn-info btn-block" style="font-size:9px">
                                                            CON TOKEN
                                                        </a></td>
                                                   
                                                @endif
                                                @if ($user->valida_qr==true)
                                                        <td style="padding:1px"><a href="{{ route('admin.restaurar.2fa', $user->id) }}"
                                                            class="btn btn-xs btn-warning btn-block" style="font-size:9px">
                                                            Restaurar 2fa
                                                        </a></td>
                                                  
                                                    @else
                                                    <td style="padding:2px" style="font-size:10px"> S/I TOKEN</td>
                                                @endif
                                            </tr>
                                        </table>

                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9">@lang('global.app_no_entries_in_table')</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $("#dtUser").dataTable({
            destroy: true,
            responsive: true,
            stateSave: true,
            dom: 'lBfrtip',
            "lengthMenu": [
                [10, 20, 30, -1],
                [10, 20, 30, "TODOS"]
            ],
            buttons: [{
                    extend: 'excelHtml5',
                    text: '<img src="/images/icons/excel.png" width="15px" heigh="10px">Descargar',
                    titleAttr: 'Excel'
                },

            ]

        });
        $(".js-delete-selected").addClass("hidden");
        @if (isset($m))
            alert('{{ $m }}');
        @endif
        window.route_mass_crud_entries_destroy = '{{ route('admin.users.mass_destroy') }}';
    </script>
@endsection
