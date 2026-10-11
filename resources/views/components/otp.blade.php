@props(['label' => null, 'description' => null, 'name' => null, 'length' => 6, 'separator' => null, 'mask' => false]) {{-- separator: index after which a dash is shown, e.g. 3 --}}
@php [$name, $key] = \Armada\Armada::field($attributes, $name, $errors ?? null); @endphp
<armada:field :label="$label" :description="$description" :name="$key">
    <div class="arm-otp" {{ $attributes->whereStartsWith(['wire:model', 'x-model']) }}
        x-data="armOtp({{ (int) $length }})" x-modelable="value" x-on:paste.prevent="paste($event)">
        @for ($i = 0; $i < $length; $i++)
            @if ($separator && $i === (int) $separator)<span class="arm-otp__sep">–</span>@endif
            <input class="arm-otp__cell" type="{{ $mask ? 'password' : 'text' }}" inputmode="numeric" maxlength="1" autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}"
                aria-label="{{ __('Digit :n', ['n' => $i + 1]) }}"
                x-on:input="input($event, {{ $i }})" x-on:keydown.backspace="back($event, {{ $i }})"
                x-on:keydown.left.prevent="$el.previousElementSibling?.focus()" x-on:keydown.right.prevent="$el.nextElementSibling?.focus()">
        @endfor
        @if ($name)<input type="hidden" name="{{ $name }}" x-bind:value="value">@endif
    </div>
</armada:field>
