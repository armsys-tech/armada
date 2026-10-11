@props(['name' => null, 'logo' => null, 'icon' => null, 'href' => null])
@php $tag = $href ? 'a' : 'span'; @endphp
<{{ $tag }} @if ($href) href="{{ $href }}" @endif {{ $attributes->class('arm-brand') }}>
    <span class="arm-brand__logo">
        @if ($logo)<img src="{{ $logo }}" alt="">
        @elseif ($icon)<armada:icon :name="$icon" />
        @else{{ mb_substr((string) $name, 0, 1) }}@endif
    </span>
    @if ($name)<span class="arm-brand__name">{{ $name }}</span>@endif
</{{ $tag }}>
