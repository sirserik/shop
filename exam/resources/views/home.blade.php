@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center">Домашняя страница</h1>
        <p class="lead text-center">Добро пожаловать, {{ auth()->user()->name }}!</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Ваш профиль</div>
                    <div class="card-body">
                        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                        <p><strong>Роль:</strong> {{ auth()->user()->role }}</p>
                        <p>Здесь вы можете разместить основную информацию, последние обновления и другие данные, важные для вашего приложения.</p>
                        <a href="{{ route('logout') }}" class="btn btn-danger"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Выйти
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
