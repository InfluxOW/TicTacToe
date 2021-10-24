<?php

namespace App\AI;

use App\Board;
use App\Enums\Filler;

class Normal implements AI
{
    public function move(Board $board, Filler $filler): void
    {
        foreach (array_reverse($board->show(), true) as $row => $cols) {
            foreach ($cols as $col => $_) {
                if ($board->isCellEmpty($row, $col)) {
                    $board->fill($row, $col, $filler);
                    return;
                }
            }
        }
    }
}
