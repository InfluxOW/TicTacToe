<?php

namespace App;

use function cli\line;
use function cli\prompt;
use cli\Table;

class Game
{
    public $properties;

    public function __construct($properties)
    {
        $this->map = $properties['board'];

        switch ($properties['enemy']) {
            case 'player':
                $this->player1 = $properties['names'][0];
                $this->player2 = $properties['names'][1];
                break;
            case 'AI':
                switch ($properties['difficulty']) {
                    case 'e':
                        $this->strategy = new strategies\Easy();
                        break;
                    case 'n':
                        $this->strategy = new strategies\Normal();
                        break;
                    case 'h':
                        $this->strategy = new strategies\Hard();
                        break;
                }
        }

        $this->table = new Table();
        switch ($properties['borderSize']) {
            case 'small':
                $this->table->setHeaders(['Tic', 'Tac', 'Toe']);
                break;
            case 'medium':
                $this->table->setHeaders(['Tic', 'Tac', 'Toe', '<----']);
                break;
            case 'large':
                $this->table->setHeaders(['---','Tic', 'Tac', 'Toe', '---']);
                break;
        }

        switch ($properties['filler']) {
            case 'x':
                $this->filler1 = '.X.';
                $this->filler2 = '.O.';
                break;
            case 'o':
                $this->filler1 = '.O.';
                $this->filler2 = '.X.';
                break;
        }
    }

    public function AITurn()
    {
        line(PHP_EOL . '---AI turn---' . PHP_EOL);
        
        $this->strategy->move($this);
        $this->table->setRows($this->map);
        $this->table->display();
    }

    public function PlayerTurn($playerName = null)
    {
        if ($playerName) {
            line(PHP_EOL . "---{$playerName}\'s turn---" . PHP_EOL);
            $currentFiller = $this->player1 === $playerName ? $this->filler1 : $this->filler2;
        } else {
            line(PHP_EOL . "---Your turn---" . PHP_EOL);
            $currentFiller = $this->filler1;
        }
        
        $row = prompt('Select row');
        $col = prompt('Select column');
        if ($row > sizeof($this->map) || $col > sizeof($this->map)) {
            throw new \Exception('You are out of the board.');
        }
        if ($this->map[$row][$col] !== '...') {
            throw new \Exception('You can not refill enemy turns.');
        }
        
        $this->map[$row][$col] = $currentFiller;
        $this->table->setRows($this->map);
        $this->table->display();
        return $this->isWinner($currentFiller);
    }


    private function isWinner($filler)
    {
        foreach ($this->map as $row) {
            if ($this->populatedByOnePlayer($row, $filler)) {
                return true;
            }
        }

        for ($i = 1; $i <= sizeof($this->map); $i++) {
            if ($this->populatedByOnePlayer(array_column($this->map, $i), $filler)) {
                return true;
            }
        }

        switch (sizeof($this->map)) {
            case 3:
                $diagonal1 = [$this->map[1][1], $this->map[2][2], $this->map[3][3]];
                $diagonal2 = [$this->map[1][3], $this->map[2][2], $this->map[3][1]];
                break;
            case 4:
                $diagonal1 = [$this->map[1][1], $this->map[2][2], $this->map[3][3], $this->map[4][4]];
                $diagonal2 = [$this->map[1][4], $this->map[2][3], $this->map[3][2], $this->map[4][1]];
                break;
            case 5:
                $diagonal1 = [$this->map[1][1], $this->map[2][2], $this->map[3][3], $this->map[4][4], $this->map[5][5]];
                $diagonal2 = [$this->map[1][5], $this->map[2][4], $this->map[3][3], $this->map[4][2], $this->map[5][1]];
                break;
        }

        if ($this->populatedByOnePlayer($diagonal1, $filler) || $this->populatedByOnePlayer($diagonal2, $filler)) {
            return true;
        }

        return false;
    }

    private function populatedByOnePlayer($row, $filler)
    {
        foreach ($row as $value) {
            if ($value !== $filler) {
                return false;
            }
        }
        return true;
    }
}
