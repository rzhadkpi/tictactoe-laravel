@extends('layouts.app')

@section('content')
    <div class="container text-center">
        <h1>TicTacToe Game</h1>
        <button id="startGameBtn" class="btn btn-primary mt-4">Почати гру</button>
        <div class="mt-4">
            <img src="https://media.istockphoto.com/id/1365567894/vector/hand-drawn-vector-tic-tac-toe-game-noughts-and-crosses-doodle-sketch.jpg?s=612x612&w=0&k=20&c=pSs72urXBp6V8pnXvuJIfX3krtUoFhHaX6fG2g1PxUQ=" class="img-fluid mx-auto d-block mt-4" alt="Tic Tac Toe Game Image">
        </div>
    </div>

    <div class="modal fade" id="sideSelectionModal" tabindex="-1" aria-labelledby="sideSelectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sideSelectionModalLabel">Оберіть сторону</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">х</button>
                </div>
                <div class="modal-body text-center">
                    <p>За кого ви хочете заграти?</p>
                    <button class="btn btn-primary btn-choice" data-side="X">Грати за X</button>
                    <button class="btn btn-success btn-choice" data-side="O">Грати за O</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const startGameBtn = document.getElementById('startGameBtn');
            const sideSelectionModal = new bootstrap.Modal(document.getElementById('sideSelectionModal'), {});

            startGameBtn.addEventListener('click', () => {
                sideSelectionModal.show();
            });

            document.querySelectorAll('.btn-choice').forEach(button => {
                button.addEventListener('click', async () => {
                    const chosenSide = button.getAttribute('data-side');

                    try {
                        const response = await fetch('/rooms', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ side: chosenSide })
                        });

                        if (!response.ok) {
                            throw new Error('Помилка створення кімнати.');
                        }

                        const redirectUrl = response.url;

                        window.location.href = redirectUrl;
                    } catch (error) {
                        console.log(error.message)
                    }
                });
            });
        });
    </script>
@endsection
