<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campeonato extends Model
{
    protected $table = 'campeonatos';

    public $timestamps = false; // porque você NÃO tem created_at e updated_at

    protected $fillable = [
        'nome',
        'ida_volta',
        'gols_fora',
        'pontos_corridos',
        'data_criacao',
        'ativo',
        'finalizado'
    ];
}
