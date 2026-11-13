<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Estacionamiento extends Model
{
    protected $table = 'estacionamientos';

    protected $fillable = [
        'vehiculo_id',
        'espacio_id',
        'numero_ticket',
        'fecha_entrada',
        'fecha_salida',
        'total',
    ];

    protected $casts = [
        'fecha_entrada' => 'datetime',
        'fecha_salida' => 'datetime',
        'total' => 'decimal:2',
    ];

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class);
    }

    public function espacio(): BelongsTo
    {
        return $this->belongsTo(Espacio::class);
    }
    public function pago()
{
    return $this->hasOne(Pago::class);
}
}