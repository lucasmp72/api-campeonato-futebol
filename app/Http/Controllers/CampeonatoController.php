<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campeonato;

class CampeonatoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $campeonatos = Campeonato::all()->where("ativo", true);
        
        return $campeonatos;
    }

    /**
     * Store a newly created resource in storage.
     */
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

        return response()->json($campeonato, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Campeonato::where("id", "=", $id)->where("ativo", true)->get();
    }

    /**
     * Update the specified resource in storage.
     */
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

        return response()->json($campeonato);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $campeonato = Campeonato::where('id', '=', $id)->where('ativo', true)->firstOrFail();
        $campeonato->ativo = false;
        $campeonato->update($campeonato->only(['ativo']));

        return response()->json(['message' => 'Time removido com sucesso']);
    }
}
