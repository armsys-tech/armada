@props(['level' => 2, 'size' => null]) {{-- size: xs | sm | md | lg | xl | display --}}
@php $size ??= [1 => 'xl', 2 => 'lg', 3 => 'md', 4 => 'sm', 5 => 'xs', 6 => 'xs'][$level] ?? 'md'; @endphp
<h{{ $level }} {{ $attributes->class(['arm-heading', 'arm-heading--'.$size]) }}>{{ $slot }}</h{{ $level }}>
