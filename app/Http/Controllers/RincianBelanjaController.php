<?php

namespace App\Http\Controllers;

use App\Models\RincianBelanja;
use Illuminate\Http\Request;

class RincianBelanjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Rincian Kegiatan';

        $rincians = RincianBelanja::latest()->get();

        return view('master.rincian_belanja', compact('title', 'rincians'));
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
            'kode_rekening' => 'required|string',
            'keterangan' => 'required|string',
        ]);

        try {
            RincianBelanja::create($validatedData);

            return redirect()->route('master.rincian-belanja.index')->with('success', "Berhasil menambahkan data rincian belanja.");
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RincianBelanja $rincianBelanja)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RincianBelanja $rincianBelanja)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RincianBelanja $rincianBelanja)
    {
        $validatedData = $request->validate([
            'keterangan' => 'required|string',
        ]);

        try {
            $rincianBelanja->update($validatedData);

            return redirect()->route('master.rincian-belanja.index')->with('success', "Berhasil mengubah data rincian belanja.");
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RincianBelanja $rincianBelanja)
    {
        try {
            $rincianBelanja->delete();

            return redirect()->back()->with('success', 'Berhasil menghapus data rincian belanja.');
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }
}
