<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DokterExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::where('role', 'dokter')->with('poli')->get();
    }

    public function headings(): array
    {
        return ['Nama', 'Email', 'Alamat', 'No KTP', 'No HP', 'Poli'];
    }

    public function map($dokter): array
    {
        return [
            $dokter->nama,
            $dokter->email,
            $dokter->alamat,
            $dokter->no_ktp,
            $dokter->no_hp,
            optional($dokter->poli)->nama_poli,
        ];
    }
}
