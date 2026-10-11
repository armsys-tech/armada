@props(['src', 'alt' => '', 'ratio' => null, 'fit' => null, 'caption' => null, 'rounded' => true])
<figure {{ $attributes->class(['arm-image', 'arm-image--rounded-none' => ! $rounded])->merge([
    'style' => collect(['--ratio' => $ratio, '--fit' => $fit])->filter()->map(fn ($v, $k) => "$k: $v")->join('; ') ?: null,
]) }}>
    <img src="{{ $src }}" alt="{{ $alt }}" loading="lazy" decoding="async">
    @if ($caption)<figcaption>{{ $caption }}</figcaption>@endif
</figure>
