<?php

namespace App\Enums;

enum BoardSize: string
{
    case S = 's';
    case M = 'm';
    case L = 'l';

    public function length(): int
    {
        return match ($this) {
            self::S => 3,
            self::M => 4,
            self::L => 5,
        };
    }
}
