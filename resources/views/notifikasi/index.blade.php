<x-layouts.app title="Notifikasi">

    <h2 class="text-2xl font-bold text-slate-800 mb-6">Notifikasi</h2>

    <div class="flex flex-col gap-3">
        @forelse($notifikasi as $notif)
        <div class="card shadow-sm border rounded-xl {{ $notif->read_at ? 'bg-base-100 border-slate-200' : 'bg-indigo-50 border-indigo-300' }}">
            <div class="card-body p-4 flex-row items-center justify-between gap-4">
                <div>
                    <p class="{{ $notif->read_at ? 'text-slate-600' : 'font-semibold text-slate-800' }}">
                        {{ $notif->data['pesan'] }}
                    </p>
                    <p class="text-xs text-slate-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                </div>

                @if(!$notif->read_at)
                <form method="POST" action="{{ route('notifikasi.baca', $notif->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline btn-primary">Tandai dibaca</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-14 text-slate-400">Belum ada notifikasi</div>
        @endforelse
    </div>

</x-layouts.app>
