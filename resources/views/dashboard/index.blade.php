@extends('layouts.dashboard')

@section('title')
    Selamat Datang Di Aplikasi Pengaduan Masyarakat
@endsection



@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Selamat datang {{Auth::user()->name}}</h2>
        </div>
    </div>
@endsection