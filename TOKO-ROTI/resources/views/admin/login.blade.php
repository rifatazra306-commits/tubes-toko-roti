<!DOCTYPE html>
<html>
<head>
    <title>Login Admin - Rivina Cake & Bakery</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <style type="text/css">
        body {
            background-color: #f5f5f5;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .login-title {
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
            color: #C8B273;
;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h3 class="login-title">LOGIN ADMIN</h3>
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ url('/admin/login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="user">Username</label>
                    <input type="text" class="form-control" id="user" placeholder="Username" name="user" required autofocus autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="pass">Password</label>
                    <input type="password" class="form-control" id="pass" placeholder="Password" name="pass" required autocomplete="off">
                </div>
                <button type="submit" class="btn btn-warning btn-block" style="background-color: #C8B273; border-color: #C8B273; color: white;">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
