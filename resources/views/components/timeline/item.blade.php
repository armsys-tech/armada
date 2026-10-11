@props(['title' => null, 'time' => null, 'icon' => null, 'tone' => null, 'avatar' => null])
<li {{ $attributes->class('arm-timeline__item') }}>
    @if ($avatar)
        <armada:avatar :name="$avatar" size="sm" style="--size: 28px" />
    @else
        <span @class(['arm-timeline__marker', 'arm-timeline__marker--dot' => ! $icon]) @if ($tone) data-tone="{{ $tone }}" @endif>@if ($icon)<armada:icon :name="$icon" />@endif</span>
    @endif
    <div class="arm-timeline__content">
        @if ($title)<div class="arm-timeline__title">{{ $title }}</div>@endif
        @if ($time)<div class="arm-timeline__time">{{ $time }}</div>@endif
        @if ($slot->isNotEmpty())<div class="arm-timeline__body">{{ $slot }}</div>@endif
    </div>
</li>
