<?php

namespace App;

class Board
{
    public $boardSize;

    public function __construct($boardSize)
    {
        switch ($boardSize) {
            case 's':
                $size = 3;
                break;
            case 'm':
                $size = 4;
                break;
            case 'l':
                $size = 5;
                break;
        }

        $rows = array_fill(1, $size, []);
        $this->map = array_map(
            function ($row) use ($size) {
                return $row = array_fill(1, $size, '...');
            },
            $rows
        );
    }
}
