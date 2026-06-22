@extends('layouts.admin')

@section('content')
<div class="container" style="padding-top: 30px; padding-bottom: 150px;">
    <h2 style="width: 100%; border-bottom: 4px solid gray"><b>Data Customer</b></h2>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Kode Customer</th>
                <th scope="col">Nama</th>
                <th scope="col">Email</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($customers as $row)
                <tr>
                    <th scope="row">{{ $no }}</th>
                    <td>{{ $row->kode_customer }}</td>
                    <td>{{ $row->nama }}</td>
                    <td>{{ $row->email }}</td>
                </tr>
                @php $no++; @endphp
            @endforeach
        </tbody>
    </table>
</div>
@endsection
