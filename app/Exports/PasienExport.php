<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PasienExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::where('role', 'pasien')->get();
    }

    public function headings(): array
    {
        return ['Nama', 'Email', 'Alamat', 'No KTP', 'No HP', 'No RM'];
    }

    public function map($pasien): array
    {
        return [
            $pasien->nama,
            $pasien->email,
            $pasien->alamat,
            $pasien->no_ktp,
            $pasien->no_hp,
            $pasien->no_rm,
        ];
    }
}
