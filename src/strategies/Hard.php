<?php

namespace App\strategies;

class Hard
{
    public function move(\App\Game $game)
    {
        $board = $game->map;
        $row = rand(1, sizeof($board));
        $col = rand(1, sizeof($board));
        if ($board[$row][$col] === '...') {
            $game->map[$row][$col] = $game->filler2;
            return $game;
        }
        self::move($game);
    }
}