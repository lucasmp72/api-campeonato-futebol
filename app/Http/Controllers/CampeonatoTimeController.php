<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\CampeonatoTimeService;

class CampeonatoTimeController extends Controller
{
    private CampeonatoTimeService $campeonatoTimeService;

    public function __construct(CampeonatoTimeService $campeonatoTimeService)
    {
        $this->campeonatoTimeService = $campeonatoTimeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->campeonatoTimeService->index();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $campeonatoTime = $this->campeonatoTimeService->store($request);

        return response()->json($campeonatoTime, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->campeonatoTimeService->show($id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $campeonatoTime = $this->campeonatoTimeService->destroy($id);

        return response()->json(['message' => 'Registro removido com sucesso']);
    }
}
