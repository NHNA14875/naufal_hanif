<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    private array $rules = [
        'nama_obat' => 'required|string|max:255',
        'kemasan'   => 'required|string|max:35',
        'harga'     => 'required|integer|min:0',
    ];

    private array $messages = [
        'nama_obat.required' => 'Nama obat wajib diisi.',
        'kemasan.required'   => 'Kemasan wajib diisi.',
        'kemasan.max'        => 'Kemasan maksimal 35 karakter.',
        'harga.required'     => 'Harga wajib diisi.',
        'harga.integer'      => 'Harga harus berupa angka.',
        'harga.min'          => 'Harga tidak boleh negatif.',
    ];

    public function index()
    {
        $obats = Obat::orderBy('nama_obat')->get();
        return view('admin.obat.index', compact('obats'));
    }

    public function create()
    {
        return view('admin.obat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules, $this->messages);

        Obat::create($validated);

        return redirect()->route('obat.index')->with('success', 'Data obat berhasil disimpan.');
    }

    public function edit(Obat $obat)
    {
        return view('admin.obat.edit', compact('obat'));
    }

    public function update(Request $request, Obat $obat)
    {
        $validated = $request->validate($this->rules, $this->messages);

        $obat->update($validated);

        return redirect()->route('obat.index')->with('success', 'Data obat berhasil diperbarui.');
    }

    public function destroy(Obat $obat)
    {
        $obat->delete();

        return redirect()->route('obat.index')->with('success', 'Data obat berhasil dihapus.');
    }
}
