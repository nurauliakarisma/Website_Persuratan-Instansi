<?php

namespace App\Http\Controllers;

use App\Models\AlokasiNPD;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';

        $npds = AlokasiNPD::with([
            'subKegiatan:id,kode_subkegiatan',
            'rincianBelanja:id,kode_rekening',
        ])
            ->latest()
            ->get();

        if (Auth::user()->tipe !== 'Super Admin') {
            $bagian = explode(' ', Auth::user()->tipe)[1];
            $npds = $npds->where('bagian', $bagian);
        }

        $npds->loadSum(['pengajuan as realisasi' => function ($query) {
            $query->where('status', 'Disetujui');
        }], 'anggaran');

        return view('home', compact('title', 'npds'));
    }

    public function changePassword()
    {
        $title = 'Ubah Password';
        return view('change-password', compact('title'));
    }

    public function changedPassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required',
        ], [
            'new_password.confirmed' => 'Password baru dan Konfirmasi tidak sama.'
        ]);

        try {
            $newPassword = Hash::make($request->new_password);
            User::where('id', Auth::id())->update(['password' => $newPassword]);

            return redirect()->route('dashboard')->with('success', 'Berhasil mengubah password');
        } catch (\Throwable $err) {
            return back()->with('error', $err->getMessage());
        }
    }
}
