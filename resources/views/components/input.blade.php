@props([
    'type' => 'text',
    'name' => null,         // defaults to the wire:model property
    'label' => null,
    'description' => null,
    'badge' => null,
    'size' => 'md',         // sm | md | lg
    'icon' => null,
    'iconTrailing' => null,
    'prefix' => null,
    'suffix' => null,
    'clearable' => false,
    'viewable' => false,    // password show/hide
    'invalid' => null,      // null = automatic from the validation errors
    'required' => false,
])
@php
    $name ??= $attributes->whereStartsWith('wire:model')->first();
    $key = $name ? rtrim(str_replace(['[', ']'], ['.', ''], $name), '.') : null;
    $invalid ??= $key && $errors->has($key);
    $id = $attributes->get('id') ?? ($key ? 'arm-'.str_replace('.', '-', $key) : null);

    $wrapper = $attributes->only(['class', 'style']);
    $control = $attributes->except(['class', 'style', 'id']);

    if ($key && $type !== 'password' && ! $attributes->whereStartsWith('wire:model')->first()) {
        $control = $control->except('value')->merge(['value' => old($key, $attributes->get('value'))]);
    }

    if ($clearable && ! $attributes->has('placeholder')) {
        $control = $control->merge(['placeholder' => ' ']); // lets CSS hide the clear button while empty
    }
@endphp
<armada:field :label="$label" :description="$description" :badge="$badge" :required="$required" :for="$id" :name="$key">
    <div {{ $wrapper->class(['arm-input', 'arm-input--'.$size => $size !== 'md'])->merge([
        'data-invalid' => $invalid ?: null,
        'data-disabled' => $attributes->has('disabled') ?: null,
        'x-data' => $viewable ? '{ show: false }' : null,
    ]) }}>
        @if ($icon)<armada:icon :name="$icon" size="md" class="arm-input__icon" />@endif
        @if ($prefix)<span class="arm-input__affix">{{ $prefix }}</span>@endif

        <input {{ $control->merge([
            'type' => $type,
            'name' => $name,
            'id' => $id,
            'required' => $required,
            'aria-invalid' => $invalid ? 'true' : null,
            'class' => 'arm-input__control',
        ]) }} @if ($viewable) x-bind:type="show ? 'text' : 'password'" @endif>

        @if ($clearable)
            <button type="button" class="arm-input__action" data-clear x-data aria-label="{{ __('Clear') }}"
                x-on:click="const i = $el.parentElement.querySelector('input'); i.value = ''; i.dispatchEvent(new Event('input', { bubbles: true })); i.focus()">
                <armada:icon name="x" size="sm" />
            </button>
        @endif

        @if ($viewable)
            <button type="button" class="arm-input__action" x-on:click="show = ! show" x-bind:aria-label="show ? '{{ __('Hide password') }}' : '{{ __('Show password') }}'">
                <armada:icon name="eye" size="sm" x-show="! show" />
                <armada:icon name="eye-off" size="sm" x-show="show" x-cloak />
            </button>
        @endif

        @if ($suffix)<span class="arm-input__affix">{{ $suffix }}</span>@endif
        @if ($iconTrailing)<armada:icon :name="$iconTrailing" size="md" class="arm-input__icon" />@endif
    </div>
</armada:field>
