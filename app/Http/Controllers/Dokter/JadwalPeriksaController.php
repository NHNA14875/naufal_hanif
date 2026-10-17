<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalPeriksaController extends Controller
{
    private array $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    private array $messages = [
        'hari.required'           => 'Hari wajib dipilih.',
        'hari.in'                 => 'Hari tidak valid.',
        'jam_mulai.required'      => 'Jam mulai wajib diisi.',
        'jam_mulai.date_format'   => 'Format jam mulai harus HH:MM.',
        'jam_selesai.required'    => 'Jam selesai wajib diisi.',
        'jam_selesai.date_format' => 'Format jam selesai harus HH:MM.',
        'jam_selesai.after'       => 'Jam selesai harus setelah jam mulai.',
        'status.required'         => 'Status wajib dipilih.',
    ];

    private function rules(bool $withStatus = false): array
    {
        $rules = [
            'hari'        => 'required|in:' . implode(',', $this->hari),
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ];

        if ($withStatus) {
            $rules['status'] = 'required|boolean';
        }

        return $rules;
    }

    // Ambil jadwal milik dokter yang sedang login saja
    private function findMine(string $id): JadwalPeriksa
    {
        return JadwalPeriksa::where('id_dokter', Auth::id())->findOrFail($id);
    }

    public function index()
    {
        $jadwals = JadwalPeriksa::where('id_dokter', Auth::id())
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
            ->orderBy('jam_mulai')
            ->get();

        return view('dokter.jadwal-periksa.index', compact('jadwals'));
    }

    public function create()
    {
        $hari = $this->hari;
        return view('dokter.jadwal-periksa.create', compact('hari'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages);

        JadwalPeriksa::create([
            'id_dokter'   => Auth::id(),
            'hari'        => $validated['hari'],
            'jam_mulai'   => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'status'      => true,
        ]);

        return redirect()->route('jadwal-periksa.index')->with('success', 'Jadwal periksa berhasil disimpan.');
    }

    public function edit(string $id)
    {
        $jadwal = $this->findMine($id);
        $hari = $this->hari;

        return view('dokter.jadwal-periksa.edit', compact('jadwal', 'hari'));
    }

    public function update(Request $request, string $id)
    {
        $jadwal = $this->findMine($id);

        $validated = $request->validate($this->rules(true), $this->messages);

        $jadwal->update($validated);

        return redirect()->route('jadwal-periksa.index')->with('success', 'Jadwal periksa berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $this->findMine($id)->delete();

        return redirect()->route('jadwal-periksa.index')->with('success', 'Jadwal periksa berhasil dihapus.');
    }
}
