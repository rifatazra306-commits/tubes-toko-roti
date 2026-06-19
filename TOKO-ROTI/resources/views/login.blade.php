@extends('layouts.app')

@section('content')
<div class="container" style="padding-bottom: 250px;">
    <h2 style="width: 100%; border-bottom: 4px solid #ff8680"><b>Login</b></h2>

    @if(session('error'))
        <div class="alert alert-danger" style="width: 500px;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ url('/login') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" placeholder="Username" name="username" style="width: 500px;" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password" name="pass" style="width: 500px;" required>
        </div>
        
        <button type="submit" class="btn btn-success">Login</button>
        <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
    </form>
</div>
@endsection
