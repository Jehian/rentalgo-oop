@props(['id', 'title' => null, 'maxWidth' => 'max-w-lg'])

<dialog id="{{ $id }}" data-modal class="m-auto w-full max-w-[calc(100%-2rem)] rounded-2xl border-0 bg-transparent p-0 backdrop:bg-slate-900/60">
    <div class="w-full {{ $maxWidth }} rounded-2xl bg-white shadow-xl">
        <header class="flex items-center justify-between gap-4 border-b border-slate-100 px-5 py-4">
            <h2 class="text-base font-semibold text-slate-900">{{ $title }}</h2>
            <button type="button" data-modal-close class="rounded-lg p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600" aria-label="Tutup dialog">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </header>

        <div class="p-5">
            {{ $slot }}
        </div>

        @isset($footer)
            <footer class="flex items-center justify-end gap-2 border-t border-slate-100 bg-slate-50 px-5 py-3">
                {{ $footer }}
            </footer>
        @endisset
    </div>
</dialog>
