{{-- ⌘K / Ctrl+K command palette. items: [['label' => 'Panel', 'icon' => 'house', 'href' => '/', 'group' => 'Sayfalar', 'kbd' => 'G P'], …] --}}
@props(['items' => [], 'placeholder' => null, 'name' => 'command', 'hotkey' => true])
<dialog {{ $attributes->class('arm-command') }} x-data="armCommand(@js(collect($items)->map(fn ($i) => $i + ['svg' => \Armada\Armada::icon($i['icon'] ?? 'arrow-right')])->values()), @js($name), @js($hotkey))" x-on:click="if ($event.target === $el) $el.close()" aria-label="{{ __('Command palette') }}">
    <div class="arm-command__search">
        <armada:icon name="search" size="lg" />
        <input type="text" x-ref="input" x-model="query" placeholder="{{ $placeholder ?? __('Search or jump to…') }}" autocomplete="off"
            x-on:keydown.down.prevent="move(1)" x-on:keydown.up.prevent="move(-1)" x-on:keydown.enter.prevent="run(filtered[active])">
        <kbd class="arm-kbd arm-kbd--hint">Esc</kbd>
    </div>
    <div class="arm-command__list" role="listbox">
        <template x-for="(item, i) in filtered" :key="item.label + i">
            <div>
                <div class="arm-menu__heading" x-show="item.group && (i === 0 || filtered[i - 1].group !== item.group)" x-text="item.group"></div>
                <a class="arm-menu__item" role="option" x-bind:href="item.href || '#'" x-bind:data-active="active === i ? '' : null" x-on:mouseenter="active = i" x-on:click.prevent="run(item)">
                    <span class="arm-icon" x-html="item.svg"></span>
                    <span class="arm-menu__label" x-text="item.label"></span>
                    <kbd class="arm-menu__kbd" x-show="item.kbd" x-text="item.kbd"></kbd>
                </a>
            </div>
        </template>
        <div class="arm-listbox__empty" x-show="! filtered.length">{{ __('No results') }}</div>
    </div>
    <div class="arm-command__footer">
        <span><kbd class="arm-kbd">↑</kbd><kbd class="arm-kbd">↓</kbd> {{ __('navigate') }}</span>
        <span><kbd class="arm-kbd">↵</kbd> {{ __('open') }}</span>
        <span><kbd class="arm-kbd">Esc</kbd> {{ __('close') }}</span>
    </div>
</dialog>
