<?php

namespace App\Exports;

use App\Models\PengajuanNPD;
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

class NPDExport implements FromCollection, WithColumnFormatting, WithColumnWidths, WithCustomStartCell, WithDrawings, WithEvents, WithHeadings, WithStyles
{
    protected $bagian;

    protected $bagianLabel;

    protected $dataCount = 0;

    protected $totalAnggaran = 0;

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
            'SUB KEGIATAN',
            'KODE REKENING',
            'TANGGAL PENGAJUAN',
            'NOMOR NPD',
            'URAIAN KEGIATAN',
            'ANGGARAN (RP)',
            'STATUS',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 42,
            'C' => 40,
            'D' => 20,
            'E' => 24,
            'F' => 48,
            'G' => 22,
            'H' => 16,
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
            'D' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'G' => '_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"_);_(@_)',
        ];
    }

    public function collection()
    {
        $data = PengajuanNPD::selectRaw('pengajuan_npd.id, CONCAT(sk.kode_subkegiatan, " - ", sk.ket_subkegiatan) as subkegiatan_text, CONCAT(rb.kode_rekening, " - ", rb.keterangan) as rekening_text, tanggal_pengajuan, nomor, uraian_kegiatan, anggaran, status')
            ->leftJoin('alokasi_npd as a', 'pengajuan_npd.alokasi_npd_id', '=', 'a.id')
            ->leftJoin('subkegiatan as sk', 'a.subkegiatan_id', '=', 'sk.id')
            ->leftJoin('rincian_belanja as rb', 'a.rincian_belanja_id', '=', 'rb.id')
            ->where('pengajuan_npd.bagian', $this->bagian)
            ->orderBy('rb.kode_rekening', 'ASC')
            ->orderBy('pengajuan_npd.tanggal_pengajuan', 'ASC')
            ->orderBy('pengajuan_npd.id', 'ASC')
            ->get();

        $this->dataCount = $data->count();
        $this->totalAnggaran = $data->sum('anggaran');

        $no = 1;

        return $data->map(function ($row) use (&$no) {
            return [
                'no' => $no++,
                'subkegiatan' => $row->subkegiatan_text ?? '-',
                'rekening' => $row->rekening_text ?? '-',
                'tanggal_pengajuan' => $row->tanggal_pengajuan ? date('d/m/Y', strtotime($row->tanggal_pengajuan)) : '-',
                'nomor' => $row->nomor ?? '-',
                'uraian_kegiatan' => $row->uraian_kegiatan ?? '-',
                'anggaran' => (float) $row->anggaran,
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
                $sheet->mergeCells('B2:H2');
                $sheet->setCellValue('B2', 'PEMERINTAH PROVINSI JAWA TIMUR');
                $sheet->getStyle('B2')->getFont()->setBold(true)->setSize(13)->setName('Arial');
                $sheet->getStyle('B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('B3:H3');
                $sheet->setCellValue('B3', 'SEKRETARIAT DEWAN PERWAKILAN RAKYAT DAERAH');
                $sheet->getStyle('B3')->getFont()->setBold(true)->setSize(15)->setName('Arial');
                $sheet->getStyle('B3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('B4:H4');
                $sheet->setCellValue('B4', 'Jl. Indrapura No. 1, Surabaya, Jawa Timur 60175 | Telp: (031) 3530180 | Website: dprd.jatimprov.go.id');
                $sheet->getStyle('B4')->getFont()->setSize(9)->setItalic(true)->setName('Arial');
                $sheet->getStyle('B4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Garis Ganda Pembatas Kop Surat (Row 5)
                $sheet->getStyle('A5:H5')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);

                // -------------------------------------------------------------
                // 2. JUDUL DOKUMEN & INFO METADATA CETAK (Rows 7 - 9)
                // -------------------------------------------------------------
                $sheet->mergeCells('A7:H7');
                $sheet->setCellValue('A7', 'LAPORAN REKAPITULASI PENGAJUAN NOTA PENCAIRAN DANA (NPD)');
                $sheet->getStyle('A7')->getFont()->setBold(true)->setSize(12)->setName('Arial')->getColor()->setRGB('1E3A8A');
                $sheet->getStyle('A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Info Unit Kerja & Tanggal Cetak
                $userName = auth()->check() ? auth()->user()->nama : 'Sistem Persuratan';
                $userEmail = auth()->check() ? auth()->user()->email : '';
                $cetakOleh = $userName.($userEmail ? " ($userEmail)" : '');
                $waktuCetak = date('d F Y, H:i').' WIB';

                $sheet->mergeCells('A8:H8');
                $sheet->setCellValue('A8', "Unit Kerja / Bagian : {$this->bagianLabel}");
                $sheet->getStyle('A8')->getFont()->setBold(true)->setSize(9)->setName('Arial');

                $sheet->mergeCells('A9:H9');
                $sheet->setCellValue('A9', "Waktu Cetak : {$waktuCetak}    |    Dicetak Oleh : {$cetakOleh}    |    Total Data : {$this->dataCount} Pengajuan");
                $sheet->getStyle('A9')->getFont()->setItalic(true)->setSize(9)->setName('Arial')->getColor()->setRGB('475569');

                // Header Height
                $sheet->getRowDimension(11)->setRowHeight(28);

                // -------------------------------------------------------------
                // 3. STYLING DATA ROWS & BORDERS (Row 12 to End)
                // -------------------------------------------------------------
                $startRow = 12;
                $endRow = $startRow + $this->dataCount - 1;

                if ($this->dataCount > 0) {
                    // Set Row Height & Alignment
                    for ($r = $startRow; $r <= $endRow; $r++) {
                        $sheet->getRowDimension($r)->setRowHeight(24);

                        // Zebra striping
                        if ($r % 2 === 1) {
                            $sheet->getStyle("A{$r}:H{$r}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
                        }

                        // Alignments
                        $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                        $sheet->getStyle("B{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
                        $sheet->getStyle("C{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
                        $sheet->getStyle("D{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                        $sheet->getStyle("E{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                        $sheet->getStyle("F{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
                        $sheet->getStyle("G{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
                        $sheet->getStyle("H{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                    }

                    // Border untuk seluruh tabel data
                    $sheet->getStyle("A11:H{$endRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CBD5E1');

                    // -------------------------------------------------------------
                    // 4. SUMMARY ROW (TOTAL ANGGARAN)
                    // -------------------------------------------------------------
                    $summaryRow = $endRow + 1;
                    $sheet->getRowDimension($summaryRow)->setRowHeight(26);
                    $sheet->mergeCells("A{$summaryRow}:F{$summaryRow}");
                    $sheet->setCellValue("A{$summaryRow}", 'TOTAL ANGGARAN REALISASI NPD');
                    $sheet->setCellValue("G{$summaryRow}", "=SUM(G{$startRow}:G{$endRow})");
                    $sheet->setCellValue("H{$summaryRow}", '');

                    $sheet->getStyle("A{$summaryRow}:H{$summaryRow}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 10,
                            'color' => ['rgb' => '0F172A'],
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E2E8F0'],
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '94A3B8']],
                            'bottom' => ['borderStyle' => Border::BORDER_DOUBLE, 'color' => ['rgb' => '94A3B8']],
                        ],
                    ]);
                    $sheet->getStyle("A{$summaryRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("G{$summaryRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("G{$summaryRow}")->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"_);_(@_)');

                    $lastTableDataRow = $summaryRow;
                } else {
                    $sheet->mergeCells('A12:H12');
                    $sheet->setCellValue('A12', 'Tidak ada data pengajuan NPD.');
                    $sheet->getStyle('A12')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle('A11:H12')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CBD5E1');
                    $lastTableDataRow = 12;
                }

                // -------------------------------------------------------------
                // 5. BLOK TANDA TANGAN (SIGN-OFF)
                // -------------------------------------------------------------
                $signRow = $lastTableDataRow + 3;
                $userJabatan = auth()->check() && ! empty(auth()->user()->jabatan) ? auth()->user()->jabatan : 'Petugas Pengelola Persuratan';
                $userNip = auth()->check() && ! empty(auth()->user()->nip) ? 'NIP. '.auth()->user()->nip : '';

                $sheet->mergeCells("F{$signRow}:H{$signRow}");
                $sheet->setCellValue("F{$signRow}", 'Surabaya, '.date('d F Y'));
                $sheet->getStyle("F{$signRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $signRow++;
                $sheet->mergeCells("F{$signRow}:H{$signRow}");
                $sheet->setCellValue("F{$signRow}", $userJabatan);
                $sheet->getStyle("F{$signRow}")->getFont()->setBold(true);
                $sheet->getStyle("F{$signRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $signRow += 4;
                $sheet->mergeCells("F{$signRow}:H{$signRow}");
                $sheet->setCellValue("F{$signRow}", '( '.$userName.' )');
                $sheet->getStyle("F{$signRow}")->getFont()->setBold(true)->setUnderline(true);
                $sheet->getStyle("F{$signRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                if ($userNip) {
                    $signRow++;
                    $sheet->mergeCells("F{$signRow}:H{$signRow}");
                    $sheet->setCellValue("F{$signRow}", $userNip);
                    $sheet->getStyle("F{$signRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            },
        ];
    }
}
