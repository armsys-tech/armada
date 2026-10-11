@props([
    'label' => null,
    'description' => null,
    'badge' => null,
    'name' => null,     // shows this field's validation error under the control
    'for' => null,
    'required' => false,
    'inline' => false,  // label left, control right (switches, checkboxes); stacks on phones
])
<div {{ $attributes->class(['arm-field', 'arm-field--inline' => $inline]) }}>
    @if ($label || $description)
        <div class="arm-label-group">
            @if ($label)<armada:label :for="$for" :required="$required" :badge="$badge">{{ $label }}</armada:label>@endif
            @if ($description)<armada:description>{{ $description }}</armada:description>@endif
        </div>
    @endif
    {{ $slot }}
    @if ($name)<armada:error :name="$name" />@endif
</div>
