<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    protected $table = 'times';

    public $timestamps = false; // porque você NÃO tem created_at e updated_at

    protected $fillable = [
        'nome',
        'ativo',
        'data_criacao'
    ];
}
