<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tarea;
use App\Models\Usuario;
use App\Exports\TareasPendientesExport;
use Maatwebsite\Excel\Facades\Excel;

class TareaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:pendiente,completada',
            'fecha_vencimiento' => 'nullable|date',
            'usuario_id' => 'required|exists:usuarios,id',
        ]);
        
        // Mapear usuario_id a user_id para el modelo
        $tareaData = $validated;
        $tareaData['user_id'] = $validated['usuario_id'];
        unset($tareaData['usuario_id']);
        
        $tarea = Tarea::create($tareaData);
        return response()->json($tarea, 201);
    }

    public function index()
    {
        $tareas = Tarea::with('usuario:id,nombre')->get();
        return response()->json($tareas);
    }

    public function descargarPendientes()
{
    return Excel::download(new TareasPendientesExport, 'tareas_pendientes.xlsx');
}

}
