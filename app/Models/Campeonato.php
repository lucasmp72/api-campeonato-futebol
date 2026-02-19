<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campeonato extends Model
{
    protected $table = 'campeonatos';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'ida_volta',
        'gols_fora',
        'pontos_corridos',
        'data_criacao',
        'ativo',
        'finalizado'
    ];

    public function campeonatosTimes()
    {
        return $this->hasMany(CampeonatoTime::class, 'campeonato_id');
    }
}
