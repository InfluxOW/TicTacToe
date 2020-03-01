<?php

namespace App;

use function cli\line;
use function cli\prompt;
use cli\Table;

class GameProcess
{
    public $game;
    public $first;
    public $enemy;

    public function __construct($game, $first, $enemy)
    {
        $this->game = $game;
        $this->first = $first;
        $this->enemy = $enemy;
    }

    public function start()
    {
        switch($enemy) {
            case 'player':
            case 'AI':
        }

        while ($game !== true && $game->isWinner($fillerAI) !== true && $i <= 9) {
            if ($i % 2 === 1) {
                $game->PlayerTurn();
                $i++;
            }
            if ($i % 2 === 0) {
                $game->AITurn();
                $i++;
            }
        }
        echo $game->isWinner($fillerPlayer) ? 'Congratulations! You won!' : ($game->isWinner($fillerAI) ? 'Don\'t worry you\'l win next time!' : 'Draw');
    }
}