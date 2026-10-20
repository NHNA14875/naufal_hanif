<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\Periksa;
use App\Notifications\PembayaranDiunggah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index()
    {
        $tagihan = Periksa::whereHas('daftarPoli', function ($query) {
            $query->where('id_pasien', Auth::id());
        })->with('daftarPoli.jadwalPeriksa.dokter.poli')
            ->latest()
            ->get();

        return view('pasien.pembayaran.index', compact('tagihan'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|max:2048',
        ]);

        $periksa = Periksa::whereHas('daftarPoli', function ($query) {
            $query->where('id_pasien', Auth::id());
        })->with('daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.dokter')->findOrFail($id);

        // Hanya boleh upload sekali (mencegah notifikasi ganda)
        if ($periksa->status_bayar !== 'belum_bayar') {
            return back()->with('error', 'Bukti pembayaran sudah pernah diupload.');
        }

        $path = $request->file('bukti_bayar')->store('bukti-bayar', 'public');

        $periksa->update([
            'bukti_bayar' => $path,
            'status_bayar' => 'menunggu_verifikasi',
        ]);

        $periksa->daftarPoli->jadwalPeriksa->dokter->notify(new PembayaranDiunggah($periksa));

        return back()->with('success', 'Bukti pembayaran berhasil diupload, menunggu verifikasi admin.');
    }
}
