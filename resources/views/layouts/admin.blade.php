@extends('adminlte::page')

@section('title', 'Admin Panel')

@section('content_header')
    <h1>@yield('page_title')</h1>
@stop

@section('content')
    @yield('admin_content')
@stop

@section('css')
    {{-- Custom CSS here if needed --}}
@stop

@section('js')
    {{-- Custom JS here if needed --}}
@stop