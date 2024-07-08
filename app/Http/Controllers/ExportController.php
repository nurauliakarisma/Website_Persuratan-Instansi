<?php

namespace App\Http\Controllers;

use App\Exports\MediaExport;
use App\Exports\NODINExport;
use App\Exports\NPDExport;
use App\Models\PengajuanNODIN;
use App\Models\PengajuanNPD;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function nodin(Request $request, $bagian)
    {
        $timestamp = date('YmdHis');
        $filename = "nodin-$bagian-$timestamp.xlsx";
        return Excel::download(new NODINExport($bagian), $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function npd(Request $request, $bagian)
    {
        $timestamp = date('YmdHis');
        $filename = "npd-$bagian-$timestamp.xlsx";
        return Excel::download(new NPDExport($bagian), $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function media(Request $request)
    {
        $timestamp = date('YmdHis');
        $filename = "media-$timestamp.xlsx";
        return (new MediaExport)->download($filename, \Maatwebsite\Excel\Excel::XLSX);
    }
}
