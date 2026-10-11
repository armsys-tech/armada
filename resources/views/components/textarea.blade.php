@props(['label' => null, 'description' => null, 'badge' => null, 'name' => null, 'rows' => 3, 'maxlength' => null, 'counter' => null, 'required' => false])
@php
    [$name, $key, $id, $invalid] = \Armada\Armada::field($attributes, $name, $errors ?? null);
    $counter ??= (bool) $maxlength;
    $initial = $key && ! $attributes->whereStartsWith('wire:model')->first() ? old($key, (string) $slot) : (string) $slot;
@endphp
<armada:field :label="$label" :description="$description" :badge="$badge" :for="$id" :required="$required" :name="$counter ? null : $key">
    <div style="display:grid;gap:6px" @if ($counter) x-data="{ n: {{ mb_strlen($initial) }} }" @endif>
        <div class="arm-input" @if ($invalid) data-invalid @endif>
            <textarea {{ $attributes->class('arm-input__control arm-textarea')->merge([
                'id' => $id, 'name' => $name, 'rows' => $rows, 'maxlength' => $maxlength, 'required' => $required,
                'aria-invalid' => $invalid ? 'true' : null, 'style' => "--rows: $rows",
            ]) }} @if ($counter) x-on:input="n = $el.value.length" x-init="n = $el.value.length" @endif>{{ $initial }}</textarea>
        </div>
        @if ($counter)
            <div class="arm-field__footer">
                @if ($key)<armada:error :name="$key" />@endif
                <span class="arm-counter" x-text="n{{ $maxlength ? " + ' / $maxlength'" : '' }}">{{ mb_strlen($initial) }}{{ $maxlength ? ' / '.$maxlength : '' }}</span>
            </div>
        @endif
    </div>
</armada:field>
