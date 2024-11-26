@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center">
        <div class="card shadow-lg" style="width: 100%; max-width: 400px;">
            <div class="card-header text-center bg-primary text-white">
                <h4>Вхід в акаунт</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Введіть ваш email">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Введіть ваш пароль">
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label for="remember" class="form-check-label">Запам'ятати мене</label>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-block mt-3">Увійти</button>
                    </div>

                    <div class="text-center mt-3">
                        <p>Немає акаунту? <a href="{{ route('register') }}">Зареєструйтесь</a></p>
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
        }

        .btn-primary {
            background-color: purple;
            border-color: purple;
        }

        .btn-primary:hover {
            background-color: purple;
            border-color: purple;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
        }

        .invalid-feedback {
            display: block;
        }

        .form-check-label {
            font-weight: normal;
        }
    </style>
@endsection
