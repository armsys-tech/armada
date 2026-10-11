{{-- Puts a count (or a dot when empty) on any element: <armada:notification-badge count="3"><armada:button …/></armada:notification-badge> --}}
@props(['count' => null, 'tone' => 'danger'])
<span {{ $attributes->class('arm-badge-anchor') }}>
    {{ $slot }}
    <span class="arm-badge-float" data-tone="{{ $tone }}">{{ $count }}</span>
</span>
