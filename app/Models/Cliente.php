<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    // Especificamos la tabla si el nombre es diferente al plural del modelo
    protected $table = 'clientes';

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'nombre',
        'dni',
    ];

    // Por defecto, Laravel espera created_at y updated_at
    // Si tu tabla no los tiene, desactívalos con:
    public $timestamps = false;

    // Relación con ventas: un cliente puede tener muchas ventas
    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    // Método de ayuda para mostrar el nombre completo
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} - {$this->dni}";
    }

    // Método para buscar clientes por DNI
    public static function buscarPorDNI($dni)
    {
        return self::where('dni', $dni)->first();
    }
}
