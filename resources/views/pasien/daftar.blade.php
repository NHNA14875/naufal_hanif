<x-layouts.app title="Pendaftaran Periksa">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Pendaftaran Periksa
        </h2>
    </div>

    {{-- Card --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border border-slate-200">
        <div class="card-body p-8">

            <form action="{{ route('pasien.daftar.submit') }}" method="POST">
                @csrf

                <input type="hidden" name="id_pasien" value="{{ $user->id }}">

                {{-- Data Pasien --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Nama Pasien
                        </label>
                        <input type="text" value="{{ $user->nama }}" readonly
                            class="w-full px-4 py-2 rounded-lg border-2 border-slate-300 bg-slate-50 text-slate-700">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            No. Rekam Medis
                        </label>
                        <input type="text" value="{{ $user->no_rm ?? '-' }}" readonly
                            class="w-full px-4 py-2 rounded-lg border-2 border-slate-300 bg-slate-50 text-slate-700">
                    </div>
                </div>

                {{-- Pilih Poli --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        Pilih Poli <span class="text-red-500">*</span>
                    </label>

                    <select name="id_poli" id="id_poli"
                        class="w-full px-4 py-2 rounded-lg border-2 border-slate-300 bg-white
                               focus:border-primary focus:outline-none
                               @error('id_poli') border-red-500 @enderror" required>
                        <option value="">-- Pilih Poli --</option>
                        @foreach ($polis as $poli)
                        <option value="{{ $poli->id }}" @selected(old('id_poli') == $poli->id)>
                            {{ $poli->nama_poli }}
                        </option>
                        @endforeach
                    </select>

                    @error('id_poli')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Pilih Jadwal --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        Pilih Jadwal <span class="text-red-500">*</span>
                    </label>

                    <select name="id_jadwal" id="id_jadwal"
                        class="w-full px-4 py-2 rounded-lg border-2 border-slate-300 bg-white
                               focus:border-primary focus:outline-none
                               @error('id_jadwal') border-red-500 @enderror" required>
                        <option value="">-- Pilih Jadwal --</option>
                        @foreach ($jadwals as $jadwal)
                        <option value="{{ $jadwal->id }}" data-poli="{{ $jadwal->dokter->id_poli }}"
                            @selected(old('id_jadwal') == $jadwal->id)>
                            {{ $jadwal->dokter->nama }} - {{ $jadwal->hari }},
                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                            - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                        </option>
                        @endforeach
                    </select>

                    @error('id_jadwal')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Keluhan --}}
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-slate-700 mb-1">
                        Keluhan <span class="text-red-500">*</span>
                    </label>

                    <textarea name="keluhan" rows="4" placeholder="Tuliskan keluhan Anda..."
                        class="w-full px-4 py-2 rounded-lg border-2 border-slate-300 
                               focus:border-primary focus:outline-none
                               @error('keluhan') border-red-500 @enderror" required>{{ old('keluhan') }}</textarea>

                    @error('keluhan')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Button --}}
                <div class="flex gap-3">
                    <button type="submit"
                        class="px-6 py-2.5 rounded-lg bg-primary hover:bg-primary/90 
                               text-white font-semibold text-sm transition">
                        <i class="fas fa-save mr-1"></i> Daftar
                    </button>

                    <a href="{{ route('pasien.dashboard') }}"
                        class="px-6 py-2.5 rounded-lg bg-slate-100 hover:bg-slate-200 
                               text-slate-600 font-semibold text-sm transition">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

    <script>
        const selectPoli = document.getElementById('id_poli');
        const selectJadwal = document.getElementById('id_jadwal');

        function filterJadwal(reset) {
            const poli = selectPoli.value;

            if (reset) {
                selectJadwal.value = '';
            }

            Array.from(selectJadwal.options).forEach(function (option) {
                if (!option.value) {
                    return;
                }

                const cocok = poli !== '' && option.dataset.poli === poli;
                option.hidden = !cocok;
                option.disabled = !cocok;
            });
        }

        selectPoli.addEventListener('change', function () {
            filterJadwal(true);
        });

        filterJadwal(false);
    </script>

</x-layouts.app>
