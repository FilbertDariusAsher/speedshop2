<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $users = User::orderByDesc('created_at')->get();
        return view('admin.karyawan', compact('users'));
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,owner,karyawan',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'active' => true,
        ]);

        return redirect()->route('admin.karyawan.index')->with('success', 'Akun karyawan berhasil dibuat');
    }

    public function updateStatus(Request $request, $id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat mengubah status akun sendiri.');
        }

        $user->active = !$user->active;
        $user->save();

        return back()->with('success', 'Status pengguna berhasil diperbarui');
    }

    public function destroy($id)
    {
        if (!in_array(auth()->user()->role, ['admin', 'owner'])) {
            abort(403);
        }

        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return back()->with('success', 'Akun berhasil dihapus');
    }
}
