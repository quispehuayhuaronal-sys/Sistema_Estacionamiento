<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Espacio extends Model
{
    protected $table = 'espacios';

    protected $fillable = [
        'codigo',
        'piso',
        'sector',
        'ocupado',
        'activo',
    ];

    protected $casts = [
        'ocupado' => 'boolean',
        'activo' => 'boolean',
    ];
}