<!DOCTYPE html>
<html>
<head>
    <title>Rivina Bakery</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-theme.css') }}">
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row top">
            <center>
                <div class="col-md-4" style="padding: 3px;">
                    <span> <i class="glyphicon glyphicon-earphone"></i> +6287804616097</span>
                </div>
                <div class="col-md-4" style="padding: 3px;">
                    <span><i class="glyphicon glyphicon-envelope"></i> rapi-cakebakery@gmail.com</span>
                </div>
                <div class="col-md-4" style="padding: 3px;">
                    <span>rapi-cake bakery Indonesia</span>
                </div>
            </center>
        </div>
    </div>

    <nav class="navbar navbar-default" style="padding: 5px;">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ route('home') }}" style="color: #ff8680"><b>Rivina Bakery</b></a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('produk') }}">Products</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('manual') }}">User Guide</a></li>

                    @if(session()->has('kd_cs'))
                        @php
                            $cartCount = \App\Models\Keranjang::where('kode_customer', session('kd_cs'))->count();
                        @endphp
                        <li><a href="{{ route('cart.index') }}"><i class="glyphicon glyphicon-shopping-cart"></i> <b>[ {{ $cartCount }} ]</b></a></li>
                    @else
                        <li><a href="{{ route('cart.index') }}"><i class="glyphicon glyphicon-shopping-cart"></i> [0]</a></li>
                    @endif

                    @if(!session()->has('user'))
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-user"></i> Akun <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('login') }}">Login</a></li>
                                <li><a href="{{ route('register') }}">Register</a></li>
                            </ul>
                        </li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-user"></i> {{ session('user') }} <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('logout') }}">Log Out</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer style="border-top: 4px solid #ff8680; margin-top: 50px;">
        <div class="container" style="padding-bottom: 50px; padding-top: 20px;">
            <div class="row">
                <div class="col-md-4">
                    <h3 style="color: #ff8680"><b>Rivina Bakery</b></h3>
                    <p>Jl. Tanah Merah Indah 1 No.10C</p>
                    <p><i class="glyphicon glyphicon-earphone"></i> +6283123456789</p>
                    <p><i class="glyphicon glyphicon-envelope"></i> rivinabakery@gmail.com</p>
                </div>
                <div class="col-md-4">
                    <h5><b>Menu</b></h5>
                    <p><a href="{{ route('produk') }}" style="color: #000">Products</a></p>
                    <p><a href="{{ route('about') }}" style="color: #000">About Us</a></p>
                    <p><a href="{{ route('manual') }}" style="color: #000">User Guide</a></p>
                </div>
                <div class="col-md-4">
                </div>
            </div>
        </div>

        <div class="copy" style="background-color: #ff8680; padding: 5px; color: #fff; text-align: center;">
            <span>Copyright &copy; Rivina Bakery</span>
        </div>
    </footer>
</body>
</html>
