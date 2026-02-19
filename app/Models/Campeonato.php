<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campeonato extends Model
{
    protected $table = 'campeonatos';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'data_criacao',
        'ativo',
    ];

    public function campeonatosTimes()
    {
        return $this->hasMany(CampeonatoTime::class, 'campeonato_id');
    }
}
