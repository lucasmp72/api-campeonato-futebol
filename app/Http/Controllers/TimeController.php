<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TimeService;

class TimeController extends Controller
{
    private TimeService $timeService;

    public function __construct(TimeService $timeService)
    {
        $this->timeService = $timeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $times = $this->timeService->index();
        
        return $times;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $time = $this->timeService->store($request);

        return response()->json($time, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $time = $this->timeService->show($id);

        return $time;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $time = $this->timeService->update($request, $id);

        return response()->json($time);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->timeService->destroy($id);

        return response()->json(['message' => 'Campeonato removido com sucesso']);
    }
}
