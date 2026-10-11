@props([
    'variant' => 'default',  // default | flat | elevated | outline | ghost
    'title' => null,
    'description' => null,
    'icon' => null,
    'actions' => null,       // slot, top right
    'footer' => null,        // slot
    'media' => null,         // image URL or slot
    'ratio' => null,         // media aspect ratio, e.g. 4/3
    'href' => null,
    'tone' => null,
    'horizontal' => false,
    'selected' => false,
    'flush' => false,        // body without padding (tables, lists)
])
@php $tag = $href ? 'a' : 'div'; @endphp
<{{ $tag }} @if ($href) href="{{ $href }}" @endif {{ $attributes->class([
    'arm-card',
    'arm-card--'.$variant => $variant !== 'default',
    'arm-card--horizontal' => $horizontal,
    'arm-card--selected' => $selected,
])->merge(['data-tone' => $tone]) }}>
    @if ($media)
        <div class="arm-card__media" @if ($ratio) style="--ratio: {{ $ratio }}" @endif>
            @if (is_string($media))<img src="{{ $media }}" alt="" loading="lazy">@else{{ $media }}@endif
        </div>
    @endif
    @if ($horizontal)<div class="arm-card__content">@endif
    @if ($title || $actions || $icon)
        <div class="arm-card__header">
            @if ($icon)<span class="arm-list__icon-box" @if ($tone) data-tone="{{ $tone }}" @endif><armada:icon :name="$icon" size="lg" /></span>@endif
            <div class="arm-card__heading">
                @if ($title)<h3 class="arm-card__title">{{ $title }}</h3>@endif
                @if ($description)<p class="arm-card__description">{{ $description }}</p>@endif
            </div>
            @if ($actions)<div class="arm-card__actions">{{ $actions }}</div>@endif
        </div>
    @endif
    @if ($slot->isNotEmpty())
        <div @class(['arm-card__body', 'arm-card__body--flush' => $flush])>{{ $slot }}</div>
    @endif
    @if ($footer)
        <div {{ $footer->attributes->class('arm-card__footer') }}>{{ $footer }}</div>
    @endif
    @if ($horizontal)</div>@endif
</{{ $tag }}>
