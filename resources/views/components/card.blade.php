@props(['title' => null, 'subtitle' => null, 'padding' => true])

<section {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm']) }}>
    @if ($title || isset($actions))
        <header class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 px-5 py-4">
            <div>
                @if ($title)
                    <h2 class="text-base font-semibold text-slate-900">{{ $title }}</h2>
                @endif

                @if ($subtitle)
                    <p class="mt-0.5 text-sm text-slate-500">{{ $subtitle }}</p>
                @endif
            </div>

            @isset($actions)
                <div class="flex items-center gap-2">{{ $actions }}</div>
            @endisset
        </header>
    @endif

    <div class="{{ $padding ? 'p-5' : '' }}">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="border-t border-slate-100 bg-slate-50 px-5 py-3">
            {{ $footer }}
        </div>
    @endisset
</section>
