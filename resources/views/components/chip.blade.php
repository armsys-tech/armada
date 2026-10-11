@props([
    'icon' => null,
    'avatar' => null,     // image URL or a name for initials
    'tone' => null,
    'size' => 'md',
    'removable' => false, // renders a × button; listen with x-on:remove or wire:click on it via the "remove" attribute
    'selected' => null,   // toggle chip (button with aria-pressed)
    'href' => null,
    'value' => null,      // filter chip: renders a checkbox input (works with wire:model)
    'name' => null,
])
@php $tag = $href ? 'a' : ($value !== null ? 'label' : ($selected !== null ? 'button' : 'span')); @endphp
<{{ $tag }}
    @if ($href) href="{{ $href }}" @endif
    @if ($tag === 'button') type="button" aria-pressed="{{ $selected ? 'true' : 'false' }}" @endif
    {{ $attributes->except(['wire:model', 'wire:model.live', 'x-model'])->class(['arm-chip', 'arm-chip--sm' => $size === 'sm'])->merge(['data-tone' => $tone]) }}>
    @if ($value !== null)
        <input type="checkbox" value="{{ $value }}" @if ($name) name="{{ $name }}" @endif {{ $attributes->whereStartsWith(['wire:model', 'x-model']) }}>
    @endif
    @if ($avatar)<armada:avatar :src="str_contains($avatar, '/') ? $avatar : null" :name="str_contains($avatar, '/') ? null : $avatar" size="xs" />@endif
    @if ($icon)<armada:icon :name="$icon" />@endif
    {{ $slot }}
    @if ($removable)
        <button type="button" class="arm-chip__remove" aria-label="{{ __('Remove') }}" x-on:click.stop="$dispatch('remove')"><armada:icon name="x" size="xs" /></button>
    @endif
</{{ $tag }}>
