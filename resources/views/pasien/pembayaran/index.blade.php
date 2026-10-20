<x-layouts.app title="Pembayaran">

    <h2 class="text-2xl font-bold text-slate-800 mb-6">Pembayaran</h2>

    @if($errors->any())
    <div class="alert alert-error mb-4 rounded-xl">
        <span>{{ $errors->first() }}</span>
    </div>
    @endif

    <div class="card bg-base-100 shadow-md rounded-2 border">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Tanggal Periksa</th>
                            <th class="px-6 py-4">Poli / Dokter</th>
                            <th class="px-6 py-4">Tagihan</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Bukti Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($tagihan as $item)
                        <tr class="border-t border-slate-100">
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->tgl_periksa)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                {{ optional($item->daftarPoli->jadwalPeriksa->dokter->poli)->nama_poli }}
                                / {{ $item->daftarPoli->jadwalPeriksa->dokter->nama }}
                            </td>
                            <td class="px-6 py-4 font-semibold">Rp {{ number_format($item->biaya_periksa, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @if($item->status_bayar === 'lunas')
                                <span class="badge badge-success text-white">Lunas</span>
                                @elseif($item->status_bayar === 'menunggu_verifikasi')
                                <span class="badge badge-warning">Menunggu Verifikasi</span>
                                @else
                                <span class="badge badge-error text-white">Belum Dibayar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status_bayar === 'belum_bayar')
                                <form method="POST" action="{{ route('pasien.pembayaran.store', $item->id) }}"
                                    enctype="multipart/form-data" class="flex items-center gap-2">
                                    @csrf
                                    <input type="file" name="bukti_bayar" accept="image/*"
                                        class="file-input file-input-bordered file-input-sm" required>
                                    <button type="submit" class="btn btn-sm btn-primary">Upload</button>
                                </form>
                                @else
                                <a href="{{ asset('storage/' . $item->bukti_bayar) }}" target="_blank"
                                    class="text-blue-600 underline">Lihat bukti</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-slate-400">Belum ada tagihan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>
