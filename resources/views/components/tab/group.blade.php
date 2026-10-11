@props(['default' => null, 'vertical' => false])
<div {{ $attributes->class(['arm-tab-group', 'arm-tab-group--vertical' => $vertical]) }}
    x-data="{ tab: @js($default) }"
    x-modelable="tab"
    x-init="tab ??= $el.querySelector('[role=tab]')?.dataset.name">{{ $slot }}</div>
