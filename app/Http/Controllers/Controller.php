<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function errorMessage($errCode)
    {
        switch ($errCode) {
            case 23000:
                $message = 'Data yang ingin ditambahkan sudah tersedia.';
                break;
            default:
                $message = 'Terjadi kesalahan pada sistem.';
                break;
        }
        return $message;
    }
}
