@props(['item', 'title' => null, 'description' => null, 'locked' => false])
<article {{ $attributes->class('arm-kanban__card') }} data-item="{{ $item }}" @unless ($locked) data-draggable @endunless tabindex="0">
    @if ($title)<div class="arm-kanban__card-title">{{ $title }}</div>@endif
    @if ($description)<div class="arm-kanban__card-text">{{ $description }}</div>@endif
    {{ $slot }}
</article>
