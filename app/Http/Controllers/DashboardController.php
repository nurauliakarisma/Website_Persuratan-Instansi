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

        $query = AlokasiNPD::with([
            'subKegiatan',
            'rincianBelanja',
        ])->latest();

        if (str_starts_with(Auth::user()->tipe, 'Admin ')) {
            $parts = explode(' ', Auth::user()->tipe);
            if (isset($parts[1])) {
                $query->where('bagian', $parts[1]);
            }
        }

        $npds = $query->get();

        $npds->loadSum(['pengajuan as realisasi' => function ($q) {
            $q->where('status', 'Disetujui');
        }], 'anggaran');

        $totalAnggaran = $npds->sum('total_anggaran');
        $totalRealisasi = $npds->sum(function ($item) {
            return $item->realisasi ?? 0;
        });
        $totalSisa = max(0, $totalAnggaran - $totalRealisasi);
        $persenRealisasiTotal = $totalAnggaran > 0 ? round(($totalRealisasi / $totalAnggaran) * 100, 2) : 0;
        $persenSisaTotal = $totalAnggaran > 0 ? round(($totalSisa / $totalAnggaran) * 100, 2) : 0;

        return view('home', compact(
            'title',
            'npds',
            'totalAnggaran',
            'totalRealisasi',
            'totalSisa',
            'persenRealisasiTotal',
            'persenSisaTotal'
        ));
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
            'new_password.confirmed' => 'Password baru dan Konfirmasi tidak sama.',
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
