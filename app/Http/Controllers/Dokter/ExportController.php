<?php

namespace App\Http\Controllers\Dokter;

use App\Exports\JadwalPeriksaExport;
use App\Exports\RiwayatPasienExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function jadwal()
    {
        return Excel::download(new JadwalPeriksaExport(Auth::id()), 'jadwal-periksa.xlsx');
    }

    public function riwayat()
    {
        return Excel::download(new RiwayatPasienExport(Auth::id()), 'riwayat-pasien.xlsx');
    }
}
