<?php

namespace App\Http\Controllers\AdministracionGrafico;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\Entities\AdministracionGrafico\Catalogo;
use App\Core\Entities\AdministracionGrafico\Grafico;
use Auth;
use Yajra\Datatables\Datatables;
use Yajra\DataTables\CollectionDataTable;
use DB;

class AdministracionGraficoController extends Controller
{
    public function index()
    {
        return view('modules.administracion_grafico.index');
    }
    public function verPlantilla(Request $request)
    {
        $array_response['status'] = 200;
        $cql = Catalogo::select('imagen_archivo')->where('descripcion', $request->tipo)->first();
        $array_response['datos'] = !is_null($cql) ? $cql->imagen_archivo : null;
        return response()->json($array_response, 200);
    }
    public function cambiarPrincipal(Request $request)
    {
        try {
            DB::connection('pgsql_presidencia')->beginTransaction();
            $cql = Grafico::where('id', $request->id)->first();
            if ($cql->eliminado) {
                Grafico::where('tipo', $cql->tipo)->update(['eliminado' => true, 'fecha_modifica' => date('Y-m-d H:i:s'), 'usuario_modifica' => Auth::user()->name]);

                $cql->fecha_modifica = date('Y-m-d H:i:s');
                $cql->usuario_modifica = Auth::user()->name;
                $cql->eliminado = false;
                $cql->save();
            } else {
                $cql->fecha_modifica = date('Y-m-d H:i:s');
                $cql->usuario_modifica = Auth::user()->name;
                $cql->eliminado = true;
                $cql->save();
            }

            DB::connection('pgsql_presidencia')->commit();
            $array_response['status'] = 200;
            $array_response['datos'] = "Grabado Exitoso";
        } catch (\Exception $e) {
            DB::connection('pgsql_presidencia')->rollBack();
            $array_response['status'] = 404;
            $array_response['datos'] = 'Error en la transaccion';
        }

        return response()->json($array_response, 200);
    }

    public function guardarAdministracionGraficaLogin(Request $request)
    {
        try {
            DB::connection('pgsql_presidencia')->beginTransaction();

            $image = base64_encode(file_get_contents($request->file('imagen')));
            $cql = Grafico::where('imagen', $image)->where('tipo', $request->tipo)->first();
            if (!is_null($cql))
                throw new \Exception("Ya se encuentra agregada la imagen, es el registro #" . $cql->id);
            $cql = new Grafico();
            $cql->imagen = $image;
            $cql->tipo = $request->tipo;
            $cql->fecha_inserta = date('Y-m-d H:i:s');
            $cql->usuario_inserta = Auth::user()->name;
            $cql->eliminado = true;
            $cql->save();

            DB::connection('pgsql_presidencia')->commit();
            $array_response['status'] = 200;
            $array_response['message'] = "Grabado Exitoso";
        } catch (\Exception $e) {
            DB::connection('pgsql_presidencia')->rollBack();
            $array_response['status'] = 404;
            $array_response['message'] = $e->getMessage();
        }


        return response()->json($array_response, 200);
    }
    public function getDatatableServerSide()
    {
        $data = Grafico::orderby('id', 'desc')->get();
        return (new CollectionDataTable($data))
            ->addIndexColumn()
            ->addColumn('', function ($row) {
                $boton = !$row->eliminado ? 'INACTIVAR' : 'ACTIVAR';
                return '<button class="btn btn-info btn-sm" onclick="app.cambiarPrincipal(\'' . $row->id . '\')">' . $boton . '</button>';
            })
            ->addColumn('imagen_', function ($row) {
                return 'data:image/png;base64,' . $row->imagen;
            })
            ->addColumn('eliminado_', function ($row) {
                return !$row->eliminado ? 'ACTIVO' : 'INACTIVO';
            })
            ->addColumn('id_', function ($row) {
                return 'REG-GRAFICO-' . $row->id;
            })
            ->rawColumns([''])
            ->toJson();
    }
}
