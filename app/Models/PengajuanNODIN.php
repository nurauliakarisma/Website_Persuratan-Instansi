<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanNODIN extends Model
{
    // use HasFactory;

    protected $table = 'pengajuan_nodin';
    public $guarded = ['id'];

    public function indexKegiatan(): BelongsTo
    {
        return $this->belongsTo(IndexKegiatan::class, 'index_kegiatan_id');
    }

    public function subKegiatan(): BelongsTo
    {
        return $this->belongsTo(SubKegiatan::class, 'subkegiatan_id');
    }

    public function rincianBelanja(): BelongsTo
    {
        return $this->belongsTo(RincianBelanja::class, 'rincian_belanja_id');
    }
}
