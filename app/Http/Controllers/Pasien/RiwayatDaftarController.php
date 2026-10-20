<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use Illuminate\Support\Facades\Auth;

class RiwayatDaftarController extends Controller
{
    public function index()
    {
        $riwayat = DaftarPoli::with(['jadwalPeriksa.dokter.poli', 'periksas'])
            ->where('id_pasien', Auth::id())
            ->latest()
            ->get();

        return view('pasien.riwayat.index', compact('riwayat'));
    }

    public function show($id)
    {
        $daftar = DaftarPoli::with(['jadwalPeriksa.dokter.poli', 'periksas.detailPeriksas.obat'])
            ->where('id_pasien', Auth::id())
            ->findOrFail($id);

        $periksa = $daftar->periksas->first();
        abort_if(!$periksa, 404);

        return view('pasien.riwayat.show', compact('daftar', 'periksa'));
    }
}
