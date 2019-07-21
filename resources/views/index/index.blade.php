@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-16 bg-white py-12 text-center rounded shadow-lg">
        <h1>Welcome to Todo</h1>
        @guest
            <div class="mt-2">
                <a class="btn primary" href="{{ url('login/google') }}">Google Login</a>
            </div>
        @endguest

        @auth
            Hello, {{ auth()->user()->name }}
        @endauth
    </div>
@endsection