<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampeonatoTime extends Model
{
        protected $table = 'campeonatos_times';

    public $timestamps = false;

    protected $fillable = [
        'campeonato_id',
        'time_id',
        'data_criacao'
    ];

    // Relacionamentos
    public function campeonato()
    {
        return $this->belongsTo(Campeonato::class, 'campeonato_id');
    }

    public function time()
    {
        return $this->belongsTo(Time::class, 'time_id');
    }
}
