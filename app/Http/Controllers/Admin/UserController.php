<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Pastikan model User diimport
use Spatie\Permission\Models\Role; // Pastikan model Role Spatie diimport
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate; // Opsional, jika ingin pakai Gate daripada middleware Spatie

class UserController extends Controller
{
    public function __construct()
    {
        // Hanya user dengan role 'admin' yang bisa mengakses seluruh controller ini.
        // Ini adalah lapisan keamanan utama untuk manajemen user.
        $this->middleware(['auth', 'role:admin']);

        // Anda juga bisa menggunakan permission spesifik jika Anda punya role lain yang hanya punya permission tertentu
        // $this->middleware(['auth', 'permission:manage users']);
    }

    public function index()
    {
        // Ambil semua user dengan pagination
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        // Ambil semua role yang tersedia untuk ditampilkan di form
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed', // Password opsional, bisa diubah
            'roles' => 'array', // Harus berupa array (checklist role)
            'roles.*' => 'exists:roles,name', // Pastikan setiap nama role yang dipilih ada di tabel roles
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        // Sinkronisasi peran pengguna
        // Jika $request->roles kosong, syncRoles akan menghapus semua peran
        $user->syncRoles($validated['roles'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui!');
    }

     public function destroy(User $user)
    {
        // Admin tidak boleh menghapus akunnya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}
