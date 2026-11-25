@props(['paginator'])

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi halaman" {{ $attributes->merge(['class' => 'flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between']) }}>
        <p class="text-sm text-slate-600">
            Menampilkan
            <span class="font-medium">{{ $paginator->firstItem() }}</span>–<span class="font-medium">{{ $paginator->lastItem() }}</span>
            dari <span class="font-medium">{{ $paginator->total() }}</span> data
        </p>

        <div class="flex items-center gap-2">
            @if ($paginator->onFirstPage())
                <span class="cursor-not-allowed rounded-lg border border-slate-200 px-3 py-1.5 text-sm text-slate-400">Sebelumnya</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Sebelumnya</a>
            @endif

            <span class="rounded-lg bg-slate-100 px-3 py-1.5 text-sm font-medium text-slate-600">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Berikutnya</a>
            @else
                <span class="cursor-not-allowed rounded-lg border border-slate-200 px-3 py-1.5 text-sm text-slate-400">Berikutnya</span>
            @endif
        </div>
    </nav>
@endif
