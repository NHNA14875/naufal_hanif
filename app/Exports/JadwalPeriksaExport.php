<?php

namespace App\Exports;

use App\Models\JadwalPeriksa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class JadwalPeriksaExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private int $dokterId)
    {
    }

    public function collection()
    {
        return JadwalPeriksa::where('id_dokter', $this->dokterId)->get();
    }

    public function headings(): array
    {
        return ['Hari', 'Jam Mulai', 'Jam Selesai'];
    }

    public function map($jadwal): array
    {
        return [
            $jadwal->hari,
            substr($jadwal->jam_mulai, 0, 5),
            substr($jadwal->jam_selesai, 0, 5),
        ];
    }
}
