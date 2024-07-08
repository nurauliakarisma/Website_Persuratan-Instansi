<?php

namespace App\Http\Controllers;

use App\Models\AlokasiNPD;
use App\Models\PengajuanNPD;
use App\Models\RincianBelanja;
use App\Models\SubKegiatan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class NPDController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($bagian)
    {
        $current_url = route(Route::current()->getName(), ['bagian' => $bagian]);
        $title = "Data Surat $bagian NPD";

        $alokasi_npd = AlokasiNPD::where('bagian', $bagian)
            ->latest()
            ->get();

        $alokasi_npd->loadSum(['pengajuan as realisasi' => function ($query) {
            $query->where('status', 'Disetujui');
        }], 'anggaran');

        return view('npd.index', compact('title', 'bagian', 'alokasi_npd', 'current_url'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($bagian)
    {
        $title = 'Tambah Alokasi NPD';

        $subs = SubKegiatan::latest()->get();
        $rincians = RincianBelanja::latest()->get();

        return view('npd.tambah', compact('title', 'bagian', 'subs', 'rincians'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $bagian)
    {
        $validatedData = $request->validate([
            'subkegiatan_id' => 'required|exists:subkegiatan,id',
            'rincian_belanja_id' => 'required|exists:rincian_belanja,id',
            'anggaran' => 'required|numeric|gt:0|exclude',
        ], [
            'subkegiatan_id.required' => 'Pilih Sub Kegiatan yang valid',
            'subkegiatan_id.exists' => 'Sub Kegiatan tidak ditemukan',
            'rincian_belanja_id.required' => 'Pilih Rincian Belanja yang valid',
            'rincian_belanja_id.exists' => 'Rincian Belanja tidak ditemukan',
            'anggaran.required' => 'Anggaran harus diisi.',
            'anggaran.numeric' => 'Anggaran harus berupa angka.',
            'anggaran.gt' => 'Anggaran harus lebih dari :value.',
        ]);

        $validatedData['bagian'] = $bagian;
        $validatedData['total_anggaran'] = $request->anggaran;

        try {
            AlokasiNPD::create($validatedData);

            return redirect()->route('npd.index', ['bagian' => $bagian])->with('success', "Data surat $bagian NPD berhasil ditambahkan.");
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $bagian, $alokasi = null)
    {
        $title = "Detail Data Surat $bagian NPD";
        $prev_url = $request->input('prev_url', null);

        $pengajuan = PengajuanNPD::whereHas('alokasi', function (Builder $query) use ($bagian) {
            $query->where('bagian', $bagian);
        });
        if ($alokasi) $pengajuan = $pengajuan->where('alokasi_npd_id', $alokasi);
        $pengajuan = $pengajuan->get();

        return view('npd.detail', compact('title', 'prev_url', 'pengajuan', 'bagian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($bagian, AlokasiNPD $alokasiNPD)
    {
        $title = 'Edit Alokasi NPD';

        return view('npd.edit', compact('title', 'bagian', 'alokasiNPD'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $bagian, AlokasiNPD $alokasiNPD)
    {
        $request->validate([
            'anggaran' => 'required|numeric|gt:0'
        ]);

        try {
            $alokasiNPD->update(['total_anggaran' => $request->anggaran]);

            return redirect()->route('npd.index', ['bagian' => $bagian])->with('success', 'Berhasil mengubah data NPD');
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($bagian, AlokasiNPD $alokasiNPD)
    {
        try {
            $alokasiNPD->delete();

            return redirect()->route('npd.index', ['bagian' => $bagian])->with('success', 'Berhasil menghapus data NPD');
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }

    public function createPengajuan($bagian)
    {
        $title = 'Ajukan NPD bagian ' . $bagian;
        $npds = AlokasiNPD::where('bagian', $bagian)
            ->latest()
            ->get();

        $npds->loadSum(['pengajuan as realisasi' => function ($query) {
            $query->where('status', 'Disetujui');
        }], 'anggaran');

        return view('npd.pengajuan', compact('title', 'bagian', 'npds'));
    }

    public function storePengajuan(Request $request, $bagian)
    {
        if ($request->anggaran > $request->sisa) return back()->withInput()->with('error', 'Anggaran yang ingin diajukan melebihi batas anggaran.');

        $validatedData = $request->validate([
            'alokasi_npd_id' => 'required|integer|exists:alokasi_npd,id',
            'tanggal_pengajuan' => 'required|date',
            'uraian_kegiatan' => 'required|string',
            'anggaran' => 'required|numeric|gt:0',
        ], [
            'alokasi_npd_id.required' => 'Pilih NPD yang ingin diajukan terlebih dahulu.',
            'alokasi_npd_id.exists' => 'NPD yang dipilih tidak valid.',
            'uraian_kegiatan.required' => 'Uraian Kegiatan harus diisi.',
            'anggaran.required' => 'Anggaran harus diisi.',
            'anggaran.gt' => 'Anggaran harus lebih dari :value',
        ]);

        try {
            $maxKode = PengajuanNPD::where('tahun', date('Y'))->where('bagian', $bagian)->max('kode');
            $validatedData['kode'] = $maxKode ? $maxKode + 1 : 1;

            $validatedData['bagian'] = $bagian;
            $validatedData['nomor'] = $validatedData['kode'] . '/KPA/PPU.03/' . date('m/Y');
            $create = PengajuanNPD::create($validatedData);

            return redirect()->back()->with('pengajuan', $create->nomor);
        } catch (\Throwable $err) {
            return back()->withInput()->with('error', $err->getMessage());
        }
    }

    public function updatePengajuan(Request $request, $bagian, PengajuanNPD $pengajuanNPD)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:Disetujui,Ditolak',
        ]);

        try {
            $pengajuanNPD->update($validatedData);

            return redirect()->back()->with('success', 'Berhasil mengubah status pengajuan');
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }
}
