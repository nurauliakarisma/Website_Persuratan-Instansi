<?php

namespace App\Http\Controllers;

use App\Models\SubKegiatan;
use Illuminate\Http\Request;

class SubKegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Sub Kegiatan';

        $subs = SubKegiatan::latest()->get();

        return view('master.subkegiatan', compact('title', 'subs'));
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
            'kode_program' => 'required|string',
            'ket_program' => 'required|string',
            'kode_kegiatan' => 'required|string',
            'ket_kegiatan' => 'required|string',
            'kode_subkegiatan' => 'required|string',
            'ket_subkegiatan' => 'required|string',
        ]);

        try {
            SubKegiatan::create($validatedData);

            return redirect()->route('master.sub-kegiatan.index')->with('success', "Berhasil menambahkan data sub kegiatan.");
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SubKegiatan $subKegiatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubKegiatan $subKegiatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubKegiatan $subKegiatan)
    {
        $validatedData = $request->validate([
            'ket_program' => 'required|string',
            'ket_kegiatan' => 'required|string',
            'ket_subkegiatan' => 'required|string',
        ]);

        try {
            $subKegiatan->update(
                [
                    'ket_program' => $validatedData['ket_program'],
                    'ket_kegiatan' => $validatedData['ket_kegiatan'],
                    'ket_subkegiatan' => $validatedData['ket_subkegiatan']
                ]
            );

            return redirect()->route('master.sub-kegiatan.index')->with('success', "Berhasil mengubah data sub kegiatan.");
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubKegiatan $subKegiatan)
    {
        try {
            $subKegiatan->delete();

            return redirect()->back()->with('success', 'Berhasil menghapus data sub kegiatan.');
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }
}
