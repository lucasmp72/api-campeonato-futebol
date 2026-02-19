<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Campeonato;
use App\Models\CampeonatoTime;
use App\Models\Partida;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\DB;

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
            'nome' => 'required|string|max:255'
        ]);

        $campeonato = Campeonato::create([
            'nome' => $request->nome,
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
            'nome' => 'string|max:255'
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

    public function simularCampeonato(string $id)
    {   
        $count = CampeonatoTime::where('campeonato_id', $id)->count();

        if($count < 8)
        {
            throw ValidationException::withMessages([
                    'mensagem' => 'Campeonato com menos de 8 times inscritos.'
                ]);
        }

        $partidasCampeonato = DB::table('partidas')
                                ->where('campeonato_id', $id)
                                ->get();
        
        if($partidasCampeonato->count() > 0)
        {
            foreach($partidasCampeonato as $partidaCampeonato)
            {
                DB::table('partidas')->where('id', $partidaCampeonato->id)->delete();
            }
        }
        
        $timesParticipantes = DB::table('campeonatos_times')
                                ->join('times', 'time_id', '=', 'times.id')
                                ->select('times.*')
                                ->pluck('id');

        //Quartas de finais
        $this->sorteiaTimes($timesParticipantes, 1, $id);

        $partidasQuartasFinais = DB::table('partidas')
                                   ->where('campeonato_id', $id)
                                   ->where('fase_id', 1)
                                   ->get();

        $this->calculaResultadoPartidas($partidasQuartasFinais);

        //Semifinais

        $vencedoresQuartasFinais = $this->vencedores($id, 1);

        $this->sorteiaTimes($vencedoresQuartasFinais, 2, $id);

        $partidasSemifinais = DB::table('partidas')
                                ->where('campeonato_id', $id)
                                ->where('fase_id', 2)
                                ->get();

        $this->calculaResultadoPartidas($partidasSemifinais);

        //Terceiro Lugar
        $vencedoresSemifinais = $this->vencedores($id, 2);

        $perdedoresSemifinais = $this->perdedores($vencedoresSemifinais, $id, 2);

        $this->sorteiaTimes($perdedoresSemifinais, 3, $id);

        $partidasTerceiroLugar = DB::table('partidas')
                                   ->where('campeonato_id', $id)
                                   ->where('fase_id', 3)
                                   ->get();

        $this->calculaResultadoPartidas($partidasTerceiroLugar);

        //Final
        $this->sorteiaTimes($vencedoresSemifinais, 4, $id);

        $partidasFinal = DB::table('partidas')
                           ->where('campeonato_id', $id)
                           ->where('fase_id', 4)
                           ->get();

        $this->calculaResultadoPartidas($partidasFinal);

        return $this->resultadosCampeonato($id);
    }

    public function sorteiaTimes($times, $fase_id, $campeonato_id)
    {
        $lista = $times->values();

        while($lista->count() > 0)
        {
            $timeCasa = rand(0, $lista->count() - 1);
            $timeFora = rand(0, $lista->count() - 1);

            if($timeCasa == $timeFora)
            {
                do
                {
                    $timeFora = rand(0, $lista->count() - 1);
                }
                while($timeCasa == $timeFora);
            }

            Partida::create([
                'campeonato_id' => $campeonato_id,
                'time_casa_id' => $lista[$timeCasa],
                'time_visitante_id' => $lista[$timeFora],
                'fase_id' => $fase_id,
                'data_criacao' => now()
            ]);

            $lista->forget($timeCasa);
            $lista->forget($timeFora);

            $lista = $lista->values();
        }
    }

    public function calculaResultadoPartidas($partidas)
    {
        foreach($partidas as $partida)
        {
            $golsCasa = rand(0, 8);
            $golsVisitante = rand(0, 8);
            $penaltisCasa = 0;
            $penaltisVisitante = 0;

            if($golsCasa == $golsVisitante)
            {
                do
                {
                    $penaltisCasa += rand(0, 5);
                    $penaltisVisitante += rand(0, 5);
                }
                while($penaltisCasa == $penaltisVisitante);
            }

            DB::table('partidas')
              ->where('id', $partida->id)
              ->update(['gols_casa' => $golsCasa, 'penaltis_casa' => $penaltisCasa, 'gols_visitante' => $golsVisitante, 'penaltis_visitante' => $penaltisVisitante]);
        }
    }

    public function vencedores($campeonato_id, $fase_id)
    {
        $vencedoresCasa = DB::table('partidas')
                            ->where('campeonato_id', $campeonato_id)
                            ->where('fase_id', $fase_id)
                            ->whereColumn('gols_casa', '>', 'gols_visitante')
                            ->select('time_casa_id as time_id');

        $vencedoresFora = DB::table('partidas')
                            ->where('campeonato_id', $campeonato_id)
                            ->where('fase_id', $fase_id)
                            ->whereColumn('gols_visitante', '>', 'gols_casa')
                            ->select('time_visitante_id as time_id');

        $vencedoresCasaPenaltis = DB::table('partidas')
                                    ->where('campeonato_id', $campeonato_id)
                                    ->where('fase_id', $fase_id)
                                    ->whereColumn('gols_casa', '=', 'gols_visitante')
                                    ->whereColumn('penaltis_casa', '>', 'penaltis_visitante')
                                    ->select('time_casa_id as time_id');

        $vencedoresForaPenaltis = DB::table('partidas')
                                    ->where('campeonato_id', $campeonato_id)
                                    ->where('fase_id', $fase_id)
                                    ->whereColumn('gols_visitante', '=', 'gols_casa')
                                    ->whereColumn('penaltis_visitante', '>', 'penaltis_casa')
                                    ->select('time_visitante_id as time_id');

        $vencedores = $vencedoresForaPenaltis->union($vencedoresCasa)
                                             ->union($vencedoresFora)
                                             ->union($vencedoresCasaPenaltis)
                                             ->pluck('time_id');

        return $vencedores;
    }

    public function perdedores($vencedores, $campeonato_id, $fase_id)
    {
        $perdedoresCasa = DB::table('partidas')
                            ->where('campeonato_id', $campeonato_id)
                            ->where('fase_id', $fase_id)
                            ->whereNotIn('time_casa_id', $vencedores)
                            ->select('time_casa_id as time_id');
        
        $perdedoresVisitante = DB::table('partidas')
                                 ->where('campeonato_id', $campeonato_id)
                                 ->where('fase_id', $fase_id)
                                 ->whereNotIn('time_visitante_id', $vencedores)
                                 ->select('time_visitante_id as time_id');

        $perdedores = $perdedoresCasa->union($perdedoresVisitante)->pluck('time_id');

        return $perdedores;
    }

    public function resultadosCampeonato($campeonato_id)
    {
        $resultadosQuartasFinais = DB::table('partidas')
                                     ->join('times as time_casa', 'time_casa_id', '=', 'time_casa.id')
                                     ->join('times as time_visitante', 'time_visitante_id', '=', 'time_visitante.id')
                                     ->where('campeonato_id', $campeonato_id)
                                     ->where('fase_id', 1)
                                     ->select('time_casa.nome as time_casa',
                                              'gols_casa',
                                              'penaltis_casa',
                                              'penaltis_visitante',
                                              'gols_visitante',
                                              'time_visitante.nome as time_visitante')
                                     ->get();

        $resultadosSemifinais = DB::table('partidas')
                                  ->join('times as time_casa', 'time_casa_id', '=', 'time_casa.id')
                                  ->join('times as time_visitante', 'time_visitante_id', '=', 'time_visitante.id')
                                  ->where('campeonato_id', $campeonato_id)
                                  ->where('fase_id', 2)
                                  ->select('time_casa.nome as time_casa',
                                          'gols_casa',
                                          'penaltis_casa',
                                          'penaltis_visitante',
                                          'gols_visitante',
                                          'time_visitante.nome as time_visitante')
                                  ->get();

        $resultadosTerceiroLugar = DB::table('partidas')
                                     ->join('times as time_casa', 'time_casa_id', '=', 'time_casa.id')
                                     ->join('times as time_visitante', 'time_visitante_id', '=', 'time_visitante.id')
                                     ->where('campeonato_id', $campeonato_id)
                                     ->where('fase_id', 3)
                                     ->select('time_casa.nome as time_casa',
                                              'gols_casa',
                                              'penaltis_casa',
                                              'penaltis_visitante',
                                              'gols_visitante',
                                              'time_visitante.nome as time_visitante')
                                     ->get();

        $resultadosFinal = DB::table('partidas')
                             ->join('times as time_casa', 'time_casa_id', '=', 'time_casa.id')
                             ->join('times as time_visitante', 'time_visitante_id', '=', 'time_visitante.id')
                             ->where('campeonato_id', $campeonato_id)
                             ->where('fase_id', 4)
                             ->select('time_casa.nome as time_casa',
                                      'gols_casa',
                                      'penaltis_casa',
                                      'penaltis_visitante',
                                      'gols_visitante',
                                      'time_visitante.nome as time_visitante')
                             ->get();

        return [
            'resultadosQuartasFinais' => $resultadosQuartasFinais,
            'resultadosSemifinais' => $resultadosSemifinais,
            'resultadosTerceiroLugar' => $resultadosTerceiroLugar,
            'resultadosFinal' => $resultadosFinal
        ];
    }
}