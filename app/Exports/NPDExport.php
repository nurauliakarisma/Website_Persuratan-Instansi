<?php

namespace App\Exports;

use App\Models\PengajuanNPD;
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

class NPDExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnWidths, WithStyles, WithColumnFormatting, WithDefaultStyles
{
    protected $bagian;

    public function __construct($bagian = null)
    {
        $this->bagian = $bagian;
    }

    public function headings(): array
    {
        return [
            'ID', 'Sub Kegiatan', 'Kode Rekening', 'Tanggal Pengajuan', 'Nomor NPD', 'Uraian Kegiatan', 'Anggaran', 'Keterangan'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'B' => 50,
            'C' => 50,
            'F' => 70,
            'G' => 20,
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
            'D' => [
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
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_DMYSLASH,
            'G' => '_("Rp"* #,##0.00_);_("Rp"* \(#,##0.00\);_("Rp"* "-"??_);_(@_)',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return PengajuanNPD::selectRaw('pengajuan_npd.id, CONCAT(sk.kode_subkegiatan, " - ", sk.ket_subkegiatan), CONCAT(rb.kode_rekening, " - ", rb.keterangan), tanggal_pengajuan, nomor, uraian_kegiatan, anggaran, status')
            ->leftJoin('alokasi_npd as a', 'pengajuan_npd.alokasi_npd_id', '=', 'a.id')
            ->leftJoin('subkegiatan as sk', 'a.subkegiatan_id', '=', 'sk.id')
            ->leftJoin('rincian_belanja as rb', 'a.rincian_belanja_id', '=', 'rb.id')
            ->where('pengajuan_npd.bagian', $this->bagian)
            ->get();
    }
}
