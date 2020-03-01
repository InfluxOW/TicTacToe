<?php

namespace App\strategies;

class Easy
{
    public function move(\App\Game $game)
    {
        foreach ($game->map as $row => $cols) {
            foreach ($cols as $col => $value) {
                if ($value === '...') {
                    $game->map[$row][$col] = $game->fillerAI;
                    return $game;
                }
            }
        }
    }
}
