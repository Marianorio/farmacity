<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    const ESTADO_ACTIVA = 'ACTIVA';
    const ESTADO_ANULADA = 'ANULADA';

    protected $fillable = [
        'numero_cliente',
        'fecha',
        'subtotal',
        'impuestos',
        'descuento',
        'total',
        'id_usuario',
        'id_usuario_anulacion',
        'id_obra_social',
        'codigo_validacion',
        'estado',
        'motivo_anulacion',
        'fecha_anulacion'
    ];

    // Convertir estos campos a fechas automáticamente
    protected $dates = [
        'fecha',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    // Relación con detalles de venta
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta');
    }

    // Relación con obra social
    public function obraSocial()
    {
        return $this->belongsTo(ObraSocial::class, 'id_obra_social');
    }

    // Relación con el usuario que creó la venta
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    // Relación con el usuario que anuló la venta (puede ser null)
    public function usuarioAnulacion()
    {
        return $this->belongsTo(User::class, 'id_usuario_anulacion');
    }

    public function getNumeroVentaAttribute()
    {
        return str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    // Método para verificar si la venta está anulada
    public function isAnulada()
    {
        return $this->estado === self::ESTADO_ANULADA;
    }
}
