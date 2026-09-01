<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\PengajuanPublikasi;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Media';

        $medias = Media::latest()->get();

        return view('media.index', compact('title', 'medias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Pengajuan Publikasi Media';

        $medias = Media::orderBy('nama', 'ASC')->get();

        return view('media.pengajuan', compact('title', 'medias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'harga_penawaran' => 'nullable|numeric',
            'harga_deal' => 'nullable|numeric',
            'harga_total' => 'nullable|numeric',
            'status' => 'nullable|string',
        ], [
            'nama.required' => 'Nama harus diisi.',
            'harga_penawaran' => 'Harga penawaran harus berupa angka.',
            'harga_deal' => 'Harga deal harus berupa angka.',
            'harga_total' => 'Harga + PPN harus berupa angka.',
            'status.required' => 'Keterangan harus diisi.',
        ]);

        try {
            Media::create($validatedData);

            return redirect()->back()->with('success', 'Berhasil menambahkan media.');
        } catch (\Throwable $err) {
            return back()->with('success', $err->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $title = 'Publikasi Media';
        $prev_url = $request->input('prev_url', null);

        $pengajuan = PengajuanPublikasi::latest()->get();
        $medias = Media::orderBy('nama', 'ASC')->get();

        return view('media.detail', compact('title', 'pengajuan', 'prev_url', 'medias'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Media $medium)
    {
        $validatedData = $request->validate([
            'nama' => 'required',
            'harga_penawaran' => 'nullable|numeric',
            'harga_deal' => 'nullable|numeric',
            'harga_total' => 'nullable|numeric',
            'status' => 'nullable|string',
        ], [
            'nama.required' => 'Nama harus diisi.',
            'harga_penawaran' => 'Harga penawaran harus berupa angka.',
            'harga_deal' => 'Harga deal harus berupa angka.',
            'harga_total' => 'Harga + PPN harus berupa angka.',
            'status.required' => 'Keterangan harus diisi.',
        ]);

        try {
            $medium->update($validatedData);

            return redirect()->back()->with('success', 'Berhasil menguabah data media.');
        } catch (\Throwable $err) {
            return back()->with('success', $err->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Media $medium)
    {
        try {
            $medium->delete();

            return redirect()->back()->with('success', 'Berhasil menghapus data media.');
        } catch (\Throwable $err) {
            return back()->with('error', $err->getMessage());
        }
    }

    public function pengajuanStore(Request $request)
    {
        $validatedData = $request->validate([
            'media_id' => 'required|exists:media,id',
            'tanggal_tayang' => 'required|date',
            'nominal_publikasi' => 'required|numeric',
            'nominal_fotocopy' => 'required|numeric',
            'judul' => 'required|string',
        ]);

        $validatedData['nama_penginput'] = auth()->user()->nama ?? 'Staff';
        $validatedData['status'] = 'Diajukan';

        try {
            PengajuanPublikasi::create($validatedData);

            return redirect()->back()->with('success', 'Berhasil melakukan pengajuan.');
        } catch (\Throwable $err) {
            return back()->withInput()->with('error', $err->getMessage());
        }
    }

    public function approvePengajuan(Request $request, PengajuanPublikasi $pengajuanPublikasi)
    {
        $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
            'catatan_penolakan' => 'nullable|string',
        ]);

        try {
            $pengajuanPublikasi->update([
                'status' => $request->status,
                'catatan_penolakan' => $request->status === 'Ditolak' ? $request->catatan_penolakan : null,
            ]);

            $message = $request->status === 'Disetujui' ? 'Pengajuan publikasi berhasil disetujui.' : 'Pengajuan publikasi berhasil ditolak.';

            return redirect()->back()->with('success', $message);
        } catch (\Throwable $err) {
            return redirect()->back()->with('error', 'Gagal memperbarui status pengajuan: '.$err->getMessage());
        }
    }

    public function resubmitPengajuan(Request $request, PengajuanPublikasi $pengajuanPublikasi)
    {
        $currentUser = auth()->user();
        if (($currentUser->tipe ?? '') !== 'Staff' || (($currentUser->nama ?? '') !== ($pengajuanPublikasi->nama_penginput ?? '') && ! empty($pengajuanPublikasi->nama_penginput))) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk memperbaiki pengajuan ini. Hanya staf yang mengajukan yang dapat melakukan perbaikan.');
        }

        $validatedData = $request->validate([
            'media_id' => 'required|exists:media,id',
            'tanggal_tayang' => 'required|date',
            'nominal_publikasi' => 'required|numeric',
            'nominal_fotocopy' => 'required|numeric',
            'judul' => 'required|string',
        ]);

        try {
            $validatedData['status'] = 'Diajukan';
            $validatedData['catatan_penolakan'] = null;

            $pengajuanPublikasi->update($validatedData);

            return redirect()->back()->with('success', 'Pengajuan publikasi berhasil diperbaiki dan diajukan ulang.');
        } catch (\Throwable $err) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengajukan ulang publikasi: '.$err->getMessage());
        }
    }
}
