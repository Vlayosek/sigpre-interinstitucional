<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DemoTask;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class PrivateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function privadoCarpeta($carpeta, $archivo)
    {
        $path = $carpeta . '/' . $archivo;
        ob_end_clean();
        if (Storage::disk('storage')->exists($path)) {
            return Storage::disk('storage')->download($path);
        }
        abort(404);
    }
    public function privadoCarpeta_dos_niveles($carpeta1, $carpeta2, $archivo)
    {
        $path = $carpeta1 . '/' . $carpeta2 . '/' . $archivo;
        ob_end_clean();
        if (Storage::disk('storage')->exists($path)) {
            //   return $this->descargarArchivo($archivo,$path);
            return Storage::disk('storage')->download($path);
        }
        abort(404);
    }
    public function privadoCarpeta_tres_niveles($carpeta1, $carpeta2, $carpeta3, $archivo)
    {
        $path = $carpeta1 . '/' . $carpeta2 . '/' . $carpeta3 . '/' . $archivo;
        ob_end_clean();
        if (Storage::disk('storage')->exists($path)) {
            return Storage::disk('storage')->download($path);
        }
        abort(404);
    }


    public function privadoArchivo($archivo)
    {
        ob_end_clean();
        if (Storage::disk('storage')->exists($archivo)) {
            return Storage::disk('storage')->download($archivo);
        }
        abort(404);
    }
    protected function descargarArchivo($archivo, $path)
    {
        $extension = explode(".", $archivo)[1];
        $path = storage_path('app') . '/' . $path;
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $path);
        header("Content-type:" . $mimeType);
        header("Content-Disposition: attachment; filename=SIGPRE." . $extension . "");
        return file_get_contents($path);
    }
}
