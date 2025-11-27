<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CursoUser extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'curso_user';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'inscrito_at' => 'datetime',
            'completado_at' => 'datetime',
            'progreso' => 'integer',
        ];
    }
}
