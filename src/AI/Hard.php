<?php

namespace App\AI;

use App\Board;
use App\Enums\Filler;

class Hard implements AI
{
    public function move(Board $board, Filler $filler): void
    {
        $row = random_int(1, $board->length);
        $col = random_int(1, $board->length);

        if ($board->isCellEmpty($row, $col)) {
            $board->fill($row, $col, $filler);
            return;
        }

        self::move($board, $filler);
    }
}
