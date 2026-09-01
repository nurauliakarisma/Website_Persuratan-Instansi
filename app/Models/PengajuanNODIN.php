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

    public function getStaffListAttribute(): array
    {
        if (empty($this->atas_nama)) {
            return [''];
        }

        $staffs = User::where('tipe', 'Staff')->pluck('nama')->filter(fn ($n) => ! empty(trim($n)))->sortByDesc(fn ($n) => strlen(trim($n)))->values();

        $matched = [];
        $remaining = trim($this->atas_nama);

        while (strlen($remaining) > 0) {
            $found = false;
            foreach ($staffs as $sName) {
                $sName = trim($sName);
                $len = strlen($sName);
                if (substr($remaining, 0, $len) === $sName) {
                    if (strlen($remaining) === $len || substr($remaining, $len, 2) === ', ') {
                        $matched[] = $sName;
                        $remaining = trim(substr($remaining, $len));
                        $remaining = ltrim($remaining, ', ');
                        $found = true;
                        break;
                    }
                }
            }
            if (! $found) {
                $pos = strpos($remaining, ', ');
                if ($pos !== false) {
                    $matched[] = substr($remaining, 0, $pos);
                    $remaining = trim(substr($remaining, $pos + 2));
                } else {
                    $matched[] = $remaining;
                    $remaining = '';
                }
            }
        }

        return ! empty($matched) ? $matched : [''];
    }
}
