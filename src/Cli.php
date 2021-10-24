<?php

namespace App;

use App\Enums\BoardSize;
use App\Enums\Difficulty;
use App\Enums\Filler;
use App\Enums\PlayerType;
use App\Exceptions\TicTacToeException;

use function cli\choose;
use function cli\confirm;
use function cli\line;
use function cli\prompt;

class Cli
{
    private const EXIT_CODE_SUCCESS = 0;
    private const EXIT_CODE_GENERIC_ERROR = 1;

    public static function run(): void
    {
        line(PHP_EOL . 'Welcome to the TicTacToe!' . PHP_EOL);

        $player = new Player();
        $player->type = PlayerType::HUMAN;
        $player->name = prompt('What is your name?');
        $player->filler = Filler::from(choose('Choose your side: X or O', 'XO', 'X'));

        $opponent = new Player();
        $opponent->type = confirm('Do you want to play against the other human') ? PlayerType::HUMAN : PlayerType::AI;
        $opponent->name = ($opponent->type === PlayerType::HUMAN) ? prompt('What is your opponent name?') : 'AI';
        $opponent->filler = ($player->filler === Filler::O) ? Filler::X : Filler::O;
        $opponent->ai = ($opponent->type === PlayerType::HUMAN) ? null : Difficulty::from(choose('Choose AI difficulty: Easy, Normal or Hard', 'ENH', 'E'))->ai();

        $boardSize = BoardSize::from(choose('Select board size: Small, Medium or Large', 'SML', 'S'));
        $board = new Board($boardSize);

        $game = new Game($board, $player, $opponent);
        try {
            $game->start(confirm('Do you want to make a first move'));
            exit(self::EXIT_CODE_SUCCESS);
        } catch (TicTacToeException $e) {
            print_r($e->getMessage());
            exit(self::EXIT_CODE_GENERIC_ERROR);
        }
    }
}
