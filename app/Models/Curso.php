<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $fillable = ['titulo', 'descripcion', 'categoria', 'user_id', 'estado'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el usuario que creó el curso
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
