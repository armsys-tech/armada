@props(['name', 'title' => null, 'count' => null, 'tone' => null, 'actions' => null, 'footer' => null])
<section {{ $attributes->class('arm-kanban__column') }} data-column="{{ $name }}" @if ($tone) data-tone="{{ $tone }}" @endif>
    <header class="arm-kanban__head">
        @if ($tone)<i class="arm-kanban__dot"></i>@endif
        <span class="arm-kanban__title">{{ $title ?? $name }}</span>
        @if ($count !== null)<span class="arm-kanban__count">{{ $count }}</span>@endif
        @if ($actions)<span class="arm-kanban__actions">{{ $actions }}</span>@endif
    </header>
    <div class="arm-kanban__cards" data-cards>{{ $slot }}</div>
    @if ($footer)<footer class="arm-kanban__foot">{{ $footer }}</footer>@endif
</section>
