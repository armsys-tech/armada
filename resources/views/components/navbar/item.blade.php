@props(['href' => '#', 'icon' => null, 'current' => null])
@php $current ??= url()->current() === url($href); @endphp
<a href="{{ $href }}" {{ $attributes->class('arm-navbar__item')->merge(['aria-current' => $current ? 'page' : null]) }}>
    @if ($icon)<armada:icon :name="$icon" size="md" />@endif{{ $slot }}
</a>
