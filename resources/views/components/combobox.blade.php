{{--
    Searchable select with rich options; single or multiple (chips). Works with wire:model / x-model.
    options: ['tr' => 'Türkiye'] or [['value' => 'tr', 'label' => 'Türkiye', 'description' => '…', 'icon' => 'flag', 'group' => 'Avrupa'], …]
--}}
@props([
    'label' => null, 'description' => null, 'badge' => null, 'name' => null,
    'options' => [], 'value' => null, 'multiple' => false, 'searchable' => true,
    'placeholder' => null, 'clearable' => false, 'creatable' => false, 'required' => false,
])
@php
    [$name, $key, $id, $invalid] = \Armada\Armada::field($attributes, $name, $errors ?? null);
    $value ??= $multiple ? [] : null;
    $placeholder ??= __('Select…');
@endphp
<armada:field :label="$label" :description="$description" :badge="$badge" :name="$key" :required="$required">
    <div class="arm-combobox" {{ $attributes->whereStartsWith(['wire:model', 'x-model']) }}
        x-data="armCombobox({ options: @js(\Armada\Armada::options($options)), value: @js($value), multiple: @js($multiple), creatable: @js($creatable) })"
        x-modelable="value"
        x-on:keydown.escape="close()" x-on:click.outside="close()">
        <div role="combobox" tabindex="0" aria-haspopup="listbox" x-bind:aria-expanded="open" aria-labelledby="{{ $id }}-label"
            {{ $attributes->only(['class', 'style'])->class(['arm-input', 'arm-combobox__trigger'])->merge(['data-invalid' => $invalid ?: null]) }}
            x-on:click="toggle()" x-on:keydown.enter.prevent="toggle()" x-on:keydown.down.prevent="openAndFocus()">
            @if ($multiple)
                <div class="arm-combobox__chips">
                    <template x-for="v in value" :key="v">
                        <span class="arm-chip arm-chip--sm"><span x-text="labelOf(v)"></span>
                            <button type="button" class="arm-chip__remove" x-on:click.stop="remove(v)" aria-label="{{ __('Remove') }}"><armada:icon name="x" size="xs" /></button>
                        </span>
                    </template>
                    <span class="arm-combobox__placeholder" x-show="! value.length">{{ $placeholder }}</span>
                </div>
            @else
                <span class="arm-input__control">
                    <span x-show="value !== null && value !== ''" x-text="labelOf(value)"></span>
                    <span class="arm-combobox__placeholder" x-show="value === null || value === ''">{{ $placeholder }}</span>
                </span>
            @endif
            @if ($clearable)
                <button type="button" class="arm-input__action" x-show="multiple ? value.length : (value !== null && value !== '')" x-on:click.stop="clear()" aria-label="{{ __('Clear') }}"><armada:icon name="x" size="sm" /></button>
            @endif
            <armada:icon name="chevrons-up-down" size="sm" class="arm-input__icon" />
        </div>
        @if ($name)
            <template x-for="v in (multiple ? value : [value])"><input type="hidden" name="{{ $name }}{{ $multiple ? '[]' : '' }}" x-bind:value="v"></template>
        @endif

        <template x-if="open"><div class="arm-popover-backdrop" x-on:click="close()"></div></template>
        <div class="arm-popover arm-popover--sheet" x-show="open" x-cloak x-transition.opacity.duration.100ms>
            @if ($searchable)
                <div class="arm-listbox__search">
                    <armada:icon name="search" size="md" />
                    <input type="text" x-ref="search" x-model="query" placeholder="{{ __('Search…') }}" autocomplete="off"
                        x-on:keydown.down.prevent="move(1)" x-on:keydown.up.prevent="move(-1)" x-on:keydown.enter.prevent="pickActive()">
                </div>
            @endif
            <div class="arm-listbox" role="listbox" id="{{ $id }}-listbox" @if ($multiple) aria-multiselectable="true" @endif
                x-on:keydown.down.prevent="move(1)" x-on:keydown.up.prevent="move(-1)" x-on:keydown.enter.prevent="pickActive()">
                <template x-for="(opt, i) in filtered" :key="opt.value">
                    <div>
                        <div class="arm-listbox__group" x-show="opt.group && (i === 0 || filtered[i - 1].group !== opt.group)" x-text="opt.group"></div>
                        <div class="arm-option" role="option" x-bind:aria-selected="isSelected(opt.value)" x-bind:data-active="active === i ? '' : null"
                            x-on:mouseenter="active = i" x-on:click="pick(opt.value)">
                            <div style="display:grid;min-width:0">
                                <span x-text="opt.label"></span>
                                <span class="arm-option__description" x-show="opt.description" x-text="opt.description"></span>
                            </div>
                            <armada:icon name="check" size="md" class="arm-option__check" />
                        </div>
                    </div>
                </template>
                <div class="arm-option" x-show="creatable && query && ! filtered.some(o => o.label.toLowerCase() === query.toLowerCase())" x-on:click="create()">
                    <armada:icon name="plus" size="md" /><span>{{ __('Create') }} "<span x-text="query"></span>"</span>
                </div>
                <div class="arm-listbox__empty" x-show="! filtered.length && ! (creatable && query)">{{ __('No results') }}</div>
            </div>
        </div>
    </div>
</armada:field>
