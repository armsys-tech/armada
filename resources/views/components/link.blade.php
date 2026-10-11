@props(['href' => '#', 'variant' => 'default', 'external' => false]) {{-- default | subtle --}}
<a href="{{ $href }}" {{ $attributes->class(['arm-link', 'arm-link--subtle' => $variant === 'subtle'])->merge($external ? ['target' => '_blank', 'rel' => 'noopener'] : []) }}>{{ $slot }}@if ($external)<armada:icon name="arrow-up-right" size="sm" />@endif</a>
