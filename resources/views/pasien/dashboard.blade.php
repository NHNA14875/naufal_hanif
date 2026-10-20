<x-layouts.app title="Dashboard Pasien">

    <h2 class="text-2xl font-bold text-slate-800 mb-6">Dashboard Pasien</h2>

    {{-- Banner antrian aktif --}}
    @if($antrianAktif)
    @php $j = $antrianAktif->jadwalPeriksa; @endphp
    <div class="rounded-2xl bg-gradient-to-r from-[#2d4499] to-indigo-500 text-white p-6 mb-6 shadow-md">
        <p class="text-sm opacity-80 mb-1">Antrian Aktif Anda</p>
        <h3 class="text-xl font-bold mb-4">
            {{ optional($j->dokter->poli)->nama_poli }} &mdash; {{ $j->dokter->nama }}
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <p class="text-xs opacity-80">Jadwal Periksa</p>
                <p class="font-semibold">{{ $j->hari }}, {{ substr($j->jam_mulai, 0, 5) }} - {{ substr($j->jam_selesai, 0, 5) }}</p>
            </div>
            <div>
                <p class="text-xs opacity-80">Nomor Antrian Anda</p>
                <p class="text-3xl font-bold">{{ $antrianAktif->no_antrian }}</p>
            </div>
            <div>
                <p class="text-xs opacity-80">Sedang Dilayani</p>
                <p class="text-3xl font-bold" data-nomor-jadwal="{{ $j->id }}">{{ $dilayani[$j->id] ?? '-' }}</p>
            </div>
        </div>
    </div>
    @else
    <div class="rounded-2xl border border-dashed border-slate-300 text-slate-400 text-center py-8 mb-6">
        Anda belum memiliki antrian aktif.
    </div>
    @endif

    {{-- Tabel jadwal poliklinik --}}
    <div class="card bg-base-100 shadow-md rounded-2 border">
        <div class="card-body p-0">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="font-bold text-slate-800">Jadwal Poliklinik &amp; Antrian</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Poli</th>
                            <th class="px-6 py-4">Dokter</th>
                            <th class="px-6 py-4">Hari</th>
                            <th class="px-6 py-4">Jam Periksa</th>
                            <th class="px-6 py-4">Sedang Dilayani</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($jadwals as $jadwal)
                        <tr class="border-t border-slate-100">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">{{ optional($jadwal->dokter->poli)->nama_poli }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $jadwal->dokter->nama }}</td>
                            <td class="px-6 py-4">{{ $jadwal->hari }}</td>
                            <td class="px-6 py-4">{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                            <td class="px-6 py-4 font-bold" data-nomor-jadwal="{{ $jadwal->id }}">{{ $dilayani[$jadwal->id] ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-400">Belum ada jadwal</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Update nomor antrian otomatis lewat Laravel Echo (Reverb)
        document.addEventListener('DOMContentLoaded', function () {
            if (!window.Echo) return;

            window.Echo.channel('antrian').listen('.antrian.updated', function (e) {
                document.querySelectorAll('[data-nomor-jadwal="' + e.idJadwal + '"]').forEach(function (el) {
                    el.textContent = e.nomor ?? '-';
                });
            });
        });
    </script>
    @endpush

</x-layouts.app>
