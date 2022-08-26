<?php

namespace App\Services;

use App\Interfaces\IGameService;
use Illuminate\Support\Facades\Storage;

class GameService implements IGameService
{
    public function generateNumber(): array
    {
        $numbers = '0123456789';
        $number = '';
        $numberPositions = array_fill(0, 10, 0);
        for ($i = 1; $i <= 4; $i++) {
            $rand = rand(0, strlen($numbers)-1);
            $number .= $numbers[$rand];
            $numberPositions[$numbers[$rand]] = $i;
            $numbers = str_replace($numbers[$rand], '', $numbers);
        }
        $hasOneEight = $numberPositions[1] && $numberPositions[8] && abs($numberPositions[1] - $numberPositions[8]) > 1;  
        $fourOnEventPosition = $numberPositions[4] && $numberPositions[4] % 2 == 0;
        $fiveOnEventPosition = $numberPositions[5] && $numberPositions[5] % 2 == 0;
        
        /**
         * Check if one and eight numbers exists and make them next to each other
         */
        if ($hasOneEight) {
            $index8 = $numberPositions[8] - 1;
            $index1 = $numberPositions[1] - 1;
            $newIndex = $numberPositions[1] < $numberPositions[8] ? $index1 + 1 : $index1 - 1;
            $temp = $number[$newIndex];
            $number[$newIndex] = $number[$index8];
            $number[$index8] = $temp;
            $numberPositions[8] = $newIndex + 1;
            $numberPositions[$temp] = $index8 + 1;
        }

        /**
         * Check if four is on event position and change the positions
         */
        if ($fourOnEventPosition) {
            $index4 = $numberPositions[4] - 1;
            $newIndex = (!$hasOneEight || ($number[$index4-1] != 1 && $number[$index4-1] != 8)) ?
            $index4 - 1 : (($index4 - 3 > 0) ? $index4 - 3 : $index4 + 1);
            $temp = $number[$newIndex];
            $number[$newIndex] = $number[$index4];
            $number[$index4] = $temp;
            $numberPositions[4] = $newIndex + 1;
            $numberPositions[$temp] = $index4 + 1;
        }

        /**
         * Check if five is on event position and change the positions
         */
        if ($fiveOnEventPosition) {
            $index5 = $numberPositions[5] - 1;
            $newIndex = (!$hasOneEight || ($number[$index5-1] != 1 && $number[$index5-1] != 8)) ?
            $index5 - 1 : (($index5 - 3 > 0) ? $index5 - 3 : $index5 + 1);
            $temp = $number[$newIndex];
            $number[$newIndex] = $number[$index5];
            $number[$index5] = $temp;
            $numberPositions[5] = $newIndex + 1;
            $numberPositions[$temp] = $index5 + 1;
        }

        return ['number' => $number, 'numberPositions' => $numberPositions];
    }

    public function generateTopPlayers($player): void
    {
        $json = Storage::get('top-attempts.json');
        $data = json_decode($json);
        if (!$data) {
            $data = [];
        }
        if (count($data) == 0) {
            $data[] = $player;
        }
        else {
            $lastIndex = count($data) - 1;
            if (count($data) < 10) {
                $data[] = $player;
                $lastIndex++;
            } elseif ($data[$lastIndex]->attempts > $player->attempts ||
                ($data[$lastIndex]->$player == $player->attempts)) {
                $data[$lastIndex] = $player;
            } else {
                return;
            }
            for ($i = $lastIndex - 1; $i >= 0; $i--) {
                if ($data[$i]->attempts > $player->attempts ||
                    ($data[$i]->attempts == $player->attempts)) {
                    $temp = $data[$i];
                    $data[$i] = $data;
                    $data[$i + 1] = $temp;
                }
            }
        }
        $json = json_encode($data);
        Storage::put('top-attempts.json', $json);
    }
}