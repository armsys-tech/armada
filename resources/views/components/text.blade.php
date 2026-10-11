@props(['size' => null, 'variant' => null, 'tone' => null, 'truncate' => false, 'lines' => null, 'as' => 'p']) {{-- size: sm | lg · variant: muted | subtle | strong --}}
<{{ $as }} {{ $attributes->class([
    'arm-text',
    'arm-text--'.$size => $size,
    'arm-text--'.$variant => $variant,
    'arm-text--truncate' => $truncate,
    'arm-text--clamp' => $lines,
])->merge(['data-tone' => $tone, 'style' => $lines ? "--lines: $lines" : null]) }}>{{ $slot }}</{{ $as }}>
