@props(['href' => null, 'icon' => null])
<li>
    @if ($href)
        <a href="{{ $href }}" {{ $attributes }}>@if ($icon)<armada:icon :name="$icon" size="sm" />@endif{{ $slot }}</a>
    @else
        <span aria-current="page" {{ $attributes }}>@if ($icon)<armada:icon :name="$icon" size="sm" />@endif{{ $slot }}</span>
    @endif
</li>
