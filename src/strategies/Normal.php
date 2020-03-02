<?php

namespace App\strategies;

class Normal
{
    public function move(\App\Game $game)
    {
        foreach (array_reverse($game->map, true) as $row => $cols) {
            foreach ($cols as $col => $value) {
                if ($value === '...') {
                    $game->map[$row][$col] = $game->filler2;
                    return $game;
                }
            }
        }
    }
}
