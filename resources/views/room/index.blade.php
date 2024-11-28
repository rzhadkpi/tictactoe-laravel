@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-center align-items-start min-vh-100 pt-5">
        <div class="container text-center">
            <h2>TicTacToe</h2>
            <div id="game-area" class="mt-3">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <h4 class="game-room-title">Кімната: <span id="room-id">{{ $room->id }}</span></h4>
                        <div id="game-status" class="mt-4">
                            <p><strong id="turn-message">Ви граєте за: {{ session('side') }}</strong></p>
                            <button id="copy-link-btn" class="btn btn-link p-0" data-bs-toggle="tooltip" title="Копировать ссылку на игру">
                                <u>Посилання на гру</u>
                            </button>
                            <p id="game-result-message" class="game-result-message" style="color: red; font-weight: bold;"></p>
                            <button id="refresh-game" class="btn btn-primary mt-2 d-none">Почати заново</button>
                        </div>
                        <div id="game-board" class="d-flex justify-content-center mt-4">
                            <div class="board-grid">
                                @for ($i = 0; $i < 3; $i++)
                                    <div class="board-row">
                                        @for ($j = 0; $j < 3; $j++)
                                            <div class="board-cell" data-x="{{ $i }}" data-y="{{ $j }}">{{ $room->board[$i][$j] }}</div>
                                        @endfor
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .min-vh-100 {
            min-height: 100vh;
        }

        .game-room-title {
            font-size: 1.5rem;
            color: #333;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .game-status p {
            font-size: 1.2rem;
            color: #333;
        }

        .game-result-message {
            font-size: 1.2rem;
        }

        .btn-link {
            color: #007bff;
            text-decoration: underline;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
        }

        .btn-link:hover {
            color: #0056b3;
        }

        .board-grid {
            display: grid;
            grid-template-columns: repeat(3, 100px);
            grid-template-rows: repeat(3, 100px);
            gap: 5px;
        }

        .board-cell {
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .board-cell.taken {
            cursor: not-allowed;
            background-color: #d9d9d9;
        }

        .board-cell:hover {
            background-color: #f8f9fa;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-primary:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5);
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const roomId = "{{ $room->id }}";
            const playerSymbol = "{{ session('side') }}";

            Pusher.logToConsole = true;

            const pusher = new Pusher('845cc525740bd15fca3e', {
                cluster: 'eu',
            });

            const channel = pusher.subscribe(`room.${roomId}`);
            channel.bind('pusher:subscription_succeeded', () => {
                console.log('Subscribed to channel room.' + roomId);
            });

            channel.bind('board.updated', (data) => {
                updateBoardUI(data.board);
                document.getElementById('turn-message').innerText = data.currentMove === playerSymbol
                    ? 'Ваш хід!'
                    : 'Хід суперника!';

                const resultMessage = document.getElementById('game-result-message');
                const refreshButton = document.getElementById('refresh-game');

                if (data.winner) {
                    if (data.winner === 'draw') {
                        resultMessage.innerText = 'Гра закінчилась нічиєю!';
                    } else {
                        resultMessage.innerText = `Переможець: ${data.winner}`;
                    }

                    refreshButton.classList.remove('d-none');
                }
            });

            function updateBoardUI(board) {
                document.querySelectorAll('.board-cell').forEach(cell => {
                    const x = cell.getAttribute('data-x');
                    const y = cell.getAttribute('data-y');

                    cell.innerText = board[x][y] !== '.' ? board[x][y] : '';

                    if (board[x][y] === '.') {
                        cell.classList.remove('taken');
                    } else {
                        cell.classList.add('taken');
                    }
                });
            }

            document.querySelectorAll('.board-cell').forEach(cell => {
                cell.addEventListener('click', async () => {
                    const x = cell.getAttribute('data-x');
                    const y = cell.getAttribute('data-y');

                    if (!cell.classList.contains('taken') && playerSymbol) {
                        try {
                            const response = await fetch(`/rooms/${roomId}/move`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                },
                                body: JSON.stringify({ x: parseInt(x), y: parseInt(y), move: playerSymbol }),
                            });

                            if (!response.ok) {
                                throw new Error('Неправильний хід.');
                            }
                        } catch (error) {
                            console.error(error.message);
                        }
                    }
                });
            });

            document.getElementById('refresh-game').addEventListener('click', async () => {
                try {
                    const response = await fetch(`/rooms/${roomId}/refresh`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Помилка при оновленні гри.');
                    }

                    document.getElementById('refresh-game').classList.add('d-none');
                } catch (error) {
                    console.error(error.message);
                }
            });

            document.getElementById('copy-link-btn').addEventListener('click', () => {
                const gameUrl = window.location.origin + `/rooms/${roomId}/join`;
                navigator.clipboard.writeText(gameUrl).then(() => {
                    alert('Посилання на гру скопійоване!');
                }).catch(err => {
                    console.error('Помилка при копіювання посилання: ', err);
                });
            });
        });

    </script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

@endsection
