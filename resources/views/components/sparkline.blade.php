@props(['values' => [], 'tone' => 'primary', 'type' => 'area', 'height' => '32px']) {{-- line | area | bar --}}
@php
    $vals = array_values($values);
    $n = max(count($vals), 2);
    $min = min($vals ?: [0]); $max = max($vals ?: [1]); $span = ($max - $min) ?: 1;
    $pts = collect($vals)->map(fn ($v, $i) => [round($i / ($n - 1) * 100, 2), round(30 - ($v - $min) / $span * 28 - 1, 2)]);
    $line = $pts->map(fn ($p) => implode(',', $p))->join(' ');
    $id = 'spk'.substr(md5($line.$tone), 0, 6);
@endphp
<svg {{ $attributes->class('arm-sparkline')->merge(['data-tone' => $tone, 'style' => "--h: $height"]) }} viewBox="0 0 100 30" preserveAspectRatio="none" aria-hidden="true">
    @if ($type === 'bar')
        @foreach ($vals as $i => $v)
            @php $h = max(1, ($v - min(0, $min)) / (($max - min(0, $min)) ?: 1) * 30); @endphp
            <rect x="{{ $i * (100 / count($vals)) + .8 }}" y="{{ 30 - $h }}" width="{{ 100 / count($vals) - 1.6 }}" height="{{ $h }}" rx="1" fill="currentColor" opacity=".85" />
        @endforeach
    @else
        @if ($type === 'area')
            <defs><linearGradient id="{{ $id }}" x1="0" x2="0" y1="0" y2="1"><stop offset="0" stop-color="currentColor" stop-opacity=".28"/><stop offset="1" stop-color="currentColor" stop-opacity="0"/></linearGradient></defs>
            <polygon points="0,30 {{ $line }} 100,30" fill="url(#{{ $id }})" />
        @endif
        <polyline points="{{ $line }}" fill="none" stroke="currentColor" stroke-width="1.6" vector-effect="non-scaling-stroke" stroke-linejoin="round" stroke-linecap="round" />
    @endif
</svg>
