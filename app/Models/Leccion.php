<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leccion extends Model
{
    protected $table = 'lecciones';

    protected $fillable = [
        'modulo_id',
        'titulo',
        'contenido',
        'tipo',
        'url_recurso',
        'duracion_minutos',
        'orden',
        'es_gratis',
        'publicado'
    ];

    protected $casts = [
        'es_gratis' => 'boolean',
        'publicado' => 'boolean',
    ];

    /**
     * Relación con el módulo
     */
    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    /**
     * Usuarios que completaron esta lección
     */
    public function usuariosCompletados()
    {
        return $this->belongsToMany(User::class, 'leccion_user')
            ->withPivot('completado_at', 'tiempo_visto')
            ->withTimestamps();
    }

    /**
     * Verificar si un usuario completó esta lección
     */
    public function estaCompletadaPor($userId)
    {
        return $this->usuariosCompletados()->where('user_id', $userId)->exists();
    }

    /**
     * Scope para lecciones publicadas
     */
    public function scopePublicadas($query)
    {
        return $query->where('publicado', true)->orderBy('orden');
    }

    /**
     * Scope para lecciones gratuitas
     */
    public function scopeGratuitas($query)
    {
        return $query->where('es_gratis', true);
    }

    /**
     * Obtener el curso a través del módulo
     */
    public function curso()
    {
        return $this->modulo->curso;
    }
}
