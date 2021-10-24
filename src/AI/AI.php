<?php

namespace App\AI;

use App\Board;
use App\Enums\Filler;
use App\Exceptions\BoardException;

interface AI
{
    /**
     * @throws BoardException
     */
    public function move(Board $board, Filler $filler): void;
}
