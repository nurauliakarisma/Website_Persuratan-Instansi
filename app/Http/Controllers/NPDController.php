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
     * Helper to resolve bagian into [dbCode, slug, nama]
     */
    protected function resolveBagian($bagian)
    {
        $isDokinfo = in_array(strtolower($bagian), ['bagiandokinfo', 'dokinfo', 'a']);
        $dbCode = $isDokinfo ? 'A' : 'B';
        $slug = $isDokinfo ? 'BagianDokinfo' : 'BagianFPP';
        $nama = $isDokinfo ? 'Dokinfo' : 'FPP';

        return [$dbCode, $slug, $nama];
    }

    /**
     * Display a listing of the resource.
     */
    public function index($bagian)
    {
        [$dbBagian, $slugBagian, $namaBagian] = $this->resolveBagian($bagian);
        $current_url = Route::current() ? route(Route::current()->getName(), ['bagian' => $slugBagian]) : url("npd/$slugBagian");
        $title = "Data Surat $namaBagian NPD";

        $alokasi_npd = AlokasiNPD::with(['subKegiatan', 'rincianBelanja'])
            ->whereIn('bagian', [$dbBagian, $slugBagian])
            ->leftJoin('rincian_belanja as rb', 'alokasi_npd.rincian_belanja_id', '=', 'rb.id')
            ->select('alokasi_npd.*')
            ->orderBy('rb.kode_rekening', 'asc')
            ->orderBy('alokasi_npd.id', 'asc')
            ->get();

        $alokasi_npd->loadSum(['pengajuan as realisasi' => function ($query) {
            $query->where('status', 'Disetujui');
        }], 'anggaran');

        return view('npd.index', ['title' => $title, 'bagian' => $slugBagian, 'alokasi_npd' => $alokasi_npd, 'current_url' => $current_url]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($bagian)
    {
        [$dbBagian, $slugBagian, $namaBagian] = $this->resolveBagian($bagian);
        $title = "Tambah Alokasi NPD $namaBagian";

        $subs = SubKegiatan::orderBy('kode_subkegiatan', 'asc')->get();
        $rincians = RincianBelanja::orderBy('kode_rekening', 'asc')->get();

        return view('npd.tambah', ['title' => $title, 'bagian' => $slugBagian, 'subs' => $subs, 'rincians' => $rincians]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $bagian)
    {
        [$dbBagian, $slugBagian, $namaBagian] = $this->resolveBagian($bagian);

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

        $validatedData['bagian'] = $dbBagian;
        $validatedData['total_anggaran'] = $request->anggaran;

        try {
            AlokasiNPD::create($validatedData);

            return redirect()->route('npd.index', ['bagian' => $slugBagian])->with('success', "Data surat $namaBagian NPD berhasil ditambahkan.");
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $bagian, $alokasi = null)
    {
        [$dbBagian, $slugBagian, $namaBagian] = $this->resolveBagian($bagian);
        $title = "Detail Data Surat $namaBagian NPD";
        $prev_url = $request->input('prev_url', null);

        $pengajuan = PengajuanNPD::whereHas('alokasi', function (Builder $query) use ($dbBagian, $slugBagian) {
            $query->whereIn('bagian', [$dbBagian, $slugBagian]);
        });
        if ($alokasi) {
            $pengajuan = $pengajuan->where('alokasi_npd_id', $alokasi);
        }
        $pengajuan = $pengajuan->orderBy('tanggal_pengajuan', 'asc')->orderBy('kode', 'asc')->get();

        $npds = AlokasiNPD::with(['subKegiatan', 'rincianBelanja'])
            ->whereIn('bagian', [$dbBagian, $slugBagian])
            ->get();

        $npds->loadSum(['pengajuan as realisasi' => function ($query) {
            $query->where('status', 'Disetujui');
        }], 'anggaran');

        return view('npd.detail', ['title' => $title, 'prev_url' => $prev_url, 'pengajuan' => $pengajuan, 'bagian' => $slugBagian, 'npds' => $npds]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($bagian, AlokasiNPD $alokasiNPD)
    {
        [$dbBagian, $slugBagian, $namaBagian] = $this->resolveBagian($bagian);
        $title = "Edit Alokasi NPD $namaBagian";

        return view('npd.edit', ['title' => $title, 'bagian' => $slugBagian, 'alokasiNPD' => $alokasiNPD]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $bagian, AlokasiNPD $alokasiNPD)
    {
        [$dbBagian, $slugBagian, $namaBagian] = $this->resolveBagian($bagian);

        $request->validate([
            'anggaran' => 'required|numeric|gt:0',
        ]);

        try {
            $alokasiNPD->update(['total_anggaran' => $request->anggaran]);

            return redirect()->route('npd.index', ['bagian' => $slugBagian])->with('success', 'Berhasil mengubah data NPD');
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($bagian, AlokasiNPD $alokasiNPD)
    {
        [$dbBagian, $slugBagian, $namaBagian] = $this->resolveBagian($bagian);

        try {
            $alokasiNPD->delete();

            return redirect()->route('npd.index', ['bagian' => $slugBagian])->with('success', 'Berhasil menghapus data NPD');
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }

    public function createPengajuan($bagian)
    {
        [$dbBagian, $slugBagian, $namaBagian] = $this->resolveBagian($bagian);
        $title = 'Ajukan NPD Bagian '.$namaBagian;

        $npds = AlokasiNPD::with(['subKegiatan', 'rincianBelanja'])
            ->leftJoin('rincian_belanja as rb', 'alokasi_npd.rincian_belanja_id', '=', 'rb.id')
            ->select('alokasi_npd.*')
            ->whereIn('alokasi_npd.bagian', [$dbBagian, $slugBagian])
            ->orderBy('rb.kode_rekening', 'asc')
            ->orderBy('alokasi_npd.id', 'asc')
            ->get();

        $npds->loadSum(['pengajuan as realisasi' => function ($query) {
            $query->where('status', 'Disetujui');
        }], 'anggaran');

        return view('npd.pengajuan', ['title' => $title, 'bagian' => $slugBagian, 'npds' => $npds]);
    }

    public function storePengajuan(Request $request, $bagian)
    {
        [$dbBagian, $slugBagian, $namaBagian] = $this->resolveBagian($bagian);

        if ($request->anggaran > $request->sisa) {
            return back()->withInput()->with('error', 'Anggaran yang ingin diajukan melebihi batas anggaran.');
        }

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
            $maxKode = PengajuanNPD::where('alokasi_npd_id', $validatedData['alokasi_npd_id'])
                ->where('tahun', date('Y'))
                ->max('kode');
            $validatedData['kode'] = $maxKode ? $maxKode + 1 : 1;

            $validatedData['bagian'] = $dbBagian;
            $validatedData['nama_penginput'] = $request->user()->nama ?? auth()->user()->nama ?? 'Staff';
            $validatedData['nomor'] = $validatedData['kode'].'/KPA/PPU.03/'.date('m/Y');
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
            'catatan_penolakan' => 'nullable|string',
        ]);

        if ($validatedData['status'] === 'Disetujui') {
            $validatedData['catatan_penolakan'] = null;
        }

        try {
            $pengajuanNPD->update($validatedData);

            $msg = $validatedData['status'] === 'Disetujui' ? 'Pengajuan NPD berhasil disetujui' : 'Pengajuan NPD berhasil ditolak';

            return redirect()->back()->with('success', $msg);
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }

    public function resubmitPengajuan(Request $request, $bagian, PengajuanNPD $pengajuanNPD)
    {
        [$dbBagian, $slugBagian, $namaBagian] = $this->resolveBagian($bagian);

        $currentUser = auth()->user();
        $isStaff = ($currentUser->tipe ?? '') === 'Staff';
        $isCreator = ($currentUser->nama ?? '') === ($pengajuanNPD->nama_penginput ?? '') || empty($pengajuanNPD->nama_penginput);

        if (! $isStaff || ! $isCreator) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mengajukan ulang pengajuan ini. Hanya staf yang mengajukan ('.($pengajuanNPD->nama_penginput ?? 'staf pengaju').') yang dapat melakukan perbaikan.');
        }

        $validatedData = $request->validate([
            'alokasi_npd_id' => 'required|integer|exists:alokasi_npd,id',
            'uraian_kegiatan' => 'required|string',
            'anggaran' => 'required|numeric|gt:0',
        ], [
            'alokasi_npd_id.required' => 'Pilih NPD yang ingin diajukan terlebih dahulu.',
            'alokasi_npd_id.exists' => 'NPD yang dipilih tidak valid.',
            'uraian_kegiatan.required' => 'Uraian Kegiatan harus diisi.',
            'anggaran.required' => 'Anggaran harus diisi.',
            'anggaran.gt' => 'Anggaran harus lebih dari :value',
        ]);

        $alokasi = AlokasiNPD::find($validatedData['alokasi_npd_id']) ?? $pengajuanNPD->alokasi;
        if ($alokasi) {
            $realisasiLain = PengajuanNPD::where('alokasi_npd_id', $alokasi->id)
                ->where('id', '!=', $pengajuanNPD->id)
                ->where('status', 'Disetujui')
                ->sum('anggaran');
            $sisa = max(0, $alokasi->total_anggaran - $realisasiLain);
            if ($validatedData['anggaran'] > $sisa) {
                return back()->withInput()->with('error', 'Anggaran yang diajukan melebihi sisa anggaran (Sisa: Rp '.number_format($sisa, 0, ',', '.').').');
            }
        }

        try {
            $validatedData['status'] = 'Diajukan';
            $validatedData['catatan_penolakan'] = null;
            if (empty($pengajuanNPD->nama_penginput)) {
                $validatedData['nama_penginput'] = $currentUser->nama ?? 'Staff';
            }
            $pengajuanNPD->update($validatedData);

            return redirect()->back()->with('success', 'Pengajuan NPD nomor '.$pengajuanNPD->nomor.' berhasil diperbaiki dan diajukan ulang ke Admin.');
        } catch (\Throwable $err) {
            return back()->withInput()->with('error', $err->getMessage());
        }
    }
}
