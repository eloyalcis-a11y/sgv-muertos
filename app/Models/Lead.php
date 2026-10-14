<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'nombre', 'telefono', 'email', 'terminos', 'marketing',
        'apodo', 'facetas', 'foto', 'completado_at',
    ];

    protected function casts(): array
    {
        return [
            'terminos' => 'boolean',
            'marketing' => 'boolean',
            'facetas' => 'array',
            'completado_at' => 'datetime',
        ];
    }
}
