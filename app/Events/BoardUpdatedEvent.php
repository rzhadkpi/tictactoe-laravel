<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BoardUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $roomId,
        public array $board,
        public string $currentMove,
        private ?string $winner = null,
    ) {
        $this->winner = $this->determineWinner();
    }

    public function broadcastOn(): Channel
    {
        return new Channel("room.{$this->roomId}");
    }

    public function broadcastWith(): array
    {
        return [
            'board' => $this->board,
            'currentMove' => $this->currentMove,
            'winner' => $this->winner,
        ];
    }

    public function broadcastAs(): string
    {
        return 'board.updated';
    }

    private function determineWinner(): ?string
    {
        $winningLines = [
            [[0, 0], [0, 1], [0, 2]],
            [[1, 0], [1, 1], [1, 2]],
            [[2, 0], [2, 1], [2, 2]],
            [[0, 0], [1, 0], [2, 0]],
            [[0, 1], [1, 1], [2, 1]],
            [[0, 2], [1, 2], [2, 2]],
            [[0, 0], [1, 1], [2, 2]],
            [[0, 2], [1, 1], [2, 0]],
        ];

        foreach ($winningLines as $line) {
            [$a, $b, $c] = $line;
            if (
                $this->board[$a[0]][$a[1]] !== '.' &&
                $this->board[$a[0]][$a[1]] === $this->board[$b[0]][$b[1]] &&
                $this->board[$a[0]][$a[1]] === $this->board[$c[0]][$c[1]]
            ) {
                return $this->board[$a[0]][$a[1]];
            }
        }

        foreach ($this->board as $row) {
            if (in_array('.', $row, true)) {
                return null;
            }
        }

        return 'draw';
    }
}
