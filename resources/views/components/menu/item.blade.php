@props(['icon' => null, 'href' => null, 'kbd' => null, 'variant' => null, 'checked' => null, 'disabled' => false]) {{-- variant: danger · checked: true/false for checkable items --}}
@php $tag = $href ? 'a' : 'button'; @endphp
<{{ $tag }} @if ($href) href="{{ $href }}" @else type="button" @endif role="{{ $checked === null && ! $attributes->has('x-bind:aria-checked') && ! $attributes->has(':aria-checked') ? 'menuitem' : 'menuitemcheckbox' }}" tabindex="-1"
    {{ $attributes->class(['arm-menu__item', 'arm-menu__item--danger' => $variant === 'danger'])->merge([
        'aria-checked' => $checked === null ? null : ($checked ? 'true' : 'false'),
        'disabled' => $disabled && ! $href,
        'aria-disabled' => $disabled ? 'true' : null,
    ]) }}>
    @if ($icon)<armada:icon :name="$icon" />@endif
    <span class="arm-menu__label">{{ $slot }}</span>
    @if ($kbd)<kbd class="arm-menu__kbd">{{ $kbd }}</kbd>@endif
    @if ($checked !== null || $attributes->has('x-bind:aria-checked') || $attributes->has(':aria-checked'))<armada:icon name="check" class="arm-menu__check" />@endif
</{{ $tag }}>
