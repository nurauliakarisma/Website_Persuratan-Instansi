<?php

namespace Database\Seeders;

use App\Models\AlokasiNPD;
use App\Models\IndexKegiatan;
use App\Models\Media;
use App\Models\PengajuanNODIN;
use App\Models\PengajuanNPD;
use App\Models\PengajuanPublikasi;
use App\Models\RincianBelanja;
use App\Models\SubKegiatan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $currentYear = (int) date('Y');

        // 1. TAMBAH USERS (Staff & Admin)
        $usersData = [
            [
                'nama' => 'Ahmad Fauzi, S.STP',
                'email' => 'ahmad.fauzi@gmail.com',
                'password' => Hash::make('password123'),
                'tipe' => 'Staff',
                'nip' => '198801122010121001',
                'jabatan' => 'Pengadministrasi Persuratan',
            ],
            [
                'nama' => 'Budi Santoso, S.Kom',
                'email' => 'budi.santoso@gmail.com',
                'password' => Hash::make('password123'),
                'tipe' => 'Staff',
                'nip' => '199003152014021002',
                'jabatan' => 'Pranata Komputer',
            ],
            [
                'nama' => 'Citra Dewi, S.Sos',
                'email' => 'citra.dewi@gmail.com',
                'password' => Hash::make('password123'),
                'tipe' => 'Staff',
                'nip' => '199207212015032001',
                'jabatan' => 'Analis Tata Usaha',
            ],
            [
                'nama' => 'Dwi Handoko, S.E.',
                'email' => 'dwi.handoko@gmail.com',
                'password' => Hash::make('password123'),
                'tipe' => 'Staff',
                'nip' => '198505102009011003',
                'jabatan' => 'Bendahara Pengeluaran Pembantu',
            ],
            [
                'nama' => 'Eka Rahmawati, S.AP',
                'email' => 'eka.rahmawati@gmail.com',
                'password' => Hash::make('password123'),
                'tipe' => 'Staff',
                'nip' => '199411082019032005',
                'jabatan' => 'Pengelola Kepegawaian',
            ],
            [
                'nama' => 'Fajar Nugroho, S.H.',
                'email' => 'fajar.nugroho@gmail.com',
                'password' => Hash::make('password123'),
                'tipe' => 'Staff',
                'nip' => '198709142011011002',
                'jabatan' => 'Analis Hukum & Persidangan',
            ],
            [
                'nama' => 'Gita Permata, S.Ak',
                'email' => 'gita.permata@gmail.com',
                'password' => Hash::make('password123'),
                'tipe' => 'Staff',
                'nip' => '199302182016022003',
                'jabatan' => 'Verifikator Keuangan',
            ],
            [
                'nama' => 'Hadi Prasetyo, S.IP',
                'email' => 'hadi.prasetyo@gmail.com',
                'password' => Hash::make('password123'),
                'tipe' => 'Staff',
                'nip' => '198904222012121004',
                'jabatan' => 'Pengelola Dokumentasi & Publikasi',
            ],
        ];

        foreach ($usersData as $u) {
            User::firstOrCreate(['email' => $u['email']], $u);
        }

        // 2. TAMBAH INDEX KEGIATAN (15 data)
        $indexData = [
            ['kode' => '050.4/01', 'keterangan' => 'Rapat Paripurna DPRD Provinsi Jawa Timur'],
            ['kode' => '050.4/02', 'keterangan' => 'Rapat Badan Anggaran (Banggar)'],
            ['kode' => '050.4/03', 'keterangan' => 'Rapat Badan Pembentukan Perda (Bapemperda)'],
            ['kode' => '050.4/04', 'keterangan' => 'Rapat Badan Musyawarah (Banmus)'],
            ['kode' => '050.4/05', 'keterangan' => 'Rapat Komisi A (Pemerintahan dan Hukum)'],
            ['kode' => '050.4/06', 'keterangan' => 'Rapat Komisi B (Perekonomian)'],
            ['kode' => '050.4/07', 'keterangan' => 'Rapat Komisi C (Keuangan dan Aset)'],
            ['kode' => '050.4/08', 'keterangan' => 'Rapat Komisi D (Pembangunan dan Infrastruktur)'],
            ['kode' => '050.4/09', 'keterangan' => 'Rapat Komisi E (Kesejahteraan Rakyat)'],
            ['kode' => '050.4/10', 'keterangan' => 'Kunjungan Kerja Luar Daerah Pimpinan & Anggota'],
            ['kode' => '050.4/11', 'keterangan' => 'Kunjungan Kerja Dalam Daerah / Reses'],
            ['kode' => '050.4/12', 'keterangan' => 'Kajian Akademis dan Konsultasi Raperda'],
            ['kode' => '050.4/13', 'keterangan' => 'Penyusunan Risalah dan Notulensi Rapat'],
            ['kode' => '050.4/14', 'keterangan' => 'Sosialisasi Kebijakan dan Publikasi Media'],
            ['kode' => '050.4/15', 'keterangan' => 'Penerimaan Audiensi Masyarakat & Lembaga'],
        ];

        $indexModels = [];
        foreach ($indexData as $idx) {
            $indexModels[] = IndexKegiatan::firstOrCreate(['kode' => $idx['kode']], $idx);
        }

        // 3. TAMBAH SUB KEGIATAN (15 data)
        $subKegiatanData = [
            [
                'kode_program' => '4.01.01',
                'ket_program' => 'Program Penunjang Urusan Pemerintahan Daerah Provinsi',
                'kode_kegiatan' => '4.01.01.1.01',
                'ket_kegiatan' => 'Perencanaan, Penganggaran, dan Evaluasi Kinerja Perangkat Daerah',
                'kode_subkegiatan' => '4.01.01.1.01.0001',
                'ket_subkegiatan' => 'Penyusunan Dokumen Perencanaan dan Laporan Kinerja SKPD',
            ],
            [
                'kode_program' => '4.01.01',
                'ket_program' => 'Program Penunjang Urusan Pemerintahan Daerah Provinsi',
                'kode_kegiatan' => '4.01.01.1.02',
                'ket_kegiatan' => 'Administrasi Keuangan Perangkat Daerah',
                'kode_subkegiatan' => '4.01.01.1.02.0001',
                'ket_subkegiatan' => 'Penyediaan Gaji dan Tunjangan ASN',
            ],
            [
                'kode_program' => '4.01.01',
                'ket_program' => 'Program Penunjang Urusan Pemerintahan Daerah Provinsi',
                'kode_kegiatan' => '4.01.01.1.02',
                'ket_kegiatan' => 'Administrasi Keuangan Perangkat Daerah',
                'kode_subkegiatan' => '4.01.01.1.02.0002',
                'ket_subkegiatan' => 'Koordinasi dan Penyusunan Laporan Keuangan Akhir Tahun',
            ],
            [
                'kode_program' => '4.01.01',
                'ket_program' => 'Program Penunjang Urusan Pemerintahan Daerah Provinsi',
                'kode_kegiatan' => '4.01.01.1.06',
                'ket_kegiatan' => 'Administrasi Umum Perangkat Daerah',
                'kode_subkegiatan' => '4.01.01.1.06.0001',
                'ket_subkegiatan' => 'Penyediaan Komponen Instalasi Listrik dan Penerangan Bangunan',
            ],
            [
                'kode_program' => '4.01.01',
                'ket_program' => 'Program Penunjang Urusan Pemerintahan Daerah Provinsi',
                'kode_kegiatan' => '4.01.01.1.06',
                'ket_kegiatan' => 'Administrasi Umum Perangkat Daerah',
                'kode_subkegiatan' => '4.01.01.1.06.0002',
                'ket_subkegiatan' => 'Penyediaan Peralatan dan Perlengkapan Kantor',
            ],
            [
                'kode_program' => '4.01.01',
                'ket_program' => 'Program Penunjang Urusan Pemerintahan Daerah Provinsi',
                'kode_kegiatan' => '4.01.01.1.06',
                'ket_kegiatan' => 'Administrasi Umum Perangkat Daerah',
                'kode_subkegiatan' => '4.01.01.1.06.0003',
                'ket_subkegiatan' => 'Penyediaan Bahan Logistik Kantor dan ATK',
            ],
            [
                'kode_program' => '4.01.01',
                'ket_program' => 'Program Penunjang Urusan Pemerintahan Daerah Provinsi',
                'kode_kegiatan' => '4.01.01.1.06',
                'ket_kegiatan' => 'Administrasi Umum Perangkat Daerah',
                'kode_subkegiatan' => '4.01.01.1.06.0004',
                'ket_subkegiatan' => 'Penyelenggaraan Rapat Koordinasi dan Konsultasi SKPD',
            ],
            [
                'kode_program' => '4.01.01',
                'ket_program' => 'Program Penunjang Urusan Pemerintahan Daerah Provinsi',
                'kode_kegiatan' => '4.01.01.1.08',
                'ket_kegiatan' => 'Peningkatan Sarana dan Prasarana Disiplin Pegawai',
                'kode_subkegiatan' => '4.01.01.1.08.0001',
                'ket_subkegiatan' => 'Pengadaan Pakaian Dinas Beserta Perlengkapannya',
            ],
            [
                'kode_program' => '4.01.02',
                'ket_program' => 'Program Dukungan Pelaksanaan Tugas dan Fungsi DPRD',
                'kode_kegiatan' => '4.01.02.1.01',
                'ket_kegiatan' => 'Layanan Pembentukan Peraturan Daerah dan Peraturan DPRD',
                'kode_subkegiatan' => '4.01.02.1.01.0001',
                'ket_subkegiatan' => 'Penyusunan Naskah Akademik dan Draf Rancangan Perda',
            ],
            [
                'kode_program' => '4.01.02',
                'ket_program' => 'Program Dukungan Pelaksanaan Tugas dan Fungsi DPRD',
                'kode_kegiatan' => '4.01.02.1.01',
                'ket_kegiatan' => 'Layanan Pembentukan Peraturan Daerah dan Peraturan DPRD',
                'kode_subkegiatan' => '4.01.02.1.01.0002',
                'ket_subkegiatan' => 'Fasilitasi Pembahasan Rancangan Peraturan Daerah',
            ],
            [
                'kode_program' => '4.01.02',
                'ket_program' => 'Program Dukungan Pelaksanaan Tugas dan Fungsi DPRD',
                'kode_kegiatan' => '4.01.02.1.02',
                'ket_kegiatan' => 'Layanan Pembahasan KUA-PPAS dan APBD',
                'kode_subkegiatan' => '4.01.02.1.02.0001',
                'ket_subkegiatan' => 'Fasilitasi Rapat Pembahasan Banggar DPRD dan TAPD',
            ],
            [
                'kode_program' => '4.01.02',
                'ket_program' => 'Program Dukungan Pelaksanaan Tugas dan Fungsi DPRD',
                'kode_kegiatan' => '4.01.02.1.03',
                'ket_kegiatan' => 'Layanan Pengawasan Urusan Pemerintahan dan Pembangunan',
                'kode_subkegiatan' => '4.01.02.1.03.0001',
                'ket_subkegiatan' => 'Fasilitasi Kunjungan Kerja Lapangan Komisi DPRD',
            ],
            [
                'kode_program' => '4.01.02',
                'ket_program' => 'Program Dukungan Pelaksanaan Tugas dan Fungsi DPRD',
                'kode_kegiatan' => '4.01.02.1.04',
                'ket_kegiatan' => 'Layanan Penyerapan Aspirasi Masyarakat',
                'kode_subkegiatan' => '4.01.02.1.04.0001',
                'ket_subkegiatan' => 'Fasilitasi Kegiatan Reses Pimpinan dan Anggota DPRD',
            ],
            [
                'kode_program' => '4.01.02',
                'ket_program' => 'Program Dukungan Pelaksanaan Tugas dan Fungsi DPRD',
                'kode_kegiatan' => '4.01.02.1.05',
                'ket_kegiatan' => 'Layanan Publikasi dan Hubungan Masyarakat DPRD',
                'kode_subkegiatan' => '4.01.02.1.05.0001',
                'ket_subkegiatan' => 'Publikasi Kinerja Dewan Melalui Media Cetak, Online & Elektronik',
            ],
            [
                'kode_program' => '4.01.02',
                'ket_program' => 'Program Dukungan Pelaksanaan Tugas dan Fungsi DPRD',
                'kode_kegiatan' => '4.01.02.1.05',
                'ket_kegiatan' => 'Layanan Publikasi dan Hubungan Masyarakat DPRD',
                'kode_subkegiatan' => '4.01.02.1.05.0002',
                'ket_subkegiatan' => 'Pengelolaan Dokumentasi dan Pemberitaan Kegiatan Paripurna',
            ],
        ];

        $subKegiatanModels = [];
        foreach ($subKegiatanData as $sub) {
            $subKegiatanModels[] = SubKegiatan::firstOrCreate([
                'kode_program' => $sub['kode_program'],
                'kode_kegiatan' => $sub['kode_kegiatan'],
                'kode_subkegiatan' => $sub['kode_subkegiatan'],
            ], $sub);
        }

        // 4. TAMBAH RINCIAN BELANJA (15 data)
        $rincianData = [
            ['kode_rekening' => '5.1.02.01.01.0024', 'keterangan' => 'Belanja Alat/Bahan untuk Kegiatan Kantor-Alat Tulis Kantor'],
            ['kode_rekening' => '5.1.02.01.01.0025', 'keterangan' => 'Belanja Alat/Bahan untuk Kegiatan Kantor-Kertas dan Cover'],
            ['kode_rekening' => '5.1.02.01.01.0026', 'keterangan' => 'Belanja Alat/Bahan untuk Kegiatan Kantor-Bahan Cetak'],
            ['kode_rekening' => '5.1.02.01.01.0027', 'keterangan' => 'Belanja Benda Pos dan Materai'],
            ['kode_rekening' => '5.1.02.01.01.0052', 'keterangan' => 'Belanja Makanan dan Minuman Rapat Paripurna & Komisi'],
            ['kode_rekening' => '5.1.02.01.01.0053', 'keterangan' => 'Belanja Makanan dan Minuman Jamuan Tamu Pimpinan'],
            ['kode_rekening' => '5.1.02.02.01.0003', 'keterangan' => 'Honorarium Narasumber / Tenaga Ahli Raperda'],
            ['kode_rekening' => '5.1.02.02.01.0014', 'keterangan' => 'Belanja Jasa Tenaga Ahli Fraksi dan Kelompok Kerja'],
            ['kode_rekening' => '5.1.02.02.01.0026', 'keterangan' => 'Belanja Jasa Iklan/Reklame, Film, dan Pemotretan/Media Publikasi'],
            ['kode_rekening' => '5.1.02.02.01.0030', 'keterangan' => 'Belanja Jasa Sewa Gedung Pertemuan dan Fasilitas Rapat'],
            ['kode_rekening' => '5.1.02.04.01.0001', 'keterangan' => 'Belanja Perjalanan Dinas Biasa Dalam Daerah'],
            ['kode_rekening' => '5.1.02.04.01.0003', 'keterangan' => 'Belanja Perjalanan Dinas Luar Daerah Pimpinan & Anggota'],
            ['kode_rekening' => '5.1.02.04.01.0004', 'keterangan' => 'Belanja Perjalanan Dinas Paket Meeting Luar Kota'],
            ['kode_rekening' => '5.2.02.05.01.0005', 'keterangan' => 'Belanja Modal Peralatan Komputer dan Jaringan'],
            ['kode_rekening' => '5.1.02.03.02.0035', 'keterangan' => 'Belanja Pemeliharaan Peralatan dan Mesin Kantor'],
        ];

        $rincianModels = [];
        foreach ($rincianData as $rc) {
            $rincianModels[] = RincianBelanja::firstOrCreate(['kode_rekening' => $rc['kode_rekening']], $rc);
        }

        // 5. TAMBAH MASTER MEDIA (15 data)
        $mediaData = [
            ['nama' => 'Harian Surya / Tribun Jatim Network', 'harga_penawaran' => 25000000, 'harga_deal' => 20000000, 'harga_total' => 22200000, 'status' => 'Mitra Utama Cetak & Online'],
            ['nama' => 'Jawa Pos Media Utama', 'harga_penawaran' => 35000000, 'harga_deal' => 28000000, 'harga_total' => 31080000, 'status' => 'Mitra Utama Koran Nasional'],
            ['nama' => 'Detikcom Biro Jawa Timur', 'harga_penawaran' => 30000000, 'harga_deal' => 25000000, 'harga_total' => 27750000, 'status' => 'Portal Berita Nasional'],
            ['nama' => 'Kompas Biro Jawa Timur', 'harga_penawaran' => 32000000, 'harga_deal' => 26000000, 'harga_total' => 28860000, 'status' => 'Koran & Portal Berita'],
            ['nama' => 'Beritajatim.com Digital Media', 'harga_penawaran' => 18000000, 'harga_deal' => 14000000, 'harga_total' => 15540000, 'status' => 'Portal Berita Lokal Jatim'],
            ['nama' => 'Times Indonesia Network', 'harga_penawaran' => 20000000, 'harga_deal' => 15000000, 'harga_total' => 16650000, 'status' => 'Media Berita Positif'],
            ['nama' => 'Radar Surabaya (Jawa Pos Group)', 'harga_penawaran' => 15000000, 'harga_deal' => 12000000, 'harga_total' => 13320000, 'status' => 'Koran Metropolitan'],
            ['nama' => 'Memorandum Biro Jatim', 'harga_penawaran' => 14000000, 'harga_deal' => 11000000, 'harga_total' => 12210000, 'status' => 'Koran Kriminal & Hukum'],
            ['nama' => 'Suara Surabaya Media Digital', 'harga_penawaran' => 22000000, 'harga_deal' => 18000000, 'harga_total' => 19980000, 'status' => 'Radio & Portal Berita'],
            ['nama' => 'Jatimnow.com Media Siber', 'harga_penawaran' => 16000000, 'harga_deal' => 12500000, 'harga_total' => 13875000, 'status' => 'Media Siber Regional'],
            ['nama' => 'Koran Sindo Jatim', 'harga_penawaran' => 20000000, 'harga_deal' => 16000000, 'harga_total' => 17760000, 'status' => 'Koran Nasional Biro Jatim'],
            ['nama' => 'TV9 Nusantara Jawa Timur', 'harga_penawaran' => 40000000, 'harga_deal' => 32000000, 'harga_total' => 35520000, 'status' => 'Televisi Religi & Budaya'],
            ['nama' => 'JTV (Jawa Pos Multimedia)', 'harga_penawaran' => 45000000, 'harga_deal' => 36000000, 'harga_total' => 39960000, 'status' => 'Televisi Lokal Terbesar Jatim'],
            ['nama' => 'RRI Surabaya Pro 1 & Pro 3', 'harga_penawaran' => 18000000, 'harga_deal' => 14500000, 'harga_total' => 16095000, 'status' => 'Radio Publik Nasional'],
            ['nama' => 'Duta Masyarakat', 'harga_penawaran' => 12000000, 'harga_deal' => 9500000, 'harga_total' => 10545000, 'status' => 'Koran Komunitas Jatim'],
        ];

        $mediaModels = [];
        foreach ($mediaData as $m) {
            $mediaModels[] = Media::firstOrCreate(['nama' => $m['nama']], $m);
        }

        // 6. TAMBAH ALOKASI NPD (Bagian A & Bagian B - Total 20 data)
        $alokasiModels = [];
        $budgets = [
            75000000, 120000000, 250000000, 180000000, 300000000,
            450000000, 90000000, 160000000, 350000000, 220000000,
        ];

        // Bagian A: 10 Alokasi
        for ($i = 0; $i < 10; $i++) {
            $sub = $subKegiatanModels[$i % count($subKegiatanModels)];
            $rc = $rincianModels[$i % count($rincianModels)];
            $alokasiModels[] = AlokasiNPD::firstOrCreate([
                'bagian' => 'A',
                'subkegiatan_id' => $sub->id,
                'rincian_belanja_id' => $rc->id,
            ], [
                'total_anggaran' => $budgets[$i],
                'tahun' => $currentYear,
            ]);
        }

        // Bagian B: 10 Alokasi
        for ($i = 0; $i < 10; $i++) {
            $sub = $subKegiatanModels[($i + 5) % count($subKegiatanModels)];
            $rc = $rincianModels[($i + 3) % count($rincianModels)];
            $alokasiModels[] = AlokasiNPD::firstOrCreate([
                'bagian' => 'B',
                'subkegiatan_id' => $sub->id,
                'rincian_belanja_id' => $rc->id,
            ], [
                'total_anggaran' => $budgets[$i] + 50000000,
                'tahun' => $currentYear,
            ]);
        }

        // 7. TAMBAH PENGAJUAN NPD (Total 30 data)
        $npdUraianList = [
            'Belanja ATK dan kelengkapan administrasi rapat paripurna istimewa pembukaan masa sidang',
            'Penyediaan konsumsi makanan dan minuman untuk rapat dengar pendapat komisi bersama mitra OPD',
            'Honorarium narasumber ahli hukum tata negara dalam kajian draf Raperda Pelayanan Publik',
            'Belanja tiket dan akomodasi perjalanan dinas konsultasi komisi ke Kementerian Dalam Negeri Jakarta',
            'Penggandaan naskah akademik rancangan perda ketahanan pangan dan dokumen pertanggungjawaban',
            'Belanja publikasi sosialisasi hasil pengawasan perda di media cetak harian lokal',
            'Pengadaan perlengkapan kantor, toner printer, dan kertas arsip komisi DPRD',
            'Fasilitasi konsumsi dan sound system pelaksanaan kegiatan serap aspirasi masyarakat dapil II',
            'Belanja perjalanan dinas komisi dalam rangka kunker monitoring infrastruktur jalan provinsi di Pacitan',
            'Honorarium tim ahli penyusunan rekomendasi LKPJ Gubernur Jawa Timur Akhir Tahun Anggaran',
            'Belanja konsumsi rapat badan anggaran pembahasan tindak lanjut evaluasi Mendagri atas Ranperda APBD',
            'Penyediaan jasa publikasi capaian kinerja pimpinan dewan pada portal berita online',
            'Belanja sewa ruang pertemuan dan paket meeting koordinasi Bapemperda di Kota Malang',
            'Belanja alat tulis, map arsip persuratan dan meterai pengiriman berkas dinas dewan',
            'Pengadaan media promosi kegiatan dewan, banner sosialisasi perda, dan backdrop paripurna',
        ];

        $npdStatuses = ['Disetujui', 'Diajukan', 'Disetujui', 'Disetujui', 'Ditolak', 'Diajukan', 'Disetujui'];

        $staffNames = [
            'Qc Aulia Karisma, S.STP',
            'Ahmad Fauzi, S.STP',
            'Budi Santoso, S.Kom',
            'Citra Dewi, S.Sos',
            'Dwi Handoko, S.E.',
            'Eka Rahmawati, S.AP',
            'Fajar Nugroho, S.H.',
            'Gita Permata, S.Ak',
            'Hadi Prasetyo, S.IP',
        ];

        $rejectionNotesNPD = [
            'Mohon lampirkan rincian nota pesanan dan telaah staf terbaru yang telah diparaf oleh PPTK.',
            'Rincian belanja melebihi pagu estimasi sub kegiatan, silakan sesuaikan volume kegiatan.',
            'Dokumen pendukung SPJ belum lengkap, mohon perbaiki tanggal pelaksanaan dan lampiran harga penawaran.',
        ];

        // Pengajuan NPD Bagian A (15 records)
        $alokasiA = array_values(array_filter($alokasiModels, fn ($a) => $a->bagian === 'A'));
        $npdCounterA = [];
        for ($k = 1; $k <= 15; $k++) {
            $alo = $alokasiA[($k - 1) % count($alokasiA)];
            $currentKode = ($npdCounterA[$alo->id] ?? 0) + 1;
            $npdCounterA[$alo->id] = $currentKode;

            $month = str_pad(max(1, min(12, $k)), 2, '0', STR_PAD_LEFT);
            $day = str_pad(($k * 2) % 27 + 1, 2, '0', STR_PAD_LEFT);
            $nomor = "$currentKode/KPA/PPU.03/$month/$currentYear";
            $anggaran = rand(3, 15) * 1000000;
            $status = $npdStatuses[$k % count($npdStatuses)];
            $staffPengaju = $staffNames[($k - 1) % count($staffNames)];
            $catatan = $status === 'Ditolak' ? $rejectionNotesNPD[($k - 1) % count($rejectionNotesNPD)] : null;

            PengajuanNPD::firstOrCreate(['nomor' => $nomor, 'alokasi_npd_id' => $alo->id], [
                'alokasi_npd_id' => $alo->id,
                'bagian' => 'A',
                'kode' => $currentKode,
                'tanggal_pengajuan' => "$currentYear-$month-$day",
                'uraian_kegiatan' => $npdUraianList[($k - 1) % count($npdUraianList)]." (Tahap $currentKode)",
                'anggaran' => $anggaran,
                'status' => $status,
                'nama_penginput' => $staffPengaju,
                'catatan_penolakan' => $catatan,
                'tahun' => $currentYear,
            ]);
        }

        // Pengajuan NPD Bagian B (15 records)
        $alokasiB = array_values(array_filter($alokasiModels, fn ($a) => $a->bagian === 'B'));
        $npdCounterB = [];
        for ($k = 1; $k <= 15; $k++) {
            $alo = $alokasiB[($k - 1) % count($alokasiB)];
            $currentKode = ($npdCounterB[$alo->id] ?? 0) + 1;
            $npdCounterB[$alo->id] = $currentKode;

            $month = str_pad(max(1, min(12, $k)), 2, '0', STR_PAD_LEFT);
            $day = str_pad(($k * 2) % 27 + 1, 2, '0', STR_PAD_LEFT);
            $nomor = "$currentKode/KPA/PPU.03/$month/$currentYear";
            $anggaran = rand(5, 20) * 1000000;
            $status = $npdStatuses[($k + 1) % count($npdStatuses)];
            $staffPengaju = $staffNames[($k + 2) % count($staffNames)];
            $catatan = $status === 'Ditolak' ? $rejectionNotesNPD[$k % count($rejectionNotesNPD)] : null;

            PengajuanNPD::firstOrCreate(['nomor' => $nomor, 'alokasi_npd_id' => $alo->id, 'bagian' => 'B'], [
                'alokasi_npd_id' => $alo->id,
                'bagian' => 'B',
                'kode' => $currentKode,
                'tanggal_pengajuan' => "$currentYear-$month-$day",
                'uraian_kegiatan' => $npdUraianList[($k - 1) % count($npdUraianList)]." - Urusan B ($currentKode)",
                'anggaran' => $anggaran,
                'status' => $status,
                'nama_penginput' => $staffPengaju,
                'catatan_penolakan' => $catatan,
                'tahun' => $currentYear,
            ]);
        }

        // 8. TAMBAH PENGAJUAN NODIN (Nota Dinas - Total 30 data)
        $nodinSubjects = [
            'Permohonan Fasilitasi Ruang Rapat Paripurna dan Konsumsi Peserta',
            'Pengajuan Surat Tugas Kunjungan Kerja Luar Daerah Pimpinan Komisi',
            'Permohonan Honorarium Narasumber Uji Publik Draf Perda Lingkungan Hidup',
            'Permintaan Pengadaan Alat Tulis Kantor dan Toner Printer Sekretariat',
            'Pengajuan Koordinasi Pembahasan KUA-PPAS Bersama Tim Anggaran Pemprov',
            'Permohonan Publikasi Rilis Berita Paripurna di Surat Kabar Harian',
            'Permintaan Kendaraan Operasional dan Pengemudi untuk Peninjauan Lapangan',
            'Pengajuan Perjalanan Dinas Konsultasi Draf Peraturan DPRD ke Kemendagri',
            'Permohonan Fasilitasi Audiensi Aliansi Masyarakat Peduli Pertanian',
            'Permintaan Penggandaan Berkas Risalah Sidang Dewan dan Laporan Reses',
            'Pengajuan Fasilitasi Rapat Dengar Pendapat Umum Komisi Bidang Pendidikan',
            'Permohonan Penyediaan Akomodasi Tim Ahli Bapemperda di Surabaya',
            'Pengajuan Verifikasi Kelengkapan SPJ Kegiatan Monitoring Wilayah Pantura',
            'Permohonan Izin Liputan Media dan Dokumentasi Sidang Istimewa',
            'Pengajuan Pengadaan Souvenir Cinderamata Kunjungan Tamu Parlemen Daerah',
        ];

        $rejectionNotesNodin = [
            'Jadwal kegiatan bertabrakan dengan agenda Rapat Paripurna Dewan, silakan sesuaikan tanggal pelaksanaan.',
            'Daftar staf pendamping melebihi kuota SPT dinas, mohon revisi nama staf yang bertugas.',
            'Format usulan perihal belum sesuai tata naskah dinas baku sekretariat DPRD.',
        ];

        $nodinStatuses = ['Disetujui', 'Disetujui', 'Diajukan', 'Disetujui', 'Ditolak', 'Diajukan', 'Disetujui'];

        // NODIN Bagian A (15 records)
        for ($n = 1; $n <= 15; $n++) {
            $idx = $indexModels[($n - 1) % count($indexModels)];
            $sub = $subKegiatanModels[($n - 1) % count($subKegiatanModels)];
            $rc = $rincianModels[($n - 1) % count($rincianModels)];
            $month = str_pad(max(1, min(12, $n)), 2, '0', STR_PAD_LEFT);
            $dayStart = str_pad(($n * 2) % 24 + 1, 2, '0', STR_PAD_LEFT);
            $dayEnd = str_pad(($n * 2) % 24 + 3, 2, '0', STR_PAD_LEFT);

            $nomor = "{$idx->kode}/$n/PPUU/050.4/$currentYear";
            $staff1 = $staffNames[($n - 1) % count($staffNames)];
            $staff2 = $staffNames[$n % count($staffNames)];
            $status = $nodinStatuses[($n - 1) % count($nodinStatuses)];
            $catatan = $status === 'Ditolak' ? $rejectionNotesNodin[($n - 1) % count($rejectionNotesNodin)] : null;

            PengajuanNODIN::firstOrCreate(['nomor' => $nomor], [
                'bagian' => 'A',
                'kode' => $n,
                'index_kegiatan_id' => $idx->id,
                'subkegiatan_id' => $sub->id,
                'rincian_belanja_id' => $rc->id,
                'subject' => $nodinSubjects[($n - 1) % count($nodinSubjects)],
                'perihal' => 'Mohon petunjuk dan persetujuan pelaksanaan kegiatan '.strtolower($nodinSubjects[($n - 1) % count($nodinSubjects)]).' demi kelancaran tugas kedinasan DPRD Provinsi Jawa Timur.',
                'tanggal_pengajuan' => "$currentYear-$month-$dayStart",
                'tanggal_mulai' => "$currentYear-$month-$dayStart",
                'tanggal_selesai' => "$currentYear-$month-$dayEnd",
                'atas_nama' => "$staff1, $staff2",
                'nama_penginput' => $staff1,
                'tahun' => $currentYear,
                'status' => $status,
                'catatan_penolakan' => $catatan,
            ]);
        }

        // NODIN Bagian B (15 records)
        for ($n = 1; $n <= 15; $n++) {
            $idx = $indexModels[($n + 3) % count($indexModels)];
            $sub = $subKegiatanModels[($n + 2) % count($subKegiatanModels)];
            $rc = $rincianModels[($n + 1) % count($rincianModels)];
            $month = str_pad(max(1, min(12, $n)), 2, '0', STR_PAD_LEFT);
            $dayStart = str_pad(($n * 2) % 24 + 1, 2, '0', STR_PAD_LEFT);
            $dayEnd = str_pad(($n * 2) % 24 + 3, 2, '0', STR_PAD_LEFT);

            $nomor = "{$idx->kode}/$n/PPUU/050.4/$currentYear";
            $staff1 = $staffNames[($n + 2) % count($staffNames)];
            $staff2 = $staffNames[($n + 4) % count($staffNames)];
            $status = $nodinStatuses[$n % count($nodinStatuses)];
            $catatan = $status === 'Ditolak' ? $rejectionNotesNodin[$n % count($rejectionNotesNodin)] : null;

            PengajuanNODIN::firstOrCreate(['nomor' => $nomor, 'bagian' => 'B'], [
                'bagian' => 'B',
                'kode' => $n,
                'index_kegiatan_id' => $idx->id,
                'subkegiatan_id' => $sub->id,
                'rincian_belanja_id' => $rc->id,
                'subject' => $nodinSubjects[($n - 1) % count($nodinSubjects)].' Bagian B',
                'perihal' => 'Pengajuan nota dinas bagian B untuk keperluan '.strtolower($nodinSubjects[($n - 1) % count($nodinSubjects)]).' di lingkungan Sekretariat DPRD Provinsi Jawa Timur.',
                'tanggal_pengajuan' => "$currentYear-$month-$dayStart",
                'tanggal_mulai' => "$currentYear-$month-$dayStart",
                'tanggal_selesai' => "$currentYear-$month-$dayEnd",
                'atas_nama' => "$staff1, $staff2",
                'nama_penginput' => $staff1,
                'tahun' => $currentYear,
                'status' => $status,
                'catatan_penolakan' => $catatan,
            ]);
        }

        // 9. TAMBAH PENGAJUAN PUBLIKASI MEDIA (Total 25 data)
        $publikasiTitles = [
            'Liputan Khusus: Paripurna Pengesahan Raperda APBD Perubahan Provinsi Jatim',
            'Pemberitaan Kunjungan Kerja Komisi E dalam Meninjau Fasilitas RSUD Dr. Soetomo',
            'Opini dan Analisis Kebijakan: Penguatan Ketahanan Pangan Melalui Perda Petani Milenial',
            'Rilis Media: Pimpinan DPRD Jatim Menerima Aspirasi Guru Honorer dan Nakes',
            'Sosialisasi Pengawasan Pelaksanaan Pilkada Serentak Damai oleh Komisi A',
            'Liputan Mendalam: Evaluasi Pembangunan Jalur Lintas Selatan (JLS) Bersama Komisi D',
            'Pemberitaan Dialog Interaktif Komisi B dengan Pelaku UMKM Sidoarjo dan Surabaya',
            'Publikasi Foto Kegiatan Reses Masa Sidang II Pimpinan dan Anggota DPRD Jatim',
            'Rilis Pers: Rekomendasi Pansus LKPJ Gubernur untuk Peningkatan PAD Sektor Pajak',
            'Liputan Khusus: Uji Publik Draf Peraturan Daerah Penanggulangan Kemiskinan Ekstrem',
            'Berita Utama: Fasilitasi Dewan atas Keluhan Petani Terkait Alokasi Pupuk Bersubsidi',
            'Liputan Live Streaming Sidang Paripurna Istimewa HUT Provinsi Jawa Timur',
            'Pemberitaan Kunjungan Delegasi Parlemen Luar Negeri ke Gedung Indrapura',
            'Sosialisasi Perda Pencegahan Narkoba di Kalangan Generasi Muda Jatim',
            'Liputan Khusus: Sinergi Forkopimda Jatim Menjaga Stabilitas Ekonomi dan Sosial',
            'Publikasi Infografis: Capaian Program Legislasi Daerah (Prolegda) Tahun 2026',
            'Pemberitaan Audiensi Forum Kepala Desa se-Jawa Timur di Komisi A DPRD',
            'Rilis Pers: Pengawasan Distribusi Bantuan Sosial Bencana Alam di Wilayah Semeru',
            'Liputan Khusus: Fasilitasi Dewan dalam Penyelesaian Sengketa Lahan Warga',
            'Pemberitaan Sidang Paripurna Penyampaian Laporan Hasil Pemeriksaan BPK RI',
            'Publikasi Advertorial: Komitmen DPRD Jatim Mendorong Pendidikan Vokasi Unggul',
            'Liputan Dialog Kebangsaan Bersama Tokoh Agama dan Pemuda Lintas Komunitas',
            'Rilis Berita: Peninjauan Lapangan Kesiapan Sarana Mudik Lebaran oleh Komisi D',
            'Pemberitaan Focus Group Discussion (FGD) Kajian Naskah Akademik Bersama Akademisi Unair',
            'Liputan Rapat Koordinasi Bersama Kementerian ATR/BPN Terkait Reforma Agraria',
        ];

        $rejectionNotesPub = [
            'Kualitas draf foto materi publikasi kurang memenuhi resolusi cetak, mohon dikirimkan materi resolusi tinggi.',
            'Materi berita belum memperoleh persetujuan naskah dari pimpinan sidang, silakan konsultasikan kembali.',
            'Format ukuran advertorial melebihi alokasi slot media mitra yang disepakati.',
        ];

        $pubStatuses = ['Disetujui', 'Disetujui', 'Diajukan', 'Disetujui', 'Ditolak', 'Diajukan', 'Disetujui'];

        for ($p = 0; $p < 25; $p++) {
            $med = $mediaModels[$p % count($mediaModels)];
            $mth = str_pad(($p % 12) + 1, 2, '0', STR_PAD_LEFT);
            $dy = str_pad(($p * 3) % 28 + 1, 2, '0', STR_PAD_LEFT);
            $nomPub = rand(5, 25) * 1000000;
            $nomFc = rand(1, 5) * 100000;
            $status = $pubStatuses[$p % count($pubStatuses)];
            $staffPengaju = $staffNames[$p % count($staffNames)];
            $catatan = $status === 'Ditolak' ? $rejectionNotesPub[$p % count($rejectionNotesPub)] : null;

            PengajuanPublikasi::firstOrCreate([
                'media_id' => $med->id,
                'judul' => $publikasiTitles[$p],
            ], [
                'tanggal_tayang' => "$currentYear-$mth-$dy",
                'nominal_publikasi' => $nomPub,
                'nominal_fotocopy' => $nomFc,
                'status' => $status,
                'nama_penginput' => $staffPengaju,
                'catatan_penolakan' => $catatan,
            ]);
        }
    }
}
