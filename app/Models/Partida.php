<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    protected $table = 'partidas';

    public $timestamps = false;

    protected $fillable = [
        'campeonato_id',
        'time_casa_id',
        'gols_casa',
        'penaltis_casa',
        'time_visitante_id',
        'gols_visitante',
        'pentaltis_visitante',
        'data_criacao',
        'ativo',
        'fase_id'
    ];
}
