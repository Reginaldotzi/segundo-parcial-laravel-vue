<?php

namespace App\Exports;

use App\Models\Tarea;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class TareasPendientesExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        $tareas = Tarea::where('estado', 'pendiente')->with('usuario')->get();

        return $tareas->map(function ($tarea) {
            return [
                $tarea->titulo,
                $tarea->usuario ? $tarea->usuario->nombre : 'Sin asignar',
                $tarea->fecha_vencimiento ? $tarea->fecha_vencimiento : 'Sin fecha',
            ];
        });
    }

    public function headings(): array
    {
        return ['Título', 'Usuario asignado', 'Fecha de vencimiento'];
    }
}