@props(['status', 'label' => null])

@php
    $value = $status instanceof \BackedEnum ? $status->value : (string) $status;

    $text = $label ?? ($status instanceof \BackedEnum && method_exists($status, 'label')
        ? $status->label()
        : \Illuminate\Support\Str::headline($value));

    $classes = match ($value) {
        'available', 'paid', 'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'rented', 'active' => 'bg-sky-50 text-sky-700 ring-sky-600/20',
        'pending', 'maintenance' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'cancelled', 'failed' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
        default => 'bg-slate-100 text-slate-600 ring-slate-500/20',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset '.$classes]) }}>
    {{ $text }}
</span>
