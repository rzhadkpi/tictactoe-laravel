<?php

namespace App\Services;

use App\Events\BoardUpdatedEvent;
use App\Models\Room;

class RoomService
{
    public function createBoard(string $side): Room
    {
        $room = new Room();
        $room->host_move = $side;
        $room->board = $this->fillBoard();

        session()->remove('side');
        session()->put('side', $side);

        $room->save();

        return $room;
    }

    public function updateBoard(Room $room, string $move, int $x, int $y): bool
    {
        if ($move !== $room->current_move) {
            return false;
        }

        if ($room->board[$x][$y] !== '.') {
            return false;
        }

        $room->current_move = $this->changeMove($move);
        $board = $room->board;
        $board[$x][$y] = $move;
        $room->board = $board;
        $room->save();
        BoardUpdatedEvent::dispatch($room->id, $room->board, $room->current_move);

        return true;
    }

    public function refreshBoard(Room $room): void
    {
        $room->board = $this->fillBoard();
        $room->current_move = 'X';
        $room->save();
        BoardUpdatedEvent::dispatch($room->id, $room->board, $room->current_move);
    }

    public function changeMove(string $move): string
    {
        return $move === 'X' ? 'O' : 'X';
    }


    public function isBoardEmpty(array $board): bool
    {
        return count(array_filter(array_merge(...$board), function ($item) {
                return $item === 'X' || $item === 'O';
            })) === 0;
    }

    private function fillBoard(): array
    {
        $board = [];
        for ($i = 0; $i < 3; $i++) {
            $board[] = array_fill(0, 3, '.');
        }

        return $board;
    }
}
