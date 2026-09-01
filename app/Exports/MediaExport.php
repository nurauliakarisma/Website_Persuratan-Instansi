<?php

namespace App\Exports;

use App\Models\Media;
use Maatwebsite\Excel\Concerns\Exportable;
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
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MediaExport implements FromCollection, WithColumnFormatting, WithColumnWidths, WithCustomStartCell, WithDrawings, WithEvents, WithHeadings, WithStyles
{
    use Exportable;

    protected $dataCount = 0;

    public function startCell(): string
    {
        return 'A11';
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAMA MEDIA MASSA',
            'HARGA PENAWARAN (RP)',
            'HARGA DEAL (RP)',
            'HARGA + PPN 11% (RP)',
            'STATUS KERJASAMA',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 38,
            'C' => 24,
            'D' => 24,
            'E' => 26,
            'F' => 20,
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
            'C' => '_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"_);_(@_)',
            'D' => '_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"_);_(@_)',
            'E' => '_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"_);_(@_)',
        ];
    }

    public function collection()
    {
        $data = Media::select('id', 'nama', 'harga_penawaran', 'harga_deal', 'harga_total', 'status')
            ->orderBy('id', 'ASC')
            ->get();

        $this->dataCount = $data->count();

        $no = 1;

        return $data->map(function ($row) use (&$no) {
            return [
                'no' => $no++,
                'nama' => $row->nama ?? '-',
                'harga_penawaran' => (float) $row->harga_penawaran,
                'harga_deal' => (float) $row->harga_deal,
                'harga_total' => (float) $row->harga_total,
                'status' => $row->status ?? 'Aktif',
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
                $sheet->mergeCells('B2:F2');
                $sheet->setCellValue('B2', 'PEMERINTAH PROVINSI JAWA TIMUR');
                $sheet->getStyle('B2')->getFont()->setBold(true)->setSize(13)->setName('Arial');
                $sheet->getStyle('B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('B3:F3');
                $sheet->setCellValue('B3', 'SEKRETARIAT DEWAN PERWAKILAN RAKYAT DAERAH');
                $sheet->getStyle('B3')->getFont()->setBold(true)->setSize(15)->setName('Arial');
                $sheet->getStyle('B3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('B4:F4');
                $sheet->setCellValue('B4', 'Jl. Indrapura No. 1, Surabaya, Jawa Timur 60175 | Telp: (031) 3530180 | Website: dprd.jatimprov.go.id');
                $sheet->getStyle('B4')->getFont()->setSize(9)->setItalic(true)->setName('Arial');
                $sheet->getStyle('B4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Garis Ganda Pembatas Kop Surat (Row 5)
                $sheet->getStyle('A5:F5')->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);

                // -------------------------------------------------------------
                // 2. JUDUL DOKUMEN & INFO METADATA CETAK (Rows 7 - 9)
                // -------------------------------------------------------------
                $sheet->mergeCells('A7:F7');
                $sheet->setCellValue('A7', 'LAPORAN REKAPITULASI DATA MITRA PUBLIKASI MEDIA MASSA');
                $sheet->getStyle('A7')->getFont()->setBold(true)->setSize(12)->setName('Arial')->getColor()->setRGB('1E3A8A');
                $sheet->getStyle('A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Info Unit Kerja & Tanggal Cetak
                $userName = auth()->check() ? auth()->user()->nama : 'Sistem Persuratan';
                $userEmail = auth()->check() ? auth()->user()->email : '';
                $cetakOleh = $userName.($userEmail ? " ($userEmail)" : '');
                $waktuCetak = date('d F Y, H:i').' WIB';

                $sheet->mergeCells('A8:F8');
                $sheet->setCellValue('A8', 'Unit Kerja / Bagian : Bagian Dokumentasi dan Informasi (Dokinfo)');
                $sheet->getStyle('A8')->getFont()->setBold(true)->setSize(9)->setName('Arial');

                $sheet->mergeCells('A9:F9');
                $sheet->setCellValue('A9', "Waktu Cetak : {$waktuCetak}    |    Dicetak Oleh : {$cetakOleh}    |    Total Rekanan : {$this->dataCount} Mitra Media");
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
                            $sheet->getStyle("A{$r}:F{$r}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
                        }

                        // Alignments
                        $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                        $sheet->getStyle("B{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
                        $sheet->getStyle("C{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
                        $sheet->getStyle("D{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
                        $sheet->getStyle("E{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setVertical(Alignment::VERTICAL_CENTER);
                        $sheet->getStyle("F{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                    }

                    // Border untuk seluruh tabel data
                    $sheet->getStyle("A11:F{$endRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CBD5E1');

                    // -------------------------------------------------------------
                    // 4. SUMMARY ROW (TOTAL HARGA DEAL & PPN)
                    // -------------------------------------------------------------
                    $summaryRow = $endRow + 1;
                    $sheet->getRowDimension($summaryRow)->setRowHeight(26);
                    $sheet->mergeCells("A{$summaryRow}:B{$summaryRow}");
                    $sheet->setCellValue("A{$summaryRow}", 'TOTAL KESEPAKATAN NILAI KONTRAK');
                    $sheet->setCellValue("C{$summaryRow}", "=SUM(C{$startRow}:C{$endRow})");
                    $sheet->setCellValue("D{$summaryRow}", "=SUM(D{$startRow}:D{$endRow})");
                    $sheet->setCellValue("E{$summaryRow}", "=SUM(E{$startRow}:E{$endRow})");
                    $sheet->setCellValue("F{$summaryRow}", '');

                    $sheet->getStyle("A{$summaryRow}:F{$summaryRow}")->applyFromArray([
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
                    $sheet->getStyle("C{$summaryRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("D{$summaryRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle("E{$summaryRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                    $sheet->getStyle("C{$summaryRow}")->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"_);_(@_)');
                    $sheet->getStyle("D{$summaryRow}")->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"_);_(@_)');
                    $sheet->getStyle("E{$summaryRow}")->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"_);_(@_)');

                    $lastTableDataRow = $summaryRow;
                } else {
                    $sheet->mergeCells('A12:F12');
                    $sheet->setCellValue('A12', 'Tidak ada data mitra media.');
                    $sheet->getStyle('A12')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
                    $sheet->getStyle('A11:F12')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('CBD5E1');
                    $lastTableDataRow = 12;
                }

                // -------------------------------------------------------------
                // 5. BLOK TANDA TANGAN (SIGN-OFF)
                // -------------------------------------------------------------
                $signRow = $lastTableDataRow + 3;
                $userJabatan = auth()->check() && ! empty(auth()->user()->jabatan) ? auth()->user()->jabatan : 'Petugas Pengelola Persuratan';
                $userNip = auth()->check() && ! empty(auth()->user()->nip) ? 'NIP. '.auth()->user()->nip : '';

                $sheet->mergeCells("E{$signRow}:F{$signRow}");
                $sheet->setCellValue("E{$signRow}", 'Surabaya, '.date('d F Y'));
                $sheet->getStyle("E{$signRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $signRow++;
                $sheet->mergeCells("E{$signRow}:F{$signRow}");
                $sheet->setCellValue("E{$signRow}", $userJabatan);
                $sheet->getStyle("E{$signRow}")->getFont()->setBold(true);
                $sheet->getStyle("E{$signRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $signRow += 4;
                $sheet->mergeCells("E{$signRow}:F{$signRow}");
                $sheet->setCellValue("E{$signRow}", '( '.$userName.' )');
                $sheet->getStyle("E{$signRow}")->getFont()->setBold(true)->setUnderline(true);
                $sheet->getStyle("E{$signRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                if ($userNip) {
                    $signRow++;
                    $sheet->mergeCells("E{$signRow}:F{$signRow}");
                    $sheet->setCellValue("E{$signRow}", $userNip);
                    $sheet->getStyle("E{$signRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            },
        ];
    }
}
