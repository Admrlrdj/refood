<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan halaman data Admin
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.pages.users.index', compact('users'));
    }

    // Menyimpan Admin baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Akun Admin berhasil ditambahkan!');
    }

    // Mengupdate Admin
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id . ',_id',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Update password HANYA jika form diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Data Admin berhasil diperbarui!');
    }

    // Menghapus Admin
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth()->user()->_id == $id) {
            return redirect()->back()->withErrors(['Tidak dapat menghapus akun Anda sendiri saat sedang login.']);
        }

        $user->delete();
        return redirect()->back()->with('success', 'Akun Admin berhasil dihapus!');
    }
}
