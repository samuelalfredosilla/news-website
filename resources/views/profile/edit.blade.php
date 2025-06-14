@extends('layouts.admin')

@section('page_title', 'Edit Profil')

@section('admin_content')
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Informasi Profil</h3>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Perbarui Kata Sandi</h3>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>

    {{-- Opsional: Hapus Akun (hati-hati dengan fitur ini) --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card card-danger card-outline">
                <div class="card-header">
                    <h3 class="card-title">Hapus Akun</h3>
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@stop