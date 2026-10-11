@props([
    'label',
    'value',
    'icon' => null,
    'trend' => null,        // e.g. 12.4 or -3 (percent)
    'description' => null,  // e.g. "geçen haftaya göre"
    'sparkline' => null,    // array of numbers
    'card' => true,
])
@php $up = $trend !== null && $trend >= 0; @endphp
<div {{ $attributes->class(['arm-stat', 'arm-card arm-stat--card' => $card]) }}>
    <div class="arm-stat__label">@if ($icon)<armada:icon :name="$icon" />@endif{{ $label }}</div>
    <div class="arm-stat__value">{{ $value }}</div>
    @if ($trend !== null || $description)
        <div class="arm-stat__footer">
            @if ($trend !== null)
                <span @class(['arm-trend', 'arm-trend--up' => $up, 'arm-trend--down' => ! $up])>
                    <armada:icon :name="$up ? 'trending-up' : 'trending-down'" />{{ $up ? '+' : '' }}{{ $trend }}%
                </span>
            @endif
            @if ($description)<span class="arm-stat__description">{{ $description }}</span>@endif
        </div>
    @endif
    @if ($sparkline)<armada:sparkline :values="$sparkline" :tone="$up || $trend === null ? 'primary' : 'danger'" style="margin-top:6px" />@endif
</div>
