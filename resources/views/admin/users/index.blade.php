@extends('layouts.admin')

@section('page_title', 'Manajemen Pengguna')

@section('admin_content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pengguna</h3>
            {{-- Tidak ada tombol tambah user di sini karena registrasi adalah cara menambah user.
                 Jika admin perlu menambah user dari backend, Anda bisa buat rute/view create. --}}
        </div>
        <div class="card-body p-0">
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

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th style="width: 150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user) {{-- Pastikan loop ini ada --}}
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @forelse($user->getRoleNames() as $roleName)
                                <span class="badge badge-primary">{{ ucfirst($roleName) }}</span>
                            @empty
                                <span class="badge badge-secondary">Tidak ada peran</span>
                            @endforelse
                        </td>
                        <td>
                            @can('manage users')
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-info btn-xs">Edit</a>
                                @if (Auth::user()->id !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')" @disabled(Auth::user()->id === $user->id)>Hapus</button>
                                    </form>
                                @endif
                            @endcan
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada pengguna yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="card-footer clearfix">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@stop