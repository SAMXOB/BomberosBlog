<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $fillable = [
        'curso_id',
        'titulo',
        'descripcion',
        'orden',
        'publicado'
    ];

    protected $casts = [
        'publicado' => 'boolean',
    ];

    /**
     * Relación con el curso
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    /**
     * Relación con lecciones
     */
    public function lecciones()
    {
        return $this->hasMany(Leccion::class)->orderBy('orden');
    }

    /**
     * Lecciones publicadas
     */
    public function leccionesPublicadas()
    {
        return $this->hasMany(Leccion::class)->where('publicado', true)->orderBy('orden');
    }

    /**
     * Contar total de lecciones
     */
    public function totalLecciones()
    {
        return $this->lecciones()->count();
    }

    /**
     * Scope para módulos publicados
     */
    public function scopePublicados($query)
    {
        return $query->where('publicado', true)->orderBy('orden');
    }

    /**
     * Obtener duración total del módulo
     */
    public function duracionTotal()
    {
        return $this->lecciones()->sum('duracion_minutos');
    }
}
