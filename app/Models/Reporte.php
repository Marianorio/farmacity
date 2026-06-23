<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    protected $table = 'reportes';

    protected $fillable = [
        'titulo',
        'tipo',
        'fecha_inicio',
        'fecha_fin',
        'datos',
        'archivo',
        'usuario_id',
        'observaciones'
    ];

    protected $casts = [
        'datos' => 'array',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}