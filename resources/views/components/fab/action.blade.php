@props(['icon', 'label', 'href' => null])
<div class="arm-fab__action">
    <span class="arm-fab__action-label">{{ $label }}</span>
    <armada:button variant="outline" :href="$href" :icon="$icon" :aria-label="$label" {{ $attributes }} />
</div>
