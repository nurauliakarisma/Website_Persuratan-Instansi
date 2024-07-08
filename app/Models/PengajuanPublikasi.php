<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanPublikasi extends Model
{
    // use HasFactory;

    protected $table = 'pengajuan_publikasi';
    public $guarded = ['id'];
    public $with = ['media'];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
}
