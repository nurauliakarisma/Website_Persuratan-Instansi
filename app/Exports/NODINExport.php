<?php

namespace App\Exports;

use App\Models\PengajuanNODIN;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NODINExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnWidths, WithStyles, WithColumnFormatting, WithDefaultStyles
{
    protected $bagian;

    public function __construct($bagian = null)
    {
        $this->bagian = $bagian;
    }

    public function headings(): array
    {
        return [
            'ID', 'Index', 'Nomor NODIN', 'Sub Kegiatan', 'Kode Rekening', 'Tanggal', 'Perihal', 'Atas Nama', 'Nama Penginput', 'Status'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'B' => 50,
            'D' => 50,
            'E' => 50,
            'G' => 70,
        ];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return [
            'alignment' => [
                'wrapText' => true,
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            'A' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            'C' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            'F' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            'H' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            'I' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            'J' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_DATE_DMYSLASH,
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return PengajuanNODIN::selectRaw('pengajuan_nodin.id, CONCAT(ik.kode, " - ", ik.keterangan), nomor, CONCAT(sk.kode_subkegiatan, " - ", sk.ket_subkegiatan), CONCAT(rb.kode_rekening, " - ", rb.keterangan), tanggal_pengajuan, perihal, atas_nama, nama_penginput, status')
            ->leftJoin('index_kegiatan as ik', 'pengajuan_nodin.index_kegiatan_id', '=', 'ik.id')
            ->leftJoin('subkegiatan as sk', 'pengajuan_nodin.subkegiatan_id', '=', 'sk.id')
            ->leftJoin('rincian_belanja as rb', 'pengajuan_nodin.rincian_belanja_id', '=', 'rb.id')
            ->where('bagian', $this->bagian)
            ->get();
    }
}
