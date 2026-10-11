@props(['variant' => 'segment']) {{-- segment | toggle --}}
@if ($variant === 'toggle')
    <armada:button variant="ghost" square x-data x-on:click="$store.armTheme.toggle()" :aria-label="__('Toggle theme')" {{ $attributes }}>
        <armada:icon name="sun" size="md" class="arm-theme-icon-light" />
        <armada:icon name="moon" size="md" class="arm-theme-icon-dark" />
    </armada:button>
@else
    <div {{ $attributes->class('arm-segment') }} x-data role="group" aria-label="{{ __('Theme') }}">
        @foreach (['light' => 'sun', 'dark' => 'moon', 'system' => 'monitor'] as $mode => $modeIcon)
            <button type="button" class="arm-segment__item" aria-label="{{ __(ucfirst($mode)) }}"
                x-on:click="$store.armTheme.set('{{ $mode }}')"
                x-bind:aria-pressed="$store.armTheme.mode === '{{ $mode }}'">
                <armada:icon :name="$modeIcon" size="md" />
            </button>
        @endforeach
    </div>
@endif
