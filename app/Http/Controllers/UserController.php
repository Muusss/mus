<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $title = 'Data User';
        $users = User::orderBy('role', 'asc')
                     ->orderBy('kelas', 'asc')
                     ->orderBy('name', 'asc')
                     ->get();
        
        $kelasList = ['6A', '6B', '6C', '6D'];
        
        return view('dashboard.users.index', compact('title', 'users', 'kelasList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['admin', 'wali_kelas'])],
            'kelas' => ['nullable', Rule::in(['6A', '6B', '6C', '6D'])],
        ]);

        // Jika role admin, kelas harus null
        if ($data['role'] === 'admin') {
            $data['kelas'] = null;
        } elseif ($data['role'] === 'wali_kelas' && empty($data['kelas'])) {
            return back()->with('error', 'Wali kelas harus memiliki kelas yang ditangani');
        }

        $data['password'] = Hash::make($data['password']);
        
        User::create($data);

        return redirect()->route('users')->with('success', 'User berhasil ditambahkan');
    }

    public function edit(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        return response()->json($user);
    }

    public function update(Request $request)
    {
        $user = User::findOrFail($request->id);
        
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 
                        Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'wali_kelas'])],
            'kelas' => ['nullable', Rule::in(['6A', '6B', '6C', '6D'])],
        ]);

        // Jika password diisi, update password
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['string', 'min:6']
            ]);
            $data['password'] = Hash::make($request->password);
        }

        // Jika role admin, kelas harus null
        if ($data['role'] === 'admin') {
            $data['kelas'] = null;
        } elseif ($data['role'] === 'wali_kelas' && empty($data['kelas'])) {
            return back()->with('error', 'Wali kelas harus memiliki kelas yang ditangani');
        }

        $user->update($data);

        return redirect()->route('users')->with('success', 'User berhasil diperbarui');
    }

    public function delete(Request $request)
    {
        $user = User::findOrFail($request->id);
        
        // Jangan hapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri');
        }

        // Cek jika ini adalah admin terakhir
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Tidak dapat menghapus admin terakhir');
            }
        }

        $user->delete();

        return redirect()->route('users')->with('success', 'User berhasil dihapus');
    }

    public function resetPassword(Request $request)
    {
        $user = User::findOrFail($request->id);
        
        // Set password default
        $defaultPassword = 'password123';
        $user->password = Hash::make($defaultPassword);
        $user->save();

        return back()->with('success', 
            "Password untuk {$user->name} telah direset menjadi: {$defaultPassword}");
    }
}