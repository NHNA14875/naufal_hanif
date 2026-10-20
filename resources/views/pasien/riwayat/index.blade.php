<x-layouts.app title="Riwayat Pendaftaran">

    <h2 class="text-2xl font-bold text-slate-800 mb-6">Riwayat Pendaftaran Poli</h2>

    <div class="card bg-base-100 shadow-md rounded-2 border">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Poli</th>
                            <th class="px-6 py-4">Dokter</th>
                            <th class="px-6 py-4">Tanggal Jadwal</th>
                            <th class="px-6 py-4">No Antrian</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($riwayat as $item)
                        @php $sudah = $item->periksas->isNotEmpty(); @endphp
                        <tr class="border-t border-slate-100 hover:bg-slate-50">
                            <td class="px-6 py-4">{{ optional($item->jadwalPeriksa->dokter->poli)->nama_poli }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $item->jadwalPeriksa->dokter->nama }}</td>
                            <td class="px-6 py-4">{{ $item->jadwalPeriksa->hari }}, {{ $item->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">{{ $item->no_antrian }}</td>
                            <td class="px-6 py-4">
                                @if($sudah)
                                <span class="badge badge-success text-white">Sudah Diperiksa</span>
                                @else
                                <span class="badge badge-warning">Menunggu</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($sudah)
                                <a href="{{ route('pasien.riwayat.show', $item->id) }}"
                                    class="btn btn-sm bg-blue-500 hover:bg-blue-600 text-white border-none rounded-lg px-4">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-400">Belum ada riwayat pendaftaran</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>
