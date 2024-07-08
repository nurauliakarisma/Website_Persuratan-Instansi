<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Jika autentikasi berhasil
            $user = Auth::user();

            // Periksa peran pengguna
            if ($user['tipe'] != 'Staff') {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('menu.index');
            }
        }

        // Jika autentikasi gagal atau peran tidak ditemukan, kembalikan ke halaman login
        return back()->withInput()->with('error', 'Email atau Password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function createAdmin()
    {
        $title = 'Tambah Admin';

        $admins = User::where('tipe', '!=', 'Staff')
            ->orderBy('nama', 'ASC')
            ->get();

        return view('admin.tambah', compact('title', 'admins'));
    }

    public function storeAdmin(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'tipe' => 'required|in:Super Admin,Admin A,Admin B',
            'photo' => 'nullable|image|mimes:png,jpg,jpeg|exclude',
        ], [
            'nama.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Masukkan email yang valid.',
            'email.unique' => 'Email yang dimasukkan sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 character.',
            'tipe.required' => 'Tipe harus dipilih.',
            'tipe.in' => 'Tipe harus bernilai :value.',
            'photo.image' => 'Photo harus berupa image file.',
            'photo.mimes' => 'Photo harus berekstensi file :value',
        ]);

        try {
            if ($request->hasFile('photo')) {
                $validatedData['photo'] = $request->file('photo')->store('photo-profile');
            }

            $validatedData['password'] = Hash::make($validatedData['password']);

            User::create($validatedData);

            return redirect()->back()->with('success', 'Berhasil menambahkan admin baru.');
        } catch (\Throwable $err) {
            if ($validatedData['photo'] != '') Storage::delete($validatedData['photo']);
            return back()->withInput()->with('error', $err->getMessage());
        }
    }

    public function updateAdmin(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'new_password' => 'nullable|min:8||exclude',
            'tipe' => 'required|in:Super Admin,Admin A,Admin B',
            'photo' => 'nullable|image|mimes:png,jpg,jpeg|exclude',
        ], [
            'nama.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Masukkan email yang valid.',
            'new_password.min' => 'Password minimal 8 character.',
            'tipe.required' => 'Tipe harus dipilih.',
            'tipe.in' => 'Tipe harus bernilai :value.',
            'photo.image' => 'Photo harus berupa image file.',
            'photo.mimes' => 'Photo harus berekstensi file :value',
        ]);

        try {
            if ($request->hasFile('photo')) {
                $validatedData['photo'] = $request->file('photo')->store('photo-profile');
                // Delete old file jika tidak null
                if ($user->photo) Storage::delete($user->photo);
            }

            if ($request->new_password && trim($request->new_password) != "")
                $validatedData['password'] = Hash::make($request->new_password);

            $user->update($validatedData);

            return redirect()->back()->with('success', 'Berhasil mengubah data admin.');
        } catch (\Throwable $err) {
            if ($validatedData['photo'] != '') Storage::delete($validatedData['photo']);
            return back()->withInput()->with('error', $err->getMessage());
        }
    }

    public function deleteAdmin(User $user)
    {
        try {
            if ($user->photo) {
                Storage::delete($user->photo);
            }

            $user->delete();

            return redirect()->back()->with('success', 'Berhasil menghapus data admin');
        } catch (\Throwable $err) {
            return back()->with('error', $err->getMessage());
        }
    }

    public function createStaff()
    {
        $title = 'Pendataan Staff';

        $staffs = User::where('tipe', 'Staff')
            ->orderBy('nama', 'ASC')
            ->get();

        return view('staff.tambah', compact('title', 'staffs'));
    }

    public function storeStaff(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'nip' => 'required|string',
            'jabatan' => 'required|string',
        ], [
            'nama.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Masukkan email yang valid.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 character.',
            'nip.required' => 'NIP harus diisi.',
            'jabatan.required' => 'Jabatan harus diisi.',
        ]);

        try {
            $validatedData['password'] = Hash::make($validatedData['password']);
            User::create($validatedData);

            return redirect()->back()->with('success', 'Berhasil menambahkan staff baru.');
        } catch (\Throwable $err) {
            return back()->withInput()->with('error', $err->getMessage());
        }
    }

    public function updateStaff(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string',
            'email' => 'required|email',
            'new_password' => 'nullable|min:8|exclude',
            'nip' => 'required|string',
            'jabatan' => 'required|string',
        ], [
            'nama.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Masukkan email yang valid.',
            'new_password.min' => 'Password minimal 8 character.',
            'nip.required' => 'NIP harus diisi.',
            'jabatan.required' => 'Jabatan harus diisi.',
        ]);

        try {
            if ($request->new_password && trim($request->new_password) != "")
                $validatedData['password'] = Hash::make($request->new_password);

            $user->update($validatedData);

            return redirect()->back()->with('success', 'Berhasil mengubah data staff.');
        } catch (\Throwable $err) {
            return back()->withInput()->with('error', $err->getMessage());
        }
    }

    public function deleteStaff(User $user)
    {
        try {
            $user->delete();

            return redirect()->back()->with('success', 'Berhasil menghapus data staff');
        } catch (\Throwable $err) {
            return back()->with('error', $err->getMessage());
        }
    }
}
