@props([
    'tone' => 'info',       // info | success | warning | danger | neutral | primary …
    'variant' => 'soft',    // soft | line | solid
    'title' => null,
    'icon' => null,         // false to hide
    'dismissible' => false,
    'actions' => null,
])
@php
    $icon ??= ['info' => 'info', 'success' => 'circle-check', 'warning' => 'triangle-alert', 'danger' => 'circle-alert'][$tone] ?? 'info';
@endphp
<div {{ $attributes->class(['arm-callout', 'arm-callout--'.$variant => $variant !== 'soft'])->merge([
    'data-tone' => $tone, 'role' => in_array($tone, ['danger', 'warning']) ? 'alert' : 'status',
]) }} @if ($dismissible) x-data="{ shown: true }" x-show="shown" x-transition.opacity @endif>
    @if ($icon)<armada:icon :name="$icon" />@endif
    <div class="arm-callout__content">
        @if ($title)<div class="arm-callout__title">{{ $title }}</div>@endif
        @if ($slot->isNotEmpty())<div>{{ $slot }}</div>@endif
        @if ($actions)<div class="arm-callout__actions">{{ $actions }}</div>@endif
    </div>
    @if ($dismissible)
        <armada:button variant="ghost" size="sm" icon="x" class="arm-callout__close" :aria-label="__('Dismiss')" x-on:click="shown = false" />
    @endif
</div>
