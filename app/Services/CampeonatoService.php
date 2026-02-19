<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Campeonato;

class CampeonatoService
{
    public function index()
    {
        $campeonatos = Campeonato::where("ativo", true)->get();
        
        return $campeonatos;
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'ida_volta' => 'boolean',
            'gols_fora' => 'boolean',
            'pontos_corridos' => 'boolean'
        ]);

        $campeonato = Campeonato::create([
            'nome' => $request->nome,
            'ida_volta' => $request->idaVolta ?? false,
            'gols_fora' => $request->golsFora ?? false,
            'pontos_corridos' => $request->pontosCorridos ?? false,
            'data_criacao' => now()
        ]);

        return $campeonato;
    }

    public function show(string $id)
    {
        return Campeonato::where("id", "=", $id)->where("ativo", true)->get();
    }

    public function update(Request $request, string $id)
    {
        $campeonato = Campeonato::findOrFail($id);

        $request->validate([
            'nome' => 'string|max:255',
            'ida_volta' => 'boolean',
            'gols_fora' => 'boolean',
            'pontos_corridos' => 'boolean'
        ]);

        $campeonato->update($request->only(['nome']));

        return $campeonato;
    }

    public function destroy(string $id)
    {
        $campeonato = Campeonato::where('id', '=', $id)->where('ativo', true)->firstOrFail();
        $campeonato->ativo = false;
        $campeonato->update($campeonato->only(['ativo']));
    }

    public function calculaCampeonato(string $id)
    {
        return 'teste';
    }
}