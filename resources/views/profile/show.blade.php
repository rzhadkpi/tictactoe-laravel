@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center">
        <div class="card shadow-lg" style="width: 100%; max-width: 400px;">
            <div class="card-header text-center bg-primary text-white">
                <h4>Профіль користувача</h4>
            </div>
            <div class="card-body">
                <h5 class="card-title text-center">{{ $user->name }}</h5>
                <p class="card-text">Email: {{ $user->email }}</p>
                <p class="card-text">Дата реєстрації: {{ $user->created_at->format('d.m.Y') }}</p>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <div class="text-center">
                        <button type="submit" class="btn btn-danger btn-block mt-3">Вийти</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 10px;
        }

        .card-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            background-color: #6f42c1;
        }

        .card-header h4 {
            margin: 0;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
        }
    </style>
@endsection
