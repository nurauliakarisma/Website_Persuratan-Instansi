<?php

namespace App\Exports;

use App\Models\PengajuanNODIN;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NODINExport implements FromCollection, WithColumnFormatting, WithColumnWidths, WithCustomStartCell, WithDrawings, WithEvents, WithHeadings, WithStyles
{
    protected $bagian;

    protected $bagianLabel;

    protected $dataCount = 0;

    public function __construct($bagian = null)
    {
        $this->bagian = in_array(strtolower($bagian), ['bagiandokinfo', 'dokinfo', 'a']) ? 'A' : 'B';
        $this->bagianLabel = $this->bagian === 'A'
            ? 'Bagian Dokumentasi dan Informasi (Dokinfo)'
            : 'Bagian Fasilitasi Penganggaran dan Pengawasan (FPP)';
    }

    public function startCell(): string
    {
        return 'A11';
    }

    public function headings(): array
    {
        return [
            'NO',
            'INDEX KEGIATAN',
            'NOMOR NOTA DINAS',
            'SUB KEGIATAN',
            'KODE REKENING',
            'TANGGAL',
            'PERIHAL',
            'ATAS NAMA',
            'PENGINPUT',
            'STATUS',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 30,
            'C' => 24,
            'D' => 40,
            'E' => 38,
            'F' => 16,
            'G' => 46,
            'H' => 24,
            'I' => 24,
            'J' => 16,
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $logoPath = public_path('images/avatars/logo.png');

        if (file_exists($logoPath)) {
            $drawing = new Drawing();
            $drawing->setName('Logo DPRD');
            $drawing->setDescription('Logo DPRD Jawa Timur');
            $drawing->setPath($logoPath);
            $drawing->setCoordinates('A2');
            $drawing->setHeight(72);
            $drawing->setOffsetX(8);
            $drawing->setOffsetY(4);
            $drawings[] = $drawing;
        }

        return $drawings;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            11 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E3A8A'], // Deep Corporate Navy Blue
                ],
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }

    public function collection()
    {
        $data = PengajuanNODIN::selectRaw('pengajuan_nodin.id, CONCAT(ik.kode, " - ", ik.keterangan) as index_text, nomor, CONCAT(sk.kode_subkegiatan, " - ", sk.ket_subkegiatan) as subkegiatan_text, CONCAT(rb.kode_rekening, " - ", rb.keterangan) as rekening_text, tanggal_pengajuan, perihal, atas_nama, nama_penginput, status')
            ->leftJoin('index_kegiatan as ik', 'pengajuan_nodin.index_kegiatan_id', '=', 'ik.id')
            ->leftJoin('subkegiatan as sk', 'pengajuan_nodin.subkegiatan_id', '=', 'sk.id')
            ->leftJoin('rincian_belanja as rb', 'pengajuan_nodin.rincian_belanja_id', '=', 'rb.id')
            ->where('bagian', $this->bagian)
            ->orderBy('rb.kode_rekening', 'ASC')
            ->orderBy('pengajuan_nodin.tanggal_pengajuan', 'ASC')
            ->orderBy('pengajuan_nodin.id', 'ASC')
            ->get();

        $this->dataCount = $data->count();

        $no = 1;

        return $data->map(function ($row) use (&$no) {
            return [
                'no' => $no++,
                'index' => $row->index_text ?? '-',
                'nomor' => $row->nomor ?? '-',
                'subkegiatan' => $row->subkegiatan_text ?? '-',
                'rekening' => $row->rekening_text ?? '-',
                'tanggal_pengajuan' => $row->tanggal_pengajuan ? date('d/m/Y', strtotime($row->tanggal_pengajuan)) : '-',
                'perihal' => $row->perihal ?? '-',
                'atas_nama' => $row->atas_nama ?? '-',
                'nama_penginput' => $row->nama_penginput ?? '-',
                'status' => $row->status ?? 'Diajukan',
            ];
        });
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // -------------------------------------------------------------
                // 1. KOP SURAT RESMI INSTANSI (Rows 2 - 4)
                // -------------------------------------------------------------
                $sheet->mergeCells('B2:J2');
                $sheet->setCellValue('B2', 'PEMERINTAH PROVINSI JAWA TIMUR');
                $sheet->getStyle('B2')->getFont()->setBold(true)->setSize(13)->setName('Arial');
                $sheet->getStyle('B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('B3:J3');
                $sheet->setCellValue('B3', 'SEKRETARIAT DEWAN PERWAKILAN RAKYAT DAERAH');
                $sheet->getStyle('B3')->getFont()->setBold(true)->setSize(15)->setName('Arial');
                $sheet->getStyle('B3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('B4:J4');
                $sheet->setCellValue('B4', 'Jl. Indrapura No. 1, Surabaya, Jawa Timur 60175 | Telp: (031) 3530180 | Website: dprd.jatimprov.go.id');
                $sheet->getStyle('B4')->getFont()->setSize(9)->setItalic(true)->setName('Arial');
                $sheet->getStyle('B4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Garis Ganda Pembatas Kop Surat (Row 5)
                $sheet->getStyle('A5:J5')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);

                // -------------------------------------------------------------
                // 2. JUDUL DOKUMEN & INFO METADATA CETAK (Rows 7 - 9)
                // -------------------------------------------------------------
                $sheet->mergeCells('A7:J7');
                $sheet->setCellValue('A7', 'LAPORAN REKAPITULASI PENGAJUAN NOTA DINAS (NODIN)');
                $sheet->getStyle('A7')->getFont()->setBold(true)->setSize(12)->setName('Arial')->getColor()->setRGB('1E3A8A');
                $sheet->getStyle('A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Info Unit Kerja & Tanggal Cetak
                $userName = auth()->check() ? auth()->user()->nama : 'Sistem Persuratan';
                $userEmail = auth()->check() ? auth()->user()->email : '';
                $cetakOleh = $userName.($userEmail ? " ($userEmail)" : '');
                $waktuCetak = date('d F Y, H:i').' WIB';

                $sheet->mergeCells('A8:J8');
                $sheet->setCellValue('A8', "Unit Kerja / Bagian : {$this->bagianLabel}");
                $sheet->getStyle('A8')->getFont()->setBold(true)->setSize(9)->setName('Arial');

                $sheet->mergeCells('A9:J9');
                $sheet->setCellValue('A9', "Waktu Cetak : {$waktuCetak}    |    Dicetak Oleh : {$cetakOleh}    |    Total Data : {$this->dataCount} Nota Dinas");
                $sheet->getStyle('A9')->getFont()->setItalic(true)->setSize(9)->setName('Arial')->getColor()->setRGB('475569');

                // Header Height
                $sheet->getRowDimension(11)->setRowHeight(28);

                // -------------------------------------------------------------
                // 3. STYLING DATA ROWS & BORDERS (Row 12 to End)
                // -------------------------------------------------------------
                $startRow = 12;
                $endRow = $startRow + $this->dataCount - 1;

                if ($this->dataCount > 0) {
                    for ($r = $startRow; $r <= $endRow; $r++) {
                        $sheet->getRowDimension($r)->setRowHeight(24);

                        // Zebra striping
                        if ($r % 2 === 1) {
                            $sheet->getStyle("A{$r}:J{$r}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
                        }

                        // Alignments
                        $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                        $sheet->getStyle("B{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
                        $sheet->getStyle("C{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                        $sheet->getStyle("D{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
                        $sheet->getStyle("E{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
                        $sheet->getStyle("F{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                        $sheet->getStyle("G{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
                        $sheet->getStyle("H{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);
                        $sheet->getStyle("I{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER);
                        $sheet->getStyle("J{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                    }

                    // Border untuk seluruh tabel data
                    $sheet->getStyle("A11:J{$endRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CBD5E1');
                    $lastTableDataRow = $endRow;
                } else {
                    $sheet->mergeCells('A12:J12');
                    $sheet->setCellValue('A12', 'Tidak ada data pengajuan Nota Dinas.');
                    $sheet->getStyle('A12')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle('A11:J12')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CBD5E1');
                    $lastTableDataRow = 12;
                }

                // -------------------------------------------------------------
                // 4. BLOK TANDA TANGAN (SIGN-OFF)
                // -------------------------------------------------------------
                $signRow = $lastTableDataRow + 3;
                $userJabatan = auth()->check() && ! empty(auth()->user()->jabatan) ? auth()->user()->jabatan : 'Petugas Pengelola Persuratan';
                $userNip = auth()->check() && ! empty(auth()->user()->nip) ? 'NIP. '.auth()->user()->nip : '';

                $sheet->mergeCells("H{$signRow}:J{$signRow}");
                $sheet->setCellValue("H{$signRow}", 'Surabaya, '.date('d F Y'));
                $sheet->getStyle("H{$signRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $signRow++;
                $sheet->mergeCells("H{$signRow}:J{$signRow}");
                $sheet->setCellValue("H{$signRow}", $userJabatan);
                $sheet->getStyle("H{$signRow}")->getFont()->setBold(true);
                $sheet->getStyle("H{$signRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $signRow += 4;
                $sheet->mergeCells("H{$signRow}:J{$signRow}");
                $sheet->setCellValue("H{$signRow}", '( '.$userName.' )');
                $sheet->getStyle("H{$signRow}")->getFont()->setBold(true)->setUnderline(true);
                $sheet->getStyle("H{$signRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                if ($userNip) {
                    $signRow++;
                    $sheet->mergeCells("H{$signRow}:J{$signRow}");
                    $sheet->setCellValue("H{$signRow}", $userNip);
                    $sheet->getStyle("H{$signRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // -------------------------------------------------------------
                // 5. SYSTEM WATERMARK & DOCUMENT PROPERTIES
                // -------------------------------------------------------------
                $sheet->getParent()->getProperties()
                    ->setCreator('Nur Aulia Karisma Dewi')
                    ->setLastModifiedBy('Nur Aulia Karisma Dewi')
                    ->setTitle('Laporan NODIN - SIM Persuratan DPRD Jatim')
                    ->setDescription('Sistem Informasi Manajemen Persuratan & Pengelolaan Anggaran Sekretariat DPRD Jawa Timur dikembangkan oleh Nur Aulia Karisma Dewi')
                    ->setCompany('Sekretariat DPRD Provinsi Jawa Timur');

                $footerRow = $signRow + 2;
                $sheet->mergeCells("A{$footerRow}:J{$footerRow}");
                $sheet->setCellValue("A{$footerRow}", 'Dokumen ini dibuat otomatis oleh Sistem Informasi Persuratan DPRD Jawa Timur • Hak Cipta © 2026 Nur Aulia Karisma Dewi');
                $sheet->getStyle("A{$footerRow}")->getFont()->setSize(8)->setItalic(true)->getColor()->setRGB('94A3B8');
                $sheet->getStyle("A{$footerRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            },
        ];
    }
}
