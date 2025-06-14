@extends('layouts.admin')

@section('page_title', 'Edit Pengguna: ' . $user->name)

@section('admin_content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Edit Detail Pengguna</h3>
        </div>
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label for="name">Nama:</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password Baru (kosongkan jika tidak ingin diubah):</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password Baru:</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>

                <div class="form-group">
                    <label>Peran (Roles):</label><br>
                    @foreach($roles as $role)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="roles[]" id="role-{{ $role->id }}" value="{{ $role->name }}"
                                {{ in_array($role->name, $user->getRoleNames()->toArray()) ? 'checked' : '' }}
                                {{ $user->id === Auth::user()->id && $role->name === 'admin' ? 'disabled' : '' }} {{-- Admin tidak bisa menghapus peran 'admin' dari dirinya sendiri --}}>
                            <label class="form-check-label" for="role-{{ $role->id }}">{{ ucfirst($role->name) }}</label>
                        </div>
                    @endforeach
                    @error('roles')
                        <small class="text-danger d-block"><strong>{{ $message }}</strong></small>
                    @enderror
                    @error('roles.*')
                        <small class="text-danger d-block"><strong>{{ $message }}</strong></small>
                    @enderror
                    @if ($user->id === Auth::user()->id)
                        <small class="text-info d-block">Anda tidak dapat mengubah peran 'admin' pada akun Anda sendiri untuk menghindari terkunci dari panel admin.</small>
                    @endif
                </div>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@stop