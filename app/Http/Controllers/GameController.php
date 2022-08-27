<?php

namespace App\Http\Controllers;

use App\Interfaces\IGameService;
use Illuminate\View\View;
use App\Http\Requests\GuessRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        if (! session()->has('attemps')) {
            session(['attemps' => 0]);
        }
        echo session('number');

        $topTenPlayers = json_decode(Storage::get('top-attempts.json', true));
        
        return view('game')->with(compact('topTenPlayers'));
    }

    public function guess(GuessRequest $request, IGameService $service)
    {
        $guessNumber = $request->get('number');
        $number = session('number');
        $numberPositions = session('numberPositions');

        $attempts = session()->get('attempts', 0); 
        session()->put('attempts', $attempts + 1);

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

        return redirect('game')->with(['bulls' => $bulls, 'cows' => $cows]);
    }
}
