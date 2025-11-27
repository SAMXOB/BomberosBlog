<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role() {
    return $this->belongsTo(Role::class);
    }

    /**
     * Cursos en los que está inscrito el usuario
     */
    public function cursosInscritos()
    {
        return $this->belongsToMany(Curso::class, 'curso_user')
            ->withPivot('progreso', 'estado', 'inscrito_at', 'completado_at')
            ->withTimestamps()
            ->using(CursoUser::class);
    }

    /**
     * Cursos creados por el usuario
     */
    public function cursosCreados()
    {
        return $this->hasMany(Curso::class);
    }

    /**
     * Verificar si está inscrito en un curso
     */
    public function estaInscritoEn($cursoId)
    {
        return $this->cursosInscritos()->where('curso_id', $cursoId)->exists();
    }

    /**
     * Lecciones completadas por el usuario
     */
    public function leccionesCompletadas()
    {
        return $this->belongsToMany(Leccion::class, 'leccion_user')
            ->withPivot('completado_at', 'tiempo_visto')
            ->withTimestamps();
    }

    /**
     * Verificar si completó una lección
     */
    public function completoLeccion($leccionId)
    {
        return $this->leccionesCompletadas()->where('leccion_id', $leccionId)->exists();
    }

    /**
     * Calcular progreso en un curso específico
     */
    public function calcularProgresoCurso($cursoId)
    {
        $curso = Curso::find($cursoId);
        if (!$curso) return 0;

        $totalLecciones = $curso->totalLecciones();
        if ($totalLecciones == 0) return 0;

        $leccionesCompletadas = $this->leccionesCompletadas()
            ->whereIn('leccion_id', function($query) use ($cursoId) {
                $query->select('lecciones.id')
                    ->from('lecciones')
                    ->join('modulos', 'lecciones.modulo_id', '=', 'modulos.id')
                    ->where('modulos.curso_id', $cursoId);
            })
            ->count();

        return round(($leccionesCompletadas / $totalLecciones) * 100);
    }

}
