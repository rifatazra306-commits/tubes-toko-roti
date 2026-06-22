<!DOCTYPE html>
<html>
<head>
    <title>Rivina Cake & Bakery</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-theme.css') }}">
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <style>
.navbar-default .navbar-nav > li > a{
    color:#C8B273 !important;
}

.navbar-default .navbar-nav > li > a:hover{
    color:#E5C76B !important;
}

.navbar-default .navbar-nav > .open > a{
    background:#234131 !important;
    color:#D4AF37 !important;
}

.navbar-default .navbar-brand{
    color:#D4AF37 !important;
}
</style>
</head>
<body>
    <div class="container-fluid">
        <div class="row top" style="background:#1F3A2E;color:#C8B273;">
            <center>
                <div class="col-md-4" style="padding: 3px;">
                    <span> <i class="glyphicon glyphicon-earphone"></i> +6283169143507</span>
                </div>
                <div class="col-md-4" style="padding: 3px;">
                    <span><i class="glyphicon glyphicon-envelope"></i> rivinacakeandbakery@gmail.com</span>
                </div>
                <div class="col-md-4" style="padding: 3px;">
                    <span>Rivina Cake & Bakery</span>
                </div>
            </center>
        </div>
    </div>

    <nav class="navbar navbar-default"
     style="padding:5px;background:#1F3A2E;border:none;border-bottom:1px solid #D4AF37;">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
               <a class="navbar-brand"
   href="{{ route('home') }}"
   style="color:#D4AF37;font-weight:bold;"> RIVINA CAKE & BAKERY</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('produk') }}">Products</a></li>
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
                                <li><a href="{{ route('checkout.history') }}">Riwayat Belanja</a></li>
                                <li><a href="{{ route('logout') }}">Log Out</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer style="border-top:4px solid #D4AF37;background:#F8F5EE; margin-top: 50px;">
        <div class="container" style="padding-bottom: 50px; padding-top: 20px;">
            <div class="row">
                <div class="col-md-4">
                    <h3 style="color:#D4AF37"><b>Rivina Cake & Bakery</b></h3>
                    <p>Jl.Telekomunikasi No. 1, Terusan Buahbatu, Sukapura, Kec. Dayeuhkolot, Kabupaten Bandung, Jawa Barat 40257</p>
                    <p><i class="glyphicon glyphicon-earphone"></i> +6283169143507</p>
                    <p><i class="glyphicon glyphicon-envelope"></i> rivinacakeandbakery@gmail.com</p>
                </div>
                <div class="col-md-4">
                    <h5><b>Home</b></h5>
                    <p><a href="{{ route('produk') }}" style="color: #000">Products</a></p>
                    <p><a href="{{ route('manual') }}" style="color: #000">User Guide</a></p>
                </div>
                <div class="col-md-4">
                </div>
            </div>
        </div>

        <div class="copy"
     style="background:#1F3A2E;color:#C8B273;
            padding:5px;text-align:center;">
            <span>Copyright &copy; Rivina Cake & Bakery</span>
        </div>
    </footer>
</body>
</html>
