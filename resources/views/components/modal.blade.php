@props([
    'name',                   // open with $dispatch('modal-show', { name }) · Livewire: $this->dispatch('modal-show', name: '…') · JS: Armada.modal('…').show()
    'title' => null,
    'description' => null,
    'icon' => null,
    'tone' => null,
    'variant' => 'modal',     // modal | drawer | sheet
    'position' => 'right',    // drawer: right | left
    'size' => 'md',           // modal: sm | md | lg | xl | full · drawer: sm | md | lg
    'footer' => null,
    'closable' => true,
    'dismissible' => true,    // close on backdrop click / Escape
    'sheetOnMobile' => true,
])
@php
    $class = match ($variant) {
        'drawer' => ['arm-drawer', 'arm-drawer--left' => $position === 'left', 'arm-drawer--'.$size => $size !== 'md'],
        'sheet' => ['arm-sheet'],
        default => ['arm-modal', 'arm-modal--'.$size => $size !== 'md', 'arm-modal--no-sheet' => ! $sheetOnMobile],
    };
@endphp
<dialog {{ $attributes->class($class)->merge(['data-modal' => $name, 'aria-labelledby' => $title ? "arm-modal-$name-title" : null]) }}
    x-data="armModal(@js($name), @js($dismissible))" tabindex="-1" wire:ignore.self>
    @if ($variant !== 'drawer')<div class="arm-modal__handle" x-on:pointerdown="drag($event)"></div>@endif
    @if ($title || $closable)
        <div class="arm-modal__header" @if ($tone) data-tone="{{ $tone }}" @endif>
            @if ($icon)<span class="arm-modal__icon"><armada:icon :name="$icon" size="lg" /></span>@endif
            <div class="arm-modal__heading">
                @if ($title)<h2 class="arm-modal__title" id="arm-modal-{{ $name }}-title">{{ $title }}</h2>@endif
                @if ($description)<p class="arm-modal__description">{{ $description }}</p>@endif
            </div>
            @if ($closable)<armada:button variant="ghost" size="sm" icon="x" class="arm-modal__close" :aria-label="__('Close')" x-on:click="close()" />@endif
        </div>
    @endif
    <div class="arm-modal__body">{{ $slot }}</div>
    @if ($footer)<div {{ $footer->attributes->class('arm-modal__footer') }}>{{ $footer }}</div>@endif
</dialog>
