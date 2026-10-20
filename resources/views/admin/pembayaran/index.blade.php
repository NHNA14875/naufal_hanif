<x-layouts.app title="Verifikasi Pembayaran">

    <h2 class="text-2xl font-bold text-slate-800 mb-6">Verifikasi Pembayaran</h2>

    <div class="card bg-base-100 shadow-md rounded-2 border">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Pasien</th>
                            <th class="px-6 py-4">Dokter</th>
                            <th class="px-6 py-4">Tagihan</th>
                            <th class="px-6 py-4">Bukti</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($tagihan as $item)
                        <tr class="border-t border-slate-100">
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $item->daftarPoli->pasien->nama }}</td>
                            <td class="px-6 py-4">{{ $item->daftarPoli->jadwalPeriksa->dokter->nama }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($item->biaya_periksa, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ asset('storage/' . $item->bukti_bayar) }}" target="_blank"
                                    class="text-blue-600 underline">Lihat bukti</a>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status_bayar === 'lunas')
                                <span class="badge badge-success text-white">Lunas</span>
                                @else
                                <span class="badge badge-warning">Menunggu Verifikasi</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($item->status_bayar === 'menunggu_verifikasi')
                                <form method="POST" action="{{ route('admin.pembayaran.konfirmasi', $item->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success text-white">
                                        <i class="fas fa-check"></i> Konfirmasi
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-400">Belum ada pembayaran yang diupload</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>
