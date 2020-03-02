<?php

namespace App;

use function cli\line;
use function cli\prompt;
use function cli\choose;

use App\Game;
use App\Board;

class Cli
{
    public function run()
    {
        line(PHP_EOL . 'Welcome to the TicTacToe!' . PHP_EOL);
    
        $enemy = choose('Do you want to play against the other player? Otherwise you\'ll play against AI.', 'yn', 'n');
        if ($enemy === 'n') {
            $difficulty = choose('Choose your difficulty: easy, normal or hard', 'ENH', 'e');
        } else {
            $player1 = prompt('What is player one name?');
            $player2 = prompt('What is player two name?');
        }
        $filler = choose('Choose your filler: X or O', 'XO', 'X');
        $boardSize = choose('Select board size: small, medium or large', 'SML', 'S');
        $board = new Board(strtolower($boardSize));
        $firstTurn = choose('Who will turn first: p1 or p2/AI', '12', '1');
    
        $properties = [
            'enemy' => $enemy === 'y' ? 'player' : 'AI',
            'difficulty' => isset($difficulty) ? strtolower($difficulty) : null,
            'names' => [$player1 ?? null, $player2 ?? null],
            'filler' => strtolower($filler),
            'board' => $board->map,
        ];
    
        $game = new Game($properties);
        $game->table->display();

        $gameProcess = new GameProcess($game, $firstTurn, $properties['enemy']);
        $gameProcess->start();
    }
}


