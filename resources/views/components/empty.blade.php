@props(['icon' => 'inbox', 'title' => null, 'description' => null, 'actions' => null, 'bordered' => false])
<div {{ $attributes->class(['arm-empty', 'arm-empty--bordered' => $bordered]) }}>
    @if ($icon)<div class="arm-empty__icon"><armada:icon :name="$icon" /></div>@endif
    @if ($title)<h3 class="arm-empty__title">{{ $title }}</h3>@endif
    @if ($description)<p class="arm-empty__description">{{ $description }}</p>@endif
    {{ $slot }}
    @if ($actions)<div class="arm-empty__actions">{{ $actions }}</div>@endif
</div>
