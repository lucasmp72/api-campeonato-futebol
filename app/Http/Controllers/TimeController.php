<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Time;

class TimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $times = Time::all()->where("ativo", true);
        
        return $times;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'ativo' => 'boolean'
        ]);

        $time = Time::create([
            'nome' => $request->nome,
            'ativo' => $request->ativo ?? true,
            'data_criacao' => now()
        ]);

        return response()->json($time, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Time::where("id", "=", $id)->where("ativo", true)->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $time = Time::findOrFail($id);

        $request->validate([
            'nome' => 'string|max:255'
        ]);

        $time->update($request->only(['nome']));

        return response()->json($time);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $time = Time::where('id', '=', $id)->where('ativo', true)->firstOrFail();
        $time->ativo = false;
        $time->update($time->only(['ativo']));

        return response()->json(['message' => 'Time removido com sucesso']);
    }
}
