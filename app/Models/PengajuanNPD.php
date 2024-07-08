<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanNPD extends Model
{
    // use HasFactory;

    protected $table = 'pengajuan_npd';
    public $guarded = ['id'];

    public $with = [
        'alokasi:id,bagian,subkegiatan_id,rincian_belanja_id',
        'alokasi.subKegiatan:id,kode_subkegiatan',
        'alokasi.rincianBelanja:id,kode_rekening',
    ];

    public function alokasi(): BelongsTo
    {
        return $this->belongsTo(AlokasiNPD::class, 'alokasi_npd_id');
    }
}
