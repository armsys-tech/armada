@props([
    'type' => 'bar',     // bar | line | area
    'labels' => [],
    'series' => [],      // ['Ziyaret' => [..], 'Kayıt' => [..]]
    'height' => 220,
    'stacked' => false,
    'legend' => true,
])
@php
    // Geometry: x in % of the plot width, y in px of a fixed-height box — nothing scales, so text and strokes stay crisp at any width.
    $series = collect($series);
    $n = max(count($labels), 1);
    $H = (int) $height; $padB = 22; $padT = 8;
    $totals = $stacked ? collect(range(0, $n - 1))->map(fn ($i) => $series->sum(fn ($s) => $s[$i] ?? 0)) : $series->flatten();
    $max = max(1, $totals->max());
    $step = 10 ** floor(log10($max)); $niceMax = ceil($max / $step) * $step; if ($niceMax / $step < 3) { $step /= 2; $niceMax = ceil($max / $step) * $step; }
    $ticks = range(0, $niceMax, $step);
    $y = fn ($v) => round($padT + ($H - $padT - $padB) * (1 - $v / $niceMax), 1);
    $bandPct = 100 / $n;
    $x = fn ($i) => round($bandPct * ($i + .5), 3);
    $colors = ['var(--arm-chart-1)', 'var(--arm-chart-2)', 'var(--arm-chart-3)', 'var(--arm-chart-4)', 'var(--arm-chart-5)', 'var(--arm-chart-6)'];
    $fmt = fn ($v) => $v >= 1000 ? round($v / 1000, 1).'k' : $v;
    $uid = 'c'.substr(md5(json_encode([$labels, $series])), 0, 6);
@endphp
<figure {{ $attributes->class('arm-chart') }} style="margin:0; --h: {{ $H }}px">
    @if ($legend && $series->count() > 1)
        <figcaption class="arm-chart__legend">
            @foreach ($series->keys() as $k => $name)<span><i style="--c: {{ $colors[$k % 6] }}"></i>{{ $name }}</span>@endforeach
        </figcaption>
    @endif
    <div class="arm-chart__plot">
        <svg class="arm-chart__axis-y" aria-hidden="true">
            @foreach ($ticks as $t)<text x="100%" y="{{ $y($t) + 4 }}" text-anchor="end">{{ $fmt($t) }}</text>@endforeach
        </svg>
        <svg class="arm-chart__canvas" role="img" aria-label="{{ $series->keys()->join(', ') }}">
            <defs><clipPath id="{{ $uid }}"><rect x="0" y="0" width="100%" height="{{ $y(0) }}" /></clipPath></defs>
            @foreach ($ticks as $t)
                <line class="arm-chart__grid" x1="0" x2="100%" y1="{{ $y($t) }}" y2="{{ $y($t) }}" />
            @endforeach
            @foreach ($labels as $i => $label)
                <text class="arm-chart__axis" x="{{ $x($i) }}%" y="{{ $H - 5 }}" text-anchor="middle">{{ $label }}</text>
            @endforeach

            @if ($type === 'bar')
                @php $groups = $stacked ? 1 : max($series->count(), 1); $bw = min(5.5, $bandPct * .6 / $groups); $base = array_fill(0, $n, 0); @endphp
                @foreach ($series->values() as $k => $vals)
                    @foreach ($vals as $i => $v)
                        @php
                            $x0 = $stacked ? $x($i) - $bw / 2 : $x($i) - $bw * $groups / 2 + $k * $bw;
                            $top = $y($base[$i] + $v); $bottom = $y($base[$i]);
                            $last = $stacked ? $k === $series->count() - 1 : true;
                            if ($stacked) $base[$i] += $v;
                        @endphp
                        {{-- rounded top only: the rect runs past its bottom edge and the plot clip trims it flat --}}
                        <rect class="arm-chart__bar" x="{{ round($x0 + .15, 3) }}%" y="{{ $top }}" width="{{ round($bw - .3, 3) }}%" height="{{ max(0, $bottom - $top + ($last ? 4 : 0)) }}" rx="{{ $last ? 3 : 0 }}" clip-path="url(#{{ $uid }})" fill="{{ $colors[$k % 6] }}"><title>{{ $labels[$i] ?? '' }}: {{ $v }}</title></rect>
                    @endforeach
                @endforeach
            @else
                @foreach ($series->values() as $k => $vals)
                    @php $vals = array_values($vals); $m = count($vals); @endphp
                    @if ($type === 'area' && $m > 1)
                        @php $pts = collect($vals)->map(fn ($v, $i) => round($x($i), 2).','.$y($v))->join(' '); @endphp
                        {{-- fill only (no stroke), so the stretched inner viewBox costs nothing --}}
                        <svg x="0" y="0" width="100%" height="{{ $H }}" viewBox="0 0 100 {{ $H }}" preserveAspectRatio="none" aria-hidden="true">
                            <polygon points="{{ $x(0) }},{{ $y(0) }} {{ $pts }} {{ $x($m - 1) }},{{ $y(0) }}" fill="{{ $colors[$k % 6] }}" opacity=".14" />
                        </svg>
                    @endif
                    @for ($i = 1; $i < $m; $i++)
                        <line x1="{{ $x($i - 1) }}%" y1="{{ $y($vals[$i - 1]) }}" x2="{{ $x($i) }}%" y2="{{ $y($vals[$i]) }}" stroke="{{ $colors[$k % 6] }}" stroke-width="2" stroke-linecap="round" />
                    @endfor
                    @foreach ($vals as $i => $v)<circle cx="{{ $x($i) }}%" cy="{{ $y($v) }}" r="3.5" fill="var(--arm-surface-1)" stroke="{{ $colors[$k % 6] }}" stroke-width="2"><title>{{ $labels[$i] ?? '' }}: {{ $v }}</title></circle>@endforeach
                @endforeach
            @endif
        </svg>
    </div>
</figure>
