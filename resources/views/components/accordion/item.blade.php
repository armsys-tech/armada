@props(['heading', 'icon' => null, 'expanded' => false])
<details {{ $attributes->class('arm-accordion__item') }} @if ($expanded) open @endif>
    <summary>@if ($icon)<armada:icon :name="$icon" />@endif<span>{{ $heading }}</span><armada:icon name="chevron-down" size="md" class="arm-accordion__chevron" /></summary>
    <div class="arm-accordion__content">{{ $slot }}</div>
</details>
