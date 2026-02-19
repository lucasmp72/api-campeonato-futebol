<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\CampeonatoTime;

class CampeonatoTimeService
{
    public function index()
    {
        return CampeonatoTime::with(['campeonato', 'time'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'campeonato_id' => 'required|exists:campeonatos,id',
            'time_id' => 'required|exists:times,id',
            'ativo' => 'boolean'
        ]);

        $existeRegistro = CampeonatoTime::where('campeonato_id', $request->campeonato_id)
                                        ->where('time_id', $request->time_id)
                                        ->exists();

        if($existeRegistro)
        {
            throw ValidationException::withMessages([
                    'time_id' => 'Este time já está vinculado a este campeonato.'
                ]);
        }

        $count = CampeonatoTime::where('campeonato_id', $request->campeonato_id)
                               ->count();

        if($count >= 8)
        {
            throw ValidationException::withMessages([
                    'campeonato_id' => 'Este campeonato já possui o limite máximo de 8 times.'
            ]);
        }

        $registro = CampeonatoTime::create([
            'campeonato_id' => $request->campeonato_id,
            'time_id' => $request->time_id,
            'ativo' => $request->ativo ?? true,
            'data_criacao' => now()
        ]);

        return $registro;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return CampeonatoTime::with(['campeonato', 'time'])
            ->findOrFail($id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $registro = CampeonatoTime::findOrFail($id);
        $registro->delete();
    }
}