<?php

namespace App\Http\Controllers;

use App\Interfaces\IGameService;
use App\Services\GameService;
use Illuminate\View\View;
use App\Http\Requests\GuessRequest;
use Illuminate\Support\Facades\Auth;
use stdClass;

class GameController extends Controller
{
    public function index(IGameService $service): View
    {
        if (! session()->has('number')) {
            $data = $service->generateNumber();
            session(['number' => $data['number']]);
            session(['name' => Auth::user()->name]);
            session(['numberPositions' => $data['numberPositions']]);
        }

        echo session()->get('number');

        $topTenPlayers = null;//json_decode(Storage::get('top-attempts.json'));

        return view('game')->with(compact('topTenPlayers'));
    }

    public function guess(GuessRequest $request, IGameService $service)
    {
        $guessNumber = $request->get('number');
        $number = session('number');
        $numberPositions = session('numberPositions');

        session()->increment('attempts');
        session()->save();

        if ($number == $guessNumber) {
            $player = new stdClass();
            $player->name = session('name');
            $player->attempts = session('attempts');
            $service->generateTopPlayers($player);

            return redirect('game')->with(['success' => true]);
        }
        $bulls = 0;
        $cows = 0;
        $guessArr = array_fill(0, 10, 0);
        for ($i = 0; $i < 4; $i++) {
            $guessArr[$guessNumber[$i]]++;
            if ($guessArr[$guessNumber[$i]] > 1) {
                return redirect('game')->withErrors(['number' => 'Duplicate digits!']);
            }
            if ($numberPositions[$guessNumber[$i]] > 0) {
                if ($numberPositions[$guessNumber[$i]] == $i+1) {
                    $bulls++;
                }
                else {
                    $cows++;
                }
            }
        }

        return redirect('game')->with(['bulls' => $bulls, 'cows' => $cows, 'attempts' => session('attempts') ]);
    }
}
