<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    protected $fillable = [
        'usuario_id',
        'clave',
        'nombre',
        'descripcion',
        'portada',
        'concluido',
    ];
    protected $casts = ['concluido' => 'boolean'];
    public function profesor(): BelongsTo
    {
        return $this->belongsTo(User::class,'usuario_id');
    }
    public function retos(): HasMany
    {
        return $this->hasMany(Reto::class);
    }
    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'inscripciones', 'grupo_id', 'usuario_id')
            ->withPivot('puntos_obtenidos','id')
            ->withTimestamps();
    }
}
