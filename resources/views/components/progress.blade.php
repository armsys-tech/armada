@props([
    'value' => null,        // 0–100; null = indeterminate
    'tone' => 'primary',
    'label' => null,
    'showValue' => false,
    'circular' => false,
    'size' => null,         // circular: 40px … ; linear: track height (4px)
    'striped' => false,
    'segments' => null,     // [['value' => 40, 'tone' => 'success'], …] stacked bar
])
@php $v = $value === null ? null : max(0, min(100, $value)); @endphp
@if ($circular)
    @php $r = 16; $c = 2 * M_PI * $r; @endphp
    <span {{ $attributes->class(['arm-progress-circle', 'arm-progress-circle--indeterminate' => $v === null])->merge([
        'data-tone' => $tone, 'role' => 'progressbar', 'aria-valuenow' => $v, 'aria-valuemin' => 0, 'aria-valuemax' => 100,
        'style' => $size ? "--size: $size" : null,
    ]) }}>
        <svg viewBox="0 0 40 40" fill="none" stroke-width="4">
            <circle class="arm-progress-circle__track" cx="20" cy="20" r="{{ $r }}" />
            <circle class="arm-progress-circle__bar" cx="20" cy="20" r="{{ $r }}" stroke-linecap="round"
                stroke-dasharray="{{ $c }}" stroke-dashoffset="{{ $v === null ? $c * .7 : $c * (1 - $v / 100) }}" />
        </svg>
        @if ($showValue && $v !== null)<span class="arm-progress-circle__label">{{ round($v) }}%</span>@endif
        {{ $slot }}
    </span>
@else
    <div {{ $attributes->class(['arm-progress', 'arm-progress--indeterminate' => $v === null && ! $segments, 'arm-progress--striped' => $striped])->merge([
        'data-tone' => $tone, 'role' => 'progressbar', 'aria-valuenow' => $v, 'aria-valuemin' => 0, 'aria-valuemax' => 100, 'aria-label' => $label,
        'style' => $size ? "--h: $size" : null,
    ]) }}>
        @if ($label || $showValue)
            <div class="arm-progress__meta"><span>{{ $label }}</span>@if ($showValue && $v !== null)<span>{{ round($v) }}%</span>@endif</div>
        @endif
        @if ($segments)
            <div class="arm-progress__segments">
                @foreach ($segments as $seg)<span data-tone="{{ $seg['tone'] ?? 'primary' }}" style="width: {{ $seg['value'] }}%"></span>@endforeach
            </div>
        @else
            <div class="arm-progress__track"><div class="arm-progress__bar" style="--value: {{ $v ?? 0 }}%"></div></div>
        @endif
    </div>
@endif
