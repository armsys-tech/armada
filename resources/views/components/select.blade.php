{{-- Native select: works everywhere, best on phones. For search, multiple chips or rich options use <armada:combobox>. --}}
@props(['label' => null, 'description' => null, 'badge' => null, 'name' => null, 'options' => [], 'placeholder' => null, 'selected' => null, 'size' => 'md', 'required' => false])
@php
    [$name, $key, $id, $invalid] = \Armada\Armada::field($attributes, $name, $errors ?? null);
    $selected = $key && ! $attributes->whereStartsWith('wire:model')->first() ? old($key, $selected) : $selected;
@endphp
<armada:field :label="$label" :description="$description" :badge="$badge" :for="$id" :name="$key" :required="$required">
    <span class="arm-select-wrap"><select {{ $attributes->class(['arm-select', 'arm-select--'.$size => $size !== 'md'])->merge([
        'id' => $id, 'name' => $name, 'required' => $required, 'aria-invalid' => $invalid ? 'true' : null,
    ]) }}>
        @if ($placeholder)<option value="" disabled @selected($selected === null)>{{ $placeholder }}</option>@endif
        @foreach (\Armada\Armada::options($options) as $opt)
            <option value="{{ $opt['value'] }}" @selected((string) $opt['value'] === (string) $selected) @disabled($opt['disabled'] ?? false)>{{ $opt['label'] }}</option>
        @endforeach
        {{ $slot }}
    </select></span>
</armada:field>
