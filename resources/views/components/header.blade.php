@props(['title' => null, 'actions' => null, 'sticky' => true, 'transparent' => false])
<header {{ $attributes->class(['arm-header', 'arm-header--static' => ! $sticky, 'arm-header--transparent' => $transparent]) }}>
    {{ $slot }}
    @if ($title)<div class="arm-header__title">{{ $title }}</div>@endif
    @if ($actions)<div class="arm-header__actions">{{ $actions }}</div>@endif
</header>
