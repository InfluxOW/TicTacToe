<?php

namespace App;

use App\Enums\BoardSize;
use App\Enums\Filler;
use App\Exceptions\BoardException;

class Board
{
    private const EMPTY_CELL_FILLER = '...';

    public int $length;

    private array $field;

    public function __construct(BoardSize $boardSize)
    {
        $this->length = $boardSize->length();

        $this->field = array_map(
            function () {
                return array_fill(1, $this->length, self::EMPTY_CELL_FILLER);
            },
            array_fill(1, $this->length, [])
        );
    }

    public function show(): array
    {
        return $this->field;
    }

    public function isCellEmpty(int $row, int $col): bool
    {
        return isset($this->field[$row][$col]) && $this->field[$row][$col] === self::EMPTY_CELL_FILLER;
    }

    /**
     * @throws BoardException
     */
    public function fill(int $row, int $col, Filler $filler): void
    {
        if ($row > $this->length || $col > $this->length) {
            throw new BoardException('You are out of the board.');
        }

        if (! $this->isCellEmpty($row, $col)) {
            throw new BoardException('You can\'t refill made moves.');
        }

        $this->field[$row][$col] = ".{$filler->name}.";
    }

    /**
     * @return array[array[], array[]]
     */
    public function getDiagonals(): array
    {
        $first = [];
        $second = [];
        foreach (range(1, $this->length) as $shift => $index) {
            $first[] = $this->field[$index][$index];
            $second[] = $this->field[$index][$this->length - $shift];
        }

        return [$first, $second];
    }
}
