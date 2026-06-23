<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';
    
    protected $fillable = [
        'nombre',
        'contacto',
        'direccion',
        'telefono',
        'email',
        'informacion_adicional'
    ];

    const CREATED_AT = 'fecha_creacion';
    public $timestamps = false;

    public function productos()
    {
        return $this->hasMany(Producto::class, 'id_proveedor');
    }
}
