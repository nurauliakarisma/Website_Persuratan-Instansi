<?php

namespace App\Http\Controllers;

use App\Models\IndexKegiatan;
use Illuminate\Http\Request;

class IndexKegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Index Kegiatan';

        $indexes = IndexKegiatan::latest()->get();

        return view('master.index_kegiatan', compact('title', 'indexes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode' => 'required',
            'keterangan' => 'required',
        ]);

        try {
            IndexKegiatan::create($validatedData);

            return redirect()->back()->with('success', 'Berhasil menambahkan data index kegiatan.');
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(IndexKegiatan $indexKegiatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IndexKegiatan $indexKegiatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IndexKegiatan $indexKegiatan)
    {
        $validatedData = $request->validate([
            'kode' => 'required',
            'keterangan' => 'required',
        ]);

        try {
            $indexKegiatan->update($validatedData);

            return redirect()->route('master.index-kegiatan.index')->with('success', 'Berhasil mengubah data index kegiatan.');
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IndexKegiatan $indexKegiatan)
    {
        try {
            $indexKegiatan->delete();

            return redirect()->back()->with('success', 'Berhasil menghapus data index kegiatan.');
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }
}
