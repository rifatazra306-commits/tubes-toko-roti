<!DOCTYPE html>
<html>
<head>
    <title>Rivina Cake & Bakery - Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-theme.css') }}">
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
</head>
<body>
    <nav class="navbar navbar-default" style="padding: 5px;">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ route('admin.dashboard') }}"><b>RIVINA CAKE & BAKERY ADMIN</b></a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-left">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-folder-close"></i> Data Master <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('admin.produk.index') }}">Master Produk</a></li>
                            <li><a href="{{ route('admin.customer.index') }}">Master Customer</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-retweet"></i> Data Transaksi <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('admin.produksi.index') }}">Produksi</a></li>
                            <li><a href="{{ route('admin.inventory.index') }}">Inventory</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-stats"></i> Laporan <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('admin.report.penjualan') }}">Laporan Penjualan</a></li>
                            <li><a href="{{ route('admin.report.profit') }}">Laporan Profit</a></li>
                            <li><a href="{{ route('admin.report.omset') }}">Laporan Omset</a></li>
                            <li><a href="{{ route('admin.report.pembatalan') }}">Laporan Pembatalan</a></li>
                            <li><a href="{{ route('admin.report.inventory') }}">Laporan Inventory</a></li>
                            <li><a href="{{ route('admin.report.produksi') }}">Laporan Produksi</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-user"></i> Admin <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('admin.logout') }}">Log Out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container" style="min-height: 500px;">
        @yield('content')
    </div>

    <footer style="background-color: gray; padding: 10px; margin-top: 50px;" class="print">
        <h5 class="text-center" style="color: white; margin: 0;">Copyright &copy; Ahmad Rafi Akbar Putra Hamzah</h5>
    </footer>
</body>
</html>
