@props(['title' => null, 'description' => null, 'actions' => null, 'breadcrumbs' => null])
<div {{ $attributes->class('arm-page-header') }}>
    <div class="arm-page-header__text">
        @if ($breadcrumbs)<div style="margin-bottom:10px">{{ $breadcrumbs }}</div>@endif
        <h1 class="arm-page-header__title">{{ $title }}</h1>
        @if ($description)<p class="arm-page-header__description">{{ $description }}</p>@endif
        {{ $slot }}
    </div>
    @if ($actions)<div class="arm-page-header__actions">{{ $actions }}</div>@endif
</div>
