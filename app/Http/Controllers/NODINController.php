<?php

namespace App\Http\Controllers;

use App\Models\IndexKegiatan;
use App\Models\PengajuanNODIN;
use App\Models\RincianBelanja;
use App\Models\SubKegiatan;
use App\Models\User;
use Illuminate\Http\Request;

class NODINController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($bagian)
    {
        $title = "Data Surat $bagian NODIN";

        $nodins = PengajuanNODIN::where('bagian', $bagian)
            ->latest()->get();

        return view('nodin.index', compact('title', 'bagian', 'nodins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($bagian)
    {
        $title = "Pengajuan Nota Dinas Bagian $bagian";

        $indexes = IndexKegiatan::all();
        $subkegiatans = SubKegiatan::all();
        $rincians = RincianBelanja::all();
        $staffs = User::where('tipe', 'Staff')->orderBy('nama', 'ASC')->get();

        return view('nodin.pengajuan', compact('title', 'bagian', 'indexes', 'subkegiatans', 'rincians', 'staffs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $bagian)
    {
        $validatedData = $request->validate([
            'index_kegiatan_id' => 'required|exists:index_kegiatan,id',
            'subkegiatan_id' => 'required|exists:subkegiatan,id',
            'rincian_belanja_id' => 'required|exists:rincian_belanja,id',
            'tanggal_pengajuan' => 'required|date',
            'perihal' => 'required|string',
            'tanggal_mulai' => 'required|date|before_or_equal:tanggal_selesai',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'atas_nama' => 'required|array|min:1|exclude',
            'nama_penginput' => 'required|string',
            'subject' => 'required|string',
        ], [
            'index_kegiatan_id.required' => 'Pilih index kegiatan terlebih dahulu.',
            'index_kegiatan_id.exists' => 'Index kegiatan yang dipilih tidak valid.',
            'subkegiatan_id.required' => 'Pilih sub kegiatan terlebih dahulu.',
            'subkegiatan_id.exists' => 'Sub kegiatan yang dipilih tidak valid.',
            'rincian_belanja_id.required' => 'Pilih rincian belanja terlebih dahulu.',
            'rincian_belanja_id.exists' => 'Rincian belanja yang dipilih tidak valid.',
            'perihal.required' => 'Perihal harus diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi.',
            'tanggal_mulai.before_or_equal' => 'Tanggal mulai harus sebelum atau sama dengan tanggal selesai.',
            'tanggal_selesai.required' => 'Tanggal selesai harus diisi.',
            'tanggal_selesai.before_or_equal' => 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',
            'atas_nama.required' => 'Atas nama harus diisi',
            'atas_nama.min' => 'Atas nama harus berisi minimal :value',
            'nama_penginput.required' => 'Nama penginput harus diisi.',
            'subject.required' => 'Subject harus diisi.',
        ]);

        try {
            $maxKode = PengajuanNODIN::where('tahun', date('Y'))->where('bagian', $bagian)->max('kode');
            $validatedData['kode'] = $maxKode ? $maxKode + 1 : 1;

            $validatedData['bagian'] = $bagian;
            $validatedData['atas_nama'] = join(', ', $request->atas_nama);
            $validatedData['nomor'] = "{$request->nomor_index}/" . $validatedData['kode'] . '/PPUU/050.4/' . date('Y');
            $create = PengajuanNODIN::create($validatedData);

            return redirect()->back()->with('pengajuan', $create->nomor);
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $bagian)
    {
        $title = "Detail Data Surat $bagian NODIN";
        $prev_url = $request->input('prev_url', null);

        $pengajuan = PengajuanNODIN::where('bagian', $bagian)->get();

        return view('nodin.detail', compact('title', 'bagian', 'pengajuan', 'prev_url'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($bagian, PengajuanNODIN $pengajuanNODIN)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $bagian, PengajuanNODIN $pengajuanNODIN)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
        ]);

        try {
            $pengajuanNODIN->update($validatedData);

            return redirect()->back()->with('success', 'Berhasil mengubah status pengajuan');
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function rekap(Request $request, $bagian)
    {
        $title = 'Rekap Nodin ' . $bagian;

        $staffs = User::where('tipe', 'Staff')->orderBy('nama')->get();
        $staff = $request->staff ?? '';

        return view('nodin.rekap', compact('title', 'bagian', 'staffs', 'staff'));
    }

    public function listRekap(Request $request, $bagian)
    {
        $start = date('Y-m-d', strtotime($request->start));
        $end = date('Y-m-d', strtotime($request->end));


        $nodins = PengajuanNODIN::where('tanggal_mulai', '>=', $start)
            ->where('tanggal_selesai', '<=', $end)
            ->where('bagian', $bagian)
            ->where('status', 'Disetujui');
        if ($request->staff !== '')
            $nodins->where('atas_nama', 'like', "%$request->staff%");
        $nodins = $nodins->get()
            ->map(fn ($item) => [
                'id' => $item->id,
                'title' => $item->subject,
                'start' => date(DATE_ISO8601, strtotime($item->tanggal_mulai)),
                'end' => date(DATE_ISO8601, strtotime($item->tanggal_selesai)),
                'nomor' => $item->nomor,
                'perihal' => $item->perihal,
                'an' => $item->atas_nama,
            ]);

        return response()->json($nodins);
    }
}
