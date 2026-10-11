@props(['variant' => 'line', 'lines' => 3, 'width' => null, 'height' => null]) {{-- line | text | circle | rect | lines --}}
@if ($variant === 'lines')
    <div {{ $attributes->class('arm-skeleton-lines') }} aria-hidden="true">
        @for ($i = 0; $i < $lines; $i++)<span class="arm-skeleton"></span>@endfor
    </div>
@else
    <span {{ $attributes->class(['arm-skeleton', 'arm-skeleton--circle' => $variant === 'circle', 'arm-skeleton--text' => $variant === 'text'])->merge([
        'style' => collect(['width' => $width, 'height' => $height])->filter()->map(fn ($v, $k) => "$k: $v")->join('; ') ?: null,
        'aria-hidden' => 'true',
    ]) }}></span>
@endif
