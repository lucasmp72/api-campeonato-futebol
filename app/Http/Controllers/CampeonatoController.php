<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CampeonatoService;

class CampeonatoController extends Controller
{
    private CampeonatoService $campeonatoService;

    public function __construct(CampeonatoService $campeonatoService)
    {
        $this->campeonatoService = $campeonatoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $campeonatos = $this->campeonatoService->index();
        
        return $campeonatos;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $campeonato = $this->campeonatoService->store($request);

        return response()->json($campeonato, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->campeonatoService->show($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $campeonato = $this->campeonatoService->update($request, $id);

        return response()->json($campeonato);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->campeonatoService->destroy($id);
        
        return response()->json(['message' => 'Time removido com sucesso']);
    }

    public function calculaCampeonato(string $id)
    {
        $resultado = $this->campeonatoService->calculaCampeonato($id);

        return response()->json(['message' => $resultado]);
    }
}
