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
        $title = "Data Surat $namaBagian NODIN";

        $nodins = PengajuanNODIN::with(['indexKegiatan', 'subKegiatan', 'rincianBelanja'])
            ->leftJoin('rincian_belanja as rb', 'pengajuan_nodin.rincian_belanja_id', '=', 'rb.id')
            ->select('pengajuan_nodin.*')
            ->whereIn('pengajuan_nodin.bagian', [$dbBagian, $slugBagian])
            ->orderBy('rb.kode_rekening', 'asc')
            ->orderBy('pengajuan_nodin.tanggal_pengajuan', 'asc')
            ->orderBy('pengajuan_nodin.kode', 'asc')
            ->get();

        $indexes = IndexKegiatan::all();
        $subkegiatans = SubKegiatan::all();
        $rincians = RincianBelanja::all();
        $staffs = User::where('tipe', 'Staff')->orderBy('nama', 'ASC')->get();

        return view('nodin.index', ['title' => $title, 'bagian' => $slugBagian, 'nodins' => $nodins, 'indexes' => $indexes, 'subkegiatans' => $subkegiatans, 'rincians' => $rincians, 'staffs' => $staffs]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($bagian)
    {
        [$dbBagian, $slugBagian, $namaBagian] = $this->resolveBagian($bagian);
        $title = "Pengajuan Nota Dinas Bagian $namaBagian";

        $indexes = IndexKegiatan::all();
        $subkegiatans = SubKegiatan::all();
        $rincians = RincianBelanja::all();
        $staffs = User::where('tipe', 'Staff')->orderBy('nama', 'ASC')->get();

        return view('nodin.pengajuan', ['title' => $title, 'bagian' => $slugBagian, 'indexes' => $indexes, 'subkegiatans' => $subkegiatans, 'rincians' => $rincians, 'staffs' => $staffs]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $bagian)
    {
        [$dbBagian, $slugBagian, $namaBagian] = $this->resolveBagian($bagian);

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
            $maxKode = PengajuanNODIN::where('tahun', date('Y'))->whereIn('bagian', [$dbBagian, $slugBagian])->max('kode');
            $validatedData['kode'] = $maxKode ? $maxKode + 1 : 1;

            $validatedData['bagian'] = $dbBagian;
            $validatedData['atas_nama'] = implode(', ', $request->atas_nama);
            $validatedData['nomor'] = "{$request->nomor_index}/".$validatedData['kode'].'/PPUU/050.4/'.date('Y');
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
        [$dbBagian, $slugBagian, $namaBagian] = $this->resolveBagian($bagian);
        $title = "Detail Data Surat $namaBagian NODIN";
        $prev_url = $request->input('prev_url', null);

        $pengajuan = PengajuanNODIN::with(['indexKegiatan', 'subKegiatan', 'rincianBelanja'])
            ->leftJoin('rincian_belanja as rb', 'pengajuan_nodin.rincian_belanja_id', '=', 'rb.id')
            ->select('pengajuan_nodin.*')
            ->whereIn('pengajuan_nodin.bagian', [$dbBagian, $slugBagian])
            ->orderBy('rb.kode_rekening', 'asc')
            ->orderBy('pengajuan_nodin.tanggal_pengajuan', 'asc')
            ->orderBy('pengajuan_nodin.kode', 'asc')
            ->get();

        $indexes = IndexKegiatan::all();
        $subkegiatans = SubKegiatan::all();
        $rincians = RincianBelanja::all();
        $staffs = User::where('tipe', 'Staff')->orderBy('nama', 'ASC')->get();

        return view('nodin.detail', ['title' => $title, 'bagian' => $slugBagian, 'pengajuan' => $pengajuan, 'prev_url' => $prev_url, 'indexes' => $indexes, 'subkegiatans' => $subkegiatans, 'rincians' => $rincians, 'staffs' => $staffs]);
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
            'catatan_penolakan' => 'nullable|string',
        ]);

        if ($validatedData['status'] === 'Disetujui') {
            $validatedData['catatan_penolakan'] = null;
        }

        try {
            $pengajuanNODIN->update($validatedData);

            $msg = $validatedData['status'] === 'Disetujui' ? 'Pengajuan NODIN berhasil disetujui' : 'Pengajuan NODIN berhasil ditolak';

            return redirect()->back()->with('success', $msg);
        } catch (\Throwable $err) {
            return back()->with('error', $this->errorMessage($err->getCode()));
        }
    }

    public function resubmitPengajuan(Request $request, $bagian, PengajuanNODIN $pengajuanNODIN)
    {
        [$dbBagian, $slugBagian, $namaBagian] = $this->resolveBagian($bagian);

        $currentUser = auth()->user();
        $isStaff = ($currentUser->tipe ?? '') === 'Staff';
        $isCreator = ($currentUser->nama ?? '') === ($pengajuanNODIN->nama_penginput ?? '') || empty($pengajuanNODIN->nama_penginput);

        if (! $isStaff || ! $isCreator) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mengajukan ulang pengajuan ini. Hanya staf yang mengajukan ('.($pengajuanNODIN->nama_penginput ?? 'staf pengaju').') yang dapat melakukan perbaikan.');
        }

        $validatedData = $request->validate([
            'index_kegiatan_id' => 'required|exists:index_kegiatan,id',
            'subkegiatan_id' => 'required|exists:subkegiatan,id',
            'rincian_belanja_id' => 'required|exists:rincian_belanja,id',
            'subject' => 'required|string',
            'perihal' => 'required|string',
            'tanggal_mulai' => 'required|date|before_or_equal:tanggal_selesai',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'atas_nama' => 'required|array|min:1|exclude',
        ], [
            'index_kegiatan_id.required' => 'Pilih index kegiatan terlebih dahulu.',
            'subkegiatan_id.required' => 'Pilih sub kegiatan terlebih dahulu.',
            'rincian_belanja_id.required' => 'Pilih rincian belanja terlebih dahulu.',
            'perihal.required' => 'Perihal harus diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi.',
            'tanggal_mulai.before_or_equal' => 'Tanggal mulai harus sebelum atau sama dengan tanggal selesai.',
            'tanggal_selesai.required' => 'Tanggal selesai harus diisi.',
            'atas_nama.required' => 'Atas nama harus diisi',
            'subject.required' => 'Subject harus diisi.',
        ]);

        try {
            $validatedData['status'] = 'Diajukan';
            $validatedData['catatan_penolakan'] = null;
            $validatedData['atas_nama'] = implode(', ', $request->atas_nama);
            $pengajuanNODIN->update($validatedData);

            return redirect()->back()->with('success', 'Pengajuan NODIN nomor '.$pengajuanNODIN->nomor.' berhasil diperbaiki dan diajukan ulang ke Admin.');
        } catch (\Throwable $err) {
            return back()->withInput()->with('error', $err->getMessage());
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
        [$dbBagian, $slugBagian, $namaBagian] = $this->resolveBagian($bagian);
        $title = 'Rekap NODIN '.$namaBagian;

        $staffs = User::where('tipe', 'Staff')->orderBy('nama')->get();
        $staff = $request->staff ?? '';

        return view('nodin.rekap', ['title' => $title, 'bagian' => $slugBagian, 'staffs' => $staffs, 'staff' => $staff]);
    }

    public function listRekap(Request $request, $bagian)
    {
        [$dbBagian, $slugBagian, $namaBagian] = $this->resolveBagian($bagian);
        $start = date('Y-m-d', strtotime($request->start));
        $end = date('Y-m-d', strtotime($request->end));

        $nodins = PengajuanNODIN::where('tanggal_mulai', '>=', $start)
            ->where('tanggal_selesai', '<=', $end)
            ->whereIn('bagian', [$dbBagian, $slugBagian])
            ->where('status', 'Disetujui');
        if ($request->staff !== '') {
            $nodins->where('atas_nama', 'like', "%$request->staff%");
        }
        $nodins = $nodins->get()
            ->map(fn ($item) => [
                'id' => $item->id,
                'title' => $item->subject,
                'start' => date(DATE_ISO8601, strtotime($item->tanggal_mulai)),
                'end' => date(DATE_ISO8601, strtotime($item->tanggal_selesai)),
                'nomor' => $item->nomor,
                'perihal' => $item->perihal,
                'an' => $item->atas_nama,
                'nama_penginput' => $item->nama_penginput ?? '-',
            ]);

        return response()->json($nodins);
    }
}
