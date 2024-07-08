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

        return view('media.detail', compact('title', 'pengajuan', 'prev_url'));
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
        $validatedData  = $request->validate([
            'media_id' => 'required|exists:media,id',
            'tanggal_tayang' => 'required|date',
            'nominal_publikasi' => 'required',
            'nominal_fotocopy' => 'required',
            'judul' => 'required',
        ]);

        try {
            PengajuanPublikasi::create($validatedData);

            return redirect()->back()->with('success', 'Berhasil melakukan pengajuan.');
        } catch (\Throwable $err) {
            return back()->withInput()->with('error', $err->getMessage());
        }
    }
}
