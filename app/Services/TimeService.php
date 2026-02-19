<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Time;

class TimeService
{
    public function index()
    {
        $times = Time::where("ativo", true)->get();
        
        return $times;
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255'
        ]);

        $time = Time::create([
            'nome' => $request->nome,
            'data_criacao' => now()
        ]);

        return $time;
    }

    public function show(string $id)
    {
        return Time::where("id", "=", $id)->where("ativo", true)->get();
    }

    public function update(Request $request, string $id)
    {
        $time = Time::findOrFail($id);

        $request->validate([
            'nome' => 'string|max:255'
        ]);

        $time->update($request->only(['nome']));

        return $time;
    }

    public function destroy(string $id)
    {
        $time = Time::where('id', '=', $id)->where('ativo', true)->firstOrFail();
        $time->ativo = false;
        $time->update($time->only(['ativo']));
    }
}