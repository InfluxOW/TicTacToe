<?php

namespace App;

use function cli\line;
use function cli\prompt;

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
        $first = $this->first === '1' ? 0 : 1;

        for ($i = 1 + $first; $i <= sizeof($this->game->map) ** 2 + $first; $i++) {
            switch ($this->enemy) {
                case 'player':
                    $result = $this->pvp($i);
                    if ($result[0] === true) {
                        line("Congratulations! {$result[1]} won!");
                        return true;
                    }
                    break;
                case 'AI':
                    $result = $this->pve($i);
                    if ($result[0] === true && $result[1] === 'AI') {
                        line("Don't worry you'll win next time!");
                        return true;
                    } elseif ($result[0] === true) {
                        line("Congratulations! You won!");
                        return true;
                    }
                    break;
            }
        }
        line('Draw!');
        return true;
    }

    public function pvp($i)
    {
        if ($i % 2 === 1) {
            line(PHP_EOL . "---{$this->game->player1}'s turn---" . PHP_EOL);
            $turn = $this->turnSelect();
            return $this->game->PlayerTurn($turn[0], $turn[1], $this->game->player1);
        }
        if ($i % 2 === 0) {
            line(PHP_EOL . "---{$this->game->player2}'s turn---" . PHP_EOL);
            $turn = $this->turnSelect();
            return $this->game->PlayerTurn($turn[0], $turn[1], $this->game->player2);
        }
    }

    public function pve($i)
    {
        if ($i % 2 === 1) {
            line(PHP_EOL . "---Your turn---" . PHP_EOL);
            $turn = $this->turnSelect();
            return $this->game->PlayerTurn($turn[0], $turn[1], null);
        }
        if ($i % 2 === 0) {
            line(PHP_EOL . "---AI turn---" . PHP_EOL);
            return $this->game->AIturn();
        }
    }

    public function turnSelect()
    {
        $row = prompt('Select row');
        $col = prompt('Select column');
        if ($row > sizeof($this->game->map) || $col > sizeof($this->game->map)) {
            throw new \Exception('You are out of the board.');
        }
        if ($this->game->map[$row][$col] !== '...') {
            throw new \Exception('You can not refill enemy turns.');
        }
        return [$row, $col];
    }
}
