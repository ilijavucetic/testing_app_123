@extends('layouts.master')

@section('title')
    Adminstracija
@stop

@section('content')
    @include('includes.message-block')

    <div class="list-group">
        <a href="{{ route('add_category') }}" class="list-group-item">
           Kategorije
        </a>
        <a href="{{ route('add_product') }}" class="list-group-item">Proizvodi</a>
        <a href="#" class="list-group-item">Korisnici</a>
    </div>

    <script>
        var token = '{{ Session::token() }}';
    </script>
@endsection