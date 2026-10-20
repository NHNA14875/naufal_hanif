<x-layouts.app title="Profile">

    <h2 class="text-2xl font-bold text-slate-800 mb-6">Profile</h2>

    <div class="card bg-base-100 shadow-md rounded-2xl border max-w-xl">
        <div class="card-body">
            <h3 class="font-bold text-slate-800 mb-2">Ganti Password</h3>

            <form method="POST" action="{{ route('profile.password') }}" class="flex flex-col gap-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Password Lama</label>
                    <input type="password" name="current_password"
                        class="w-full border border-slate-300 rounded-xl px-4 py-2.5 @error('current_password') border-red-500 @enderror">
                    @error('current_password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Password Baru</label>
                    <input type="password" name="password"
                        class="w-full border border-slate-300 rounded-xl px-4 py-2.5 @error('password') border-red-500 @enderror">
                    @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation"
                        class="w-full border border-slate-300 rounded-xl px-4 py-2.5">
                </div>

                <div>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold">
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
