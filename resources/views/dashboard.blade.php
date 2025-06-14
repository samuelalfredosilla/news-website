@extends('layouts.admin')

@section('page_title', 'Dashboard')

@section('admin_content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Selamat Datang!</h3>
                </div>
                <div class="card-body">
                    <p>Anda telah berhasil masuk ke Dashboard Admin.</p>
                    <p>Role Anda saat ini: {{ Auth::user()->getRoleNames()->implode(', ') }}</p>
                </div>
            </div>
        </div>
    </div>
@stop