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

    /**
     * Usuarios inscritos en el curso
     */
    public function inscritos()
    {
        return $this->belongsToMany(User::class, 'curso_user')
            ->withPivot('progreso', 'estado', 'inscrito_at', 'completado_at')
            ->withTimestamps()
            ->using(CursoUser::class);
    }

    /**
     * Contar usuarios inscritos
     */
    public function totalInscritos()
    {
        return $this->inscritos()->count();
    }

    /**
     * Scope para cursos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * Scope para buscar cursos
     */
    public function scopeBuscar($query, $search)
    {
        return $query->where('titulo', 'like', "%{$search}%")
            ->orWhere('descripcion', 'like', "%{$search}%");
    }

    /**
     * Scope para filtrar por categoría
     */
    public function scopeCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    /**
     * Módulos del curso
     */
    public function modulos()
    {
        return $this->hasMany(Modulo::class)->orderBy('orden');
    }

    /**
     * Módulos publicados
     */
    public function modulosPublicados()
    {
        return $this->hasMany(Modulo::class)->where('publicado', true)->orderBy('orden');
    }

    /**
     * Total de lecciones del curso
     */
    public function totalLecciones()
    {
        return Leccion::whereIn('modulo_id', $this->modulos()->pluck('id'))->count();
    }

    /**
     * Duración total del curso en minutos
     */
    public function duracionTotal()
    {
        return Leccion::whereIn('modulo_id', $this->modulos()->pluck('id'))->sum('duracion_minutos');
    }
}
