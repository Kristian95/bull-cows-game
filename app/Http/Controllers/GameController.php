<?php

namespace App\Http\Controllers;

use App\Interfaces\IGameService;
use App\Services\GameService;
use Illuminate\View\View;
use Illuminate\Http\Request;

class GameController extends Controller
{
    protected IGameService $service;

    public function __construct(GameService $service)
    {
        $this->service = $service;
    }

    public function index(): View
    {
        $number = $this->service->generateNumber();

        session(['number' => $number]);

        return view('game');
    }

    public function guess(Request $request)
    {
        dd($request->all());
        
    }
}
