@props([
    'type' => 'bar',     // bar | line | area
    'labels' => [],
    'series' => [],      // ['Ziyaret' => [..], 'Kayıt' => [..]]
    'height' => 220,
    'stacked' => false,
    'legend' => true,
])
@php
    $series = collect($series);
    $n = count($labels);
    $W = 800; $H = $height; $padL = 36; $padB = 24; $padT = 8;
    $totals = $stacked ? collect(range(0, max($n - 1, 0)))->map(fn ($i) => $series->sum(fn ($s) => $s[$i] ?? 0)) : $series->flatten();
    $max = max(1, $totals->max());
    $step = 10 ** floor(log10($max)); $niceMax = ceil($max / $step) * $step; if ($niceMax / $step < 3) { $step /= 2; $niceMax = ceil($max / $step) * $step; }
    $ticks = range(0, $niceMax, $step);
    $y = fn ($v) => $padT + ($H - $padT - $padB) * (1 - $v / $niceMax);
    $bandW = ($W - $padL) / max($n, 1);
    $x = fn ($i) => $padL + $bandW * ($i + .5);
    $colors = ['var(--arm-chart-1)', 'var(--arm-chart-2)', 'var(--arm-chart-3)', 'var(--arm-chart-4)', 'var(--arm-chart-5)', 'var(--arm-chart-6)'];
    $fmt = fn ($v) => $v >= 1000 ? round($v / 1000, 1).'k' : $v;
@endphp
<figure {{ $attributes->class('arm-chart') }} style="margin:0">
    @if ($legend && $series->count() > 1)
        <figcaption class="arm-chart__legend">
            @foreach ($series->keys() as $k => $name)<span><i style="--c: {{ $colors[$k % 6] }}"></i>{{ $name }}</span>@endforeach
        </figcaption>
    @endif
    <svg viewBox="0 0 {{ $W }} {{ $H }}" role="img" aria-label="{{ $series->keys()->join(', ') }}">
        @foreach ($ticks as $t)
            <line class="arm-chart__grid" x1="{{ $padL }}" x2="{{ $W }}" y1="{{ $y($t) }}" y2="{{ $y($t) }}" />
            <text class="arm-chart__axis" x="{{ $padL - 8 }}" y="{{ $y($t) + 4 }}" text-anchor="end">{{ $fmt($t) }}</text>
        @endforeach
        @foreach ($labels as $i => $label)
            <text class="arm-chart__axis" x="{{ $x($i) }}" y="{{ $H - 6 }}" text-anchor="middle">{{ $label }}</text>
        @endforeach

        @if ($type === 'bar')
            @php $groups = $stacked ? 1 : max($series->count(), 1); $bw = min(28, $bandW * .7 / $groups); $base = array_fill(0, $n, 0); @endphp
            @foreach ($series->values() as $k => $vals)
                @foreach ($vals as $i => $v)
                    @php
                        $x0 = $stacked ? $x($i) - $bw / 2 : $x($i) - $bw * $groups / 2 + $k * $bw;
                        $top = $y($base[$i] + $v); $bottom = $y($base[$i]);
                        if ($stacked) $base[$i] += $v;
                    @endphp
                    <rect class="arm-chart__bar" x="{{ $x0 + 1 }}" y="{{ $top }}" width="{{ $bw - 2 }}" height="{{ max(0, $bottom - $top) }}" rx="3" fill="{{ $colors[$k % 6] }}"><title>{{ $labels[$i] ?? '' }}: {{ $v }}</title></rect>
                @endforeach
            @endforeach
        @else
            @foreach ($series->values() as $k => $vals)
                @php $pts = collect($vals)->map(fn ($v, $i) => round($x($i), 1).','.round($y($v), 1))->join(' '); @endphp
                @if ($type === 'area')
                    <polygon points="{{ round($x(0), 1) }},{{ $y(0) }} {{ $pts }} {{ round($x(count($vals) - 1), 1) }},{{ $y(0) }}" fill="{{ $colors[$k % 6] }}" opacity=".14" />
                @endif
                <polyline points="{{ $pts }}" fill="none" stroke="{{ $colors[$k % 6] }}" stroke-width="2.2" stroke-linejoin="round" stroke-linecap="round" />
                @foreach ($vals as $i => $v)<circle cx="{{ $x($i) }}" cy="{{ $y($v) }}" r="3" fill="var(--arm-surface-1)" stroke="{{ $colors[$k % 6] }}" stroke-width="2"><title>{{ $labels[$i] ?? '' }}: {{ $v }}</title></circle>@endforeach
            @endforeach
        @endif
    </svg>
</figure>
