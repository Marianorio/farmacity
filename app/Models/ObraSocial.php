<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ObraSocial extends Model
{
    protected $table = 'obras_sociales';
    
    protected $fillable = [
        'nombre',
        'cuit',
        'fecha_convenio',
        'fecha_vencimiento_convenio',
        'codigo_validacion'
    ];

    protected $dates = [
        'fecha_convenio',
        'fecha_vencimiento_convenio'
    ];

    // Corregir la relación con productos
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'productos_obras_sociales', 'id_obra_social', 'id_producto')
                    ->withPivot('descuento');
    }
}