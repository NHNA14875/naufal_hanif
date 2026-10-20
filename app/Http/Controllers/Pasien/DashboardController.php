<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Antrian aktif pasien (belum diperiksa)
        $antrianAktif = DaftarPoli::with('jadwalPeriksa.dokter.poli')
            ->where('id_pasien', Auth::id())
            ->whereDoesntHave('periksas')
            ->latest()
            ->first();

        $jadwals = JadwalPeriksa::with('dokter.poli')->get();

        // Nomor yang sedang dilayani per jadwal (1 query)
        $dilayani = DaftarPoli::whereDoesntHave('periksas')
            ->selectRaw('id_jadwal, MIN(no_antrian) as nomor')
            ->groupBy('id_jadwal')
            ->pluck('nomor', 'id_jadwal');

        return view('pasien.dashboard', compact('antrianAktif', 'jadwals', 'dilayani'));
    }
}
