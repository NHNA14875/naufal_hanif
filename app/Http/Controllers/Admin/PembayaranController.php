<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use App\Notifications\PembayaranDiverifikasi;

class PembayaranController extends Controller
{
    public function index()
    {
        $tagihan = Periksa::where('status_bayar', '!=', 'belum_bayar')
            ->with('daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.dokter')
            ->orderByRaw("status_bayar = 'menunggu_verifikasi' desc")
            ->latest()
            ->get();

        return view('admin.pembayaran.index', compact('tagihan'));
    }

    public function konfirmasi($id)
    {
        $periksa = Periksa::with('daftarPoli.pasien')
            ->where('status_bayar', 'menunggu_verifikasi')
            ->findOrFail($id);

        $periksa->update(['status_bayar' => 'lunas']);

        $periksa->daftarPoli->pasien->notify(new PembayaranDiverifikasi($periksa));

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi (lunas).');
    }
}
