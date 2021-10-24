<?php

namespace App\Enums;

use App\AI\AI;
use App\AI\Easy;
use App\AI\Hard;
use App\AI\Normal;

enum Difficulty: string
{
    case EASY = 'e';
    case NORMAL = 'n';
    case HARD = 'h';

    public function ai(): AI
    {
        return match ($this) {
            self::EASY => new Easy(),
            self::NORMAL => new Normal(),
            self::HARD => new Hard(),
        };
    }
}
