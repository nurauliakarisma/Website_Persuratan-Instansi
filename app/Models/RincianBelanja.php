<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RincianBelanja extends Model
{
    // use HasFactory;

    protected $table = 'rincian_belanja';
    public $guarded = ['id'];
}
