<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    protected $table = 'times';

    public $timestamps = false;

    protected $fillable = [
        'nome',
        'ativo',
        'data_criacao'
    ];

    public function campeonatosTimes()
    {
        return $this->hasMany(CampeonatoTime::class, 'time_id');
    }
}
