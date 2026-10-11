@props(['heading' => null, 'icon' => null, 'expanded' => false, 'trailing' => null]) {{-- heading: text or slot · trailing: slot right of the heading (badges, counts) --}}
<details {{ $attributes->class('arm-accordion__item') }} @if ($expanded) open @endif>
    <summary>@if ($icon)<armada:icon :name="$icon" />@endif<span class="arm-accordion__heading">{{ $heading }}</span>@if ($trailing)<span class="arm-accordion__trailing">{{ $trailing }}</span>@endif<armada:icon name="chevron-down" size="md" class="arm-accordion__chevron" /></summary>
    <div class="arm-accordion__content">{{ $slot }}</div>
</details>
