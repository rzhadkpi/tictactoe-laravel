<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TicTacToe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <style>
        .navbar {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-bottom: 2px solid #f8f9fa;
            position: sticky;
            top: 0;
            z-index: 1030;
            padding: 0 2rem;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
            color: #343a40;
            letter-spacing: 2px;
            transition: color 0.3s ease-in-out, transform 0.3s ease-in-out;
        }
        .navbar-brand:hover {
            color: #007bff;
            transform: scale(1.1);
        }

        .nav-link {
            color: #5a5a5a;
            font-weight: bold;
            font-size: 1rem;
            padding: 0.5rem 1rem;
            transition: color 0.3s ease-in-out, background-color 0.3s ease-in-out;
        }

        .nav-link:hover {
            color: #ffffff;
            background-color: #007bff;
            border-radius: 5px;
        }

        .content {
            padding-top: 20px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/">TicTacToe</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile') }}">Профіль</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Вийти</a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endauth

            @guest
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Вхід</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Реєстрація</a></li>
            @endguest
        </ul>
    </div>
</nav>

    <div class="content">
        @yield('content')
    </div>

</body>
</html>
