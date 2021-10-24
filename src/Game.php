<?php

namespace App;

use App\Enums\Filler;
use App\Enums\PlayerType;
use App\Exceptions\BoardException;
use cli\Table;

use function cli\line;
use function cli\prompt;

class Game
{
    private Table $table;

    public function __construct(public Board $board, public Player $player, public Player $opponent)
    {
        $this->table = new Table();
        $this->table->setRows($this->board->show());
        switch ($this->board->length) {
            case 3:
                $this->table->setHeaders(['Tic', 'Tac', 'Toe']);
                break;
            case 4:
                $this->table->setHeaders(['Tic', 'Tac', 'Toe', '-♥-']);
                break;
            case 5:
                $this->table->setHeaders(['---', 'Tic', 'Tac', 'Toe', '---']);
                break;
        }
    }

    public function start(bool $playerMakesFirstMove): void
    {
        $firstMoveShift = $playerMakesFirstMove ? 0 : 1;
        $this->table->display();

        for ($move = 1 + $firstMoveShift; $move <= $this->board->length ** 2 + $firstMoveShift; $move++) {
            $currentPlayer = ($move % 2 === 1) ? $this->player : $this->opponent;
            $this->tryMakeMove($currentPlayer);
            if ($this->isWinner($currentPlayer)) {
                $this->congratulationsTo($currentPlayer);
                return;
            }
        }

        line('Draw!');
    }

    private function tryMakeMove(Player $player): void
    {
        $makeMove = function (Player $player) use (&$makeMove): void {
            try {
                $this->makeMove($player);
            } catch (BoardException $e) {
                print_r(PHP_EOL . $e->getMessage() . PHP_EOL . 'Try again!' . PHP_EOL);
                $makeMove($player);
            } finally {
                $this->table->display();
            }
        };

        $makeMove($player);
    }

    /**
     * @throws BoardException
     */
    private function makeMove(Player $player): void
    {
        line(PHP_EOL . "---{$player->name}'s move---" . PHP_EOL);

        if ($player->type === PlayerType::HUMAN) {
            $row = (int) prompt('Select row');
            $col = (int) prompt('Select column');
            $this->board->fill($row, $col, $player->filler);
        }

        if ($player->type === PlayerType::AI) {
            $player->ai->move($this->board, $player->filler);
        }

        $this->table->setRows($this->board->show());
    }

    private function isWinner(Player $player): bool
    {
        foreach ($this->board->show() as $row) {
            if ($this->rowIsPopulatedBy($row, $player->filler)) {
                return true;
            }
        }

        for ($i = 1; $i <= $this->board->length; $i++) {
            if ($this->rowIsPopulatedBy(array_column($this->board->show(), $i), $player->filler)) {
                return true;
            }
        }

        [$diagonal1, $diagonal2] = $this->board->getDiagonals();

        return $this->rowIsPopulatedBy($diagonal1, $player->filler) || $this->rowIsPopulatedBy($diagonal2, $player->filler);
    }

    private function rowIsPopulatedBy(array $row, Filler $filler): bool
    {
        foreach ($row as $value) {
            if (str_contains($value, $filler->name)) {
                continue;
            }

            return false;
        }

        return true;
    }

    private function congratulationsTo(Player $player): void
    {
        line(PHP_EOL);

        if ($player->type === PlayerType::AI) {
            line('Don\'t worry! You\'ll win next time!');
        }

        if ($player->type === PlayerType::HUMAN) {
            line("Congratulations! {$player->name} won!");
        }
    }
}
