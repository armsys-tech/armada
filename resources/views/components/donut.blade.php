@props(['segments' => [], 'size' => '160px', 'value' => null, 'label' => null, 'legend' => true, 'thickness' => 10])
@php
    $segments = collect($segments); // ['Chrome' => 62, 'Safari' => 21, …]
    $total = max($segments->sum(), 1);
    $r = 50 - $thickness / 2 - 1; $c = 2 * M_PI * $r; $offset = 0;
    $colors = ['var(--arm-chart-1)', 'var(--arm-chart-2)', 'var(--arm-chart-3)', 'var(--arm-chart-4)', 'var(--arm-chart-5)', 'var(--arm-chart-6)'];
@endphp
<div {{ $attributes->class('arm-chart') }} style="justify-items:center">
    <div class="arm-donut" style="--size: {{ $size }}">
        <svg viewBox="0 0 100 100" aria-hidden="true">
            <circle cx="50" cy="50" r="{{ $r }}" fill="none" stroke="var(--arm-surface-3)" stroke-width="{{ $thickness }}" />
            @foreach ($segments->values() as $i => $v)
                @php $len = $v / $total * $c; @endphp
                <circle cx="50" cy="50" r="{{ $r }}" fill="none" stroke="{{ $colors[$i % 6] }}" stroke-width="{{ $thickness }}"
                    stroke-dasharray="{{ max(0, $len - 1.2) }} {{ $c }}" stroke-dashoffset="{{ -$offset }}" />
                @php $offset += $len; @endphp
            @endforeach
        </svg>
        @if ($value !== null || $label)
            <div class="arm-donut__center"><span class="arm-donut__value">{{ $value }}</span><span class="arm-donut__label">{{ $label }}</span></div>
        @endif
    </div>
    @if ($legend)
        <div class="arm-chart__legend" style="justify-content:center">
            @foreach ($segments as $name => $v)<span><i style="--c: {{ $colors[$loop->index % 6] }}"></i>{{ $name }} · {{ round($v / $total * 100) }}%</span>@endforeach
        </div>
    @endif
</div>
