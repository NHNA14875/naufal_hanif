<?php

namespace App\Exports;

use App\Models\Periksa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RiwayatPasienExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private int $dokterId)
    {
    }

    public function collection()
    {
        return Periksa::whereHas('daftarPoli.jadwalPeriksa', function ($query) {
            $query->where('id_dokter', $this->dokterId);
        })->with(['daftarPoli.pasien', 'detailPeriksas.obat'])
            ->orderBy('tgl_periksa', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return ['No Antrian', 'Nama Pasien', 'Keluhan', 'Tanggal Periksa', 'Catatan', 'Obat', 'Biaya'];
    }

    public function map($periksa): array
    {
        return [
            $periksa->daftarPoli->no_antrian,
            $periksa->daftarPoli->pasien->nama,
            $periksa->daftarPoli->keluhan,
            $periksa->tgl_periksa,
            $periksa->catatan,
            $periksa->detailPeriksas->map(fn ($d) => $d->obat->nama_obat)->implode(', '),
            $periksa->biaya_periksa,
        ];
    }
}
