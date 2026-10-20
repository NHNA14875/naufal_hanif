<?php

namespace App\Http\Controllers\Pasien;

use App\Events\AntrianUpdated;
use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PoliController extends Controller
{
    public function get()
    {
        $user = Auth::user();
        $polis = Poli::all();
        $jadwal = JadwalPeriksa::with('dokter', 'dokter.poli')->get();

        return view('pasien.daftar', [
            'user' => $user,
            'polis' => $polis,
            'jadwals' => $jadwal,
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'id_poli' => 'required|exists:poli,id',
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan' => 'nullable|string',
            'id_pasien' => 'required|exists:users,id',
        ]);

        // Pasien tidak boleh punya dua antrian aktif sekaligus
        $punyaAntrianAktif = DaftarPoli::where('id_pasien', Auth::id())
            ->whereDoesntHave('periksas')
            ->exists();

        if ($punyaAntrianAktif) {
            return redirect()
                ->back()
                ->with('error', 'Anda masih memiliki antrian aktif. Daftar kembali setelah pemeriksaan selesai.');
        }

        $jumlahSudahDaftar = DaftarPoli::where('id_jadwal', $request->id_jadwal)->count();

        DaftarPoli::create([
            'id_pasien' => Auth::id(),
            'id_jadwal' => $request->id_jadwal,
            'keluhan' => $request->keluhan,
            'no_antrian' => $jumlahSudahDaftar + 1,
        ]);

        AntrianUpdated::dispatch($request->id_jadwal);

        return redirect()
            ->back()
            ->with('message', 'Berhasil Mendaftar ke Poli')
            ->with('type', 'success');
    }
}