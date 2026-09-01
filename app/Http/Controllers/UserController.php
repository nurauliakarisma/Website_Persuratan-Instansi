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
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'tipe' => 'required|in:Super Admin,Admin A,Admin B',
            'nip' => 'nullable|string|unique:users,nip',
            'jabatan' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ], [
            'nama.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Masukkan email yang valid.',
            'email.unique' => 'Email yang dimasukkan sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'tipe.required' => 'Tipe / role admin harus dipilih.',
            'tipe.in' => 'Tipe admin tidak valid.',
            'nip.unique' => 'NIP sudah terdaftar untuk pengguna lain.',
            'photo.image' => 'Photo harus berupa file gambar.',
            'photo.mimes' => 'Photo harus berformat PNG, JPG, atau JPEG.',
            'photo.max' => 'Ukuran photo maksimal 2MB.',
        ]);

        try {
            if ($request->hasFile('photo')) {
                $validatedData['photo'] = $request->file('photo')->store('photo-profile', 'public');
            }

            $validatedData['password'] = Hash::make($validatedData['password']);
            User::create($validatedData);

            return redirect()->back()->with('success', 'Berhasil menambahkan admin baru.');
        } catch (\Throwable $err) {
            if (! empty($validatedData['photo'])) {
                Storage::disk('public')->delete($validatedData['photo']);
            }

            return back()->withInput()->with('error', 'Gagal menambahkan admin: '.$err->getMessage());
        }
    }

    public function updateAdmin(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'new_password' => 'nullable|min:6',
            'tipe' => 'required|in:Super Admin,Admin A,Admin B',
            'nip' => 'nullable|string|unique:users,nip,'.$user->id,
            'jabatan' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ], [
            'nama.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Masukkan email yang valid.',
            'email.unique' => 'Email sudah terdaftar untuk pengguna lain.',
            'new_password.min' => 'Password minimal 6 karakter.',
            'tipe.required' => 'Tipe / role admin harus dipilih.',
            'tipe.in' => 'Tipe admin tidak valid.',
            'nip.unique' => 'NIP sudah terdaftar untuk pengguna lain.',
            'photo.image' => 'Photo harus berupa file gambar.',
            'photo.mimes' => 'Photo harus berformat PNG, JPG, atau JPEG.',
            'photo.max' => 'Ukuran photo maksimal 2MB.',
        ]);

        try {
            if ($request->hasFile('photo')) {
                $validatedData['photo'] = $request->file('photo')->store('photo-profile', 'public');
                if ($user->photo) {
                    Storage::disk('public')->delete($user->photo);
                }
            }

            if (! empty($request->new_password) && trim($request->new_password) != '') {
                $validatedData['password'] = Hash::make($request->new_password);
            }
            unset($validatedData['new_password']);

            $user->update($validatedData);

            return redirect()->back()->with('success', 'Berhasil mengubah data admin.');
        } catch (\Throwable $err) {
            if (! empty($validatedData['photo'])) {
                Storage::disk('public')->delete($validatedData['photo']);
            }

            return back()->withInput()->with('error', 'Gagal mengubah data admin: '.$err->getMessage());
        }
    }

    public function deleteAdmin(User $user)
    {
        try {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }

            $user->delete();

            return redirect()->back()->with('success', 'Berhasil menghapus data admin');
        } catch (\Throwable $err) {
            return back()->with('error', 'Gagal menghapus admin: '.$err->getMessage());
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
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nip' => 'nullable|string|unique:users,nip',
            'jabatan' => 'nullable|string|max:255',
        ], [
            'nama.required' => 'Nama lengkap staf harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Masukkan email yang valid.',
            'email.unique' => 'Email yang dimasukkan sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'nip.unique' => 'NIP sudah terdaftar untuk pengguna lain.',
        ]);

        try {
            $validatedData['tipe'] = 'Staff';
            $validatedData['password'] = Hash::make($validatedData['password']);
            User::create($validatedData);

            return redirect()->back()->with('success', 'Berhasil menambahkan staff baru.');
        } catch (\Throwable $err) {
            return back()->withInput()->with('error', 'Gagal menambahkan staff: '.$err->getMessage());
        }
    }

    public function updateStaff(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'new_password' => 'nullable|min:6',
            'nip' => 'nullable|string|unique:users,nip,'.$user->id,
            'jabatan' => 'nullable|string|max:255',
        ], [
            'nama.required' => 'Nama lengkap staf harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Masukkan email yang valid.',
            'email.unique' => 'Email sudah terdaftar untuk pengguna lain.',
            'new_password.min' => 'Password minimal 6 karakter.',
            'nip.unique' => 'NIP sudah terdaftar untuk pengguna lain.',
        ]);

        try {
            if (! empty($request->new_password) && trim($request->new_password) != '') {
                $validatedData['password'] = Hash::make($request->new_password);
            }
            unset($validatedData['new_password']);

            $user->update($validatedData);

            return redirect()->back()->with('success', 'Berhasil mengubah data staff.');
        } catch (\Throwable $err) {
            return back()->withInput()->with('error', 'Gagal mengubah data staff: '.$err->getMessage());
        }
    }

    public function deleteStaff(User $user)
    {
        try {
            $user->delete();

            return redirect()->back()->with('success', 'Berhasil menghapus data staff');
        } catch (\Throwable $err) {
            return back()->with('error', 'Gagal menghapus data staff: '.$err->getMessage());
        }
    }
}
