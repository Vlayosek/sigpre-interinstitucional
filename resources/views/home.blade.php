@extends('layouts.app')
@section('contentheader_title')
    SIGPRE
@endsection
@section('contentheader_description')
    Bienvenido
@endsection
@section('javascript')
@endsection
@section('css')
    <style>
        .btn-app {
            border-radius: 3px;
            position: relative;
            padding: 15px 5px;
            margin: 0 0 10px 10px;
            min-width: 100%;
            height: 130px;
            text-align: center;
            color: #666;
            border: 1px solid #ddd;
            font-size: 19px;
        }
    </style>
@endsection
@section('content')
    <br />
    <div class="card ">
        <div class="card-body">
            @if ($contadorEncuesta == 0)
                <h4 class="text-center" id="encuestaUsuariosGeneral">
                    {!! $descripcion_encuesta !!}
                    <br />
                    @if (!$habilita_si_no)
                        <button class="btn btn-primary btnTop" onclick="appPerfil.grabarUsuarioEncuesta('SI')"> CLICK AQUI
                        </button>
                    @else
                        <button class="btn btn-primary btnTop" onclick="appPerfil.grabarUsuarioEncuesta('SI')"> SI </button>
                        <button class="btn btn-danger btnTop" onclick="appPerfil.grabarUsuarioEncuesta('NO')"> NO </button>
                    @endif
                </h4>
            @else
                <h6 class="text-center">
                    BIENVENIDO AL SIGPRE


                    <br />


                </h6>
                @if (!is_null(Auth::user()->clave_expira))
                    <h6 class="text-center"><strong>CLAVE EXPIRA : {!! Auth::user()->clave_expira !!}</strong></h6>
                @endif
            @endif
        </div>
    </div>
@endsection
