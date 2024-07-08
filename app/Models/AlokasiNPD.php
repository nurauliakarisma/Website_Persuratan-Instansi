<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlokasiNPD extends Model
{
    // use HasFactory;

    protected $table = 'alokasi_npd';
    public $guarded = ['id'];
    public $with = [
        'subKegiatan:id,kode_program,ket_program,kode_kegiatan,ket_kegiatan,kode_subkegiatan,ket_subkegiatan',
        'rincianBelanja:id,kode_rekening,keterangan'
    ];

    public function subKegiatan(): BelongsTo
    {
        return $this->belongsTo(SubKegiatan::class, 'subkegiatan_id');
    }

    public function rincianBelanja(): BelongsTo
    {
        return $this->belongsTo(RincianBelanja::class, 'rincian_belanja_id');
    }

    public function pengajuan(): HasMany
    {
        return $this->hasMany(PengajuanNPD::class, 'alokasi_npd_id', 'id');
    }
}
