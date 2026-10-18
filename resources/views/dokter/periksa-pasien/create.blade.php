<x-layouts.app title="Periksa Pasien">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('periksa-pasien.index') }}" class="inline-flex items-center justify-center w-9 h-9 
                  rounded-lg bg-slate-100 text-slate-500 
                  hover:bg-slate-200 transition">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>

        <h2 class="text-2xl font-bold text-slate-800">
            Periksa Pasien
        </h2>
    </div>

    {{-- Card --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-8">

            <form action="{{ route('periksa-pasien.store') }}" method="POST">
                @csrf

                <input type="hidden" name="id_daftar_poli" value="{{ $id }}">
                <input type="hidden" name="obat_json" id="obat_json" value="[]">
                <input type="hidden" name="biaya_periksa" id="biaya_periksa" value="0">

                {{-- Pilih Obat --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        Pilih Obat
                    </label>

                    <div class="flex gap-3">
                        <select id="select_obat"
                            class="w-full px-4 py-2 rounded-lg border-2 border-slate-300 bg-white
                                   focus:border-primary focus:outline-none">
                            <option value="">-- Pilih Obat --</option>
                            @foreach ($obats as $obat)
                            <option value="{{ $obat->id }}" data-nama="{{ $obat->nama_obat }}"
                                data-harga="{{ $obat->harga }}">
                                {{ $obat->nama_obat }} - {{ $obat->kemasan }} (Rp {{ number_format($obat->harga, 0, ',', '.') }})
                            </option>
                            @endforeach
                        </select>

                        <button type="button" id="btn_tambah_obat"
                            class="px-5 py-2 rounded-lg bg-primary hover:bg-primary/90 
                                   text-white font-semibold text-sm transition whitespace-nowrap">
                            <i class="fas fa-plus mr-1"></i> Tambah
                        </button>
                    </div>

                    @error('obat_json')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Obat Terpilih --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        Obat Terpilih
                    </label>

                    <ul id="list_obat" class="space-y-2">
                        <li id="obat_kosong" class="text-sm text-slate-400">Belum ada obat dipilih</li>
                    </ul>
                </div>

                {{-- Total Harga --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        Total Harga
                    </label>

                    <div class="flex items-center border-2 border-slate-300 rounded-lg px-4 py-2 bg-slate-50">
                        <span class="text-slate-500 text-sm font-semibold mr-2">Rp</span>
                        <input type="text" id="total_harga" value="0" readonly
                            class="w-full bg-transparent focus:outline-none font-semibold text-slate-800">
                    </div>

                    <p class="text-xs text-slate-400 mt-1">
                        Biaya jasa dokter Rp 150.000 ditambahkan otomatis saat disimpan.
                    </p>

                    @error('biaya_periksa')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        Catatan
                    </label>

                    <textarea name="catatan" rows="4" placeholder="Tuliskan catatan atau hasil pemeriksaan..."
                        class="w-full px-4 py-2 rounded-lg border-2 border-slate-300 
                               focus:border-primary focus:outline-none
                               @error('catatan') border-red-500 @enderror">{{ old('catatan') }}</textarea>

                    @error('catatan')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Button --}}
                <div class="flex gap-3">
                    <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-primary hover:bg-primary/90 
                               text-white font-semibold text-sm transition">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>

                    <a href="{{ route('periksa-pasien.index') }}"
                        class="px-6 py-2.5 rounded-lg bg-slate-100 hover:bg-slate-200 
                               text-slate-600 font-semibold text-sm transition">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

    <script>
        const selectObat = document.getElementById('select_obat');
        const btnTambah = document.getElementById('btn_tambah_obat');
        const listObat = document.getElementById('list_obat');
        const obatKosong = document.getElementById('obat_kosong');
        const inputJson = document.getElementById('obat_json');
        const inputBiaya = document.getElementById('biaya_periksa');
        const inputTotal = document.getElementById('total_harga');

        let obatTerpilih = [];

        function render() {
            listObat.innerHTML = '';

            if (obatTerpilih.length === 0) {
                listObat.appendChild(obatKosong);
            }

            let total = 0;

            obatTerpilih.forEach(function (obat, index) {
                total += obat.harga;

                const li = document.createElement('li');
                li.className = 'flex items-center justify-between px-4 py-2 rounded-lg bg-slate-100';
                li.innerHTML =
                    '<span class="text-sm text-slate-700"></span>' +
                    '<button type="button" class="btn btn-xs bg-red-500 hover:bg-red-600 text-white border-none rounded-lg">' +
                    '<i class="fas fa-trash"></i> Hapus</button>';

                li.querySelector('span').textContent =
                    obat.nama + ' - Rp ' + obat.harga.toLocaleString('id-ID');

                li.querySelector('button').addEventListener('click', function () {
                    obatTerpilih.splice(index, 1);
                    render();
                });

                listObat.appendChild(li);
            });

            inputJson.value = JSON.stringify(obatTerpilih.map(function (o) { return o.id; }));
            inputBiaya.value = total;
            inputTotal.value = total.toLocaleString('id-ID');
        }

        btnTambah.addEventListener('click', function () {
            const option = selectObat.options[selectObat.selectedIndex];

            if (!option.value) {
                return;
            }

            const id = parseInt(option.value);

            if (obatTerpilih.some(function (o) { return o.id === id; })) {
                return;
            }

            obatTerpilih.push({
                id: id,
                nama: option.dataset.nama,
                harga: parseInt(option.dataset.harga)
            });

            selectObat.value = '';
            render();
        });
    </script>

</x-layouts.app>
