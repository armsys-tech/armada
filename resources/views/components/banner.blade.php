@props(['tone' => null, 'dismissible' => true])
<div {{ $attributes->class('arm-banner')->merge(['data-tone' => $tone]) }} @if ($dismissible) x-data="{ shown: true }" x-show="shown" @endif>
    {{ $slot }}
    @if ($dismissible)
        <armada:button variant="ghost" size="sm" icon="x" class="arm-banner__close" :aria-label="__('Dismiss')" x-on:click="shown = false" />
    @endif
</div>
