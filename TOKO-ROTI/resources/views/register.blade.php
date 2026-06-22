@extends('layouts.app')

@section('content')
<div class="container" style="padding-bottom: 250px;">
    <h2 style="width: 100%; border-bottom: 4px solid #C8B273; color: #C8B273;">
        <b>Register</b>
    </h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ url('/register') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nama" style="color: #C8B273;">Name</label>
                    <input type="text" class="form-control" id="Name" placeholder="Name" name="nama" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email" style="color: #C8B273;">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="username" style="color: #C8B273;">Username</label>
                    <input type="text" class="form-control" id="username" placeholder="Username" name="username" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="telp" style="color: #C8B273;">No Telp</label>
                    <input type="text" class="form-control" id="telp" placeholder="+62" name="telp" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="password" style="color: #C8B273;">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="konfirmasi" style="color: #C8B273;">Confirm Password</label>
                    <input type="password" class="form-control" id="konfirmasi" placeholder="Confirm Password" name="konfirmasi" required>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Register</button>
    </form>
</div>
@endsection
