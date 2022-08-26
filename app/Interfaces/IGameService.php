<?php

namespace App\Interfaces;

use stdClass;

interface IGameService
{
    public function generateNumber(): array;

    public function generateTopPlayers(stdClass $player): void;
}