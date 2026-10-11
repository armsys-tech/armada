@props(['label' => null, 'description' => null, 'name' => null, 'value' => '#4d7cfe', 'swatches' => ['#4d7cfe', '#8b5cf6', '#10b981', '#f97316', '#f43f5e', '#06b6d4', '#eab308', '#71717a'], 'custom' => true])
@php [$name, $key, $id] = \Armada\Armada::field($attributes, $name, $errors ?? null); @endphp
<armada:field :label="$label" :description="$description" :name="$key">
    <div style="display:flex;flex-wrap:wrap;align-items:center;gap:12px" {{ $attributes->whereStartsWith(['wire:model', 'x-model']) }} x-data="{ value: @js($value) }" x-modelable="value">
        <div class="arm-swatches" role="radiogroup">
            @foreach ($swatches as $c)
                <label class="arm-swatch" style="--c: {{ $c }}" title="{{ $c }}"><input type="radio" value="{{ $c }}" x-model="value" aria-label="{{ $c }}"></label>
            @endforeach
        </div>
        @if ($custom)
            <div class="arm-input" style="width:150px">
                <input type="color" class="arm-color-input" x-model="value" aria-label="{{ __('Custom colour') }}">
                <input type="text" class="arm-input__control" x-model.lazy="value" id="{{ $id }}" @if ($name) name="{{ $name }}" @endif maxlength="7" spellcheck="false" style="font-family:var(--arm-font-mono);font-size:13px">
            </div>
        @endif
    </div>
</armada:field>
