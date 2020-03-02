<?php

namespace App;

use cli\Table;

class Game
{
    private $properties;
    public $map;
    public $table;
    public $player1;
    public $player2;
    private $strategy;
    public $filler1;
    public $filler2;


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
        $this->table->setRows($this->map);
        switch (sizeof($properties['board'])) {
            case 3:
                $this->table->setHeaders(['Tic', 'Tac', 'Toe']);
                break;
            case 4:
                $this->table->setHeaders(['Tic', 'Tac', 'Toe', '!!!']);
                break;
            case 5:
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
        $this->strategy->move($this);
        $this->table->setRows($this->map);
        $this->table->display();
        return [$this->isWinner($this->filler2), 'AI'];
    }

    public function PlayerTurn($playerName = null, $row, $col)
    {
        if ($playerName) {
            $currentFiller = $this->player1 === $playerName ? $this->filler1 : $this->filler2;
        } else {
            $currentFiller = $this->filler1;
        }
        
        $this->map[$row][$col] = $currentFiller;
        $this->table->setRows($this->map);
        $this->table->display();
        return [$this->isWinner($currentFiller), $playerName];
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
