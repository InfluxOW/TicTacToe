<?php

namespace App;

use App\AI\AI;
use App\Enums\Filler;
use App\Enums\PlayerType;

class Player
{
    public PlayerType $type;
    public string $name;
    public Filler $filler;
    public AI|null $ai;
}
