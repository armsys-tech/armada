@props(['heading' => null, 'expandable' => false, 'expanded' => true])
@if ($expandable)
    <details {{ $attributes->class('arm-navlist__group') }} @if ($expanded) open @endif>
        <summary class="arm-navlist__heading">{{ $heading }}<armada:icon name="chevron-down" size="sm" class="arm-navlist__chevron" /></summary>
        <div class="arm-navlist__items">{{ $slot }}</div>
    </details>
@else
    <div {{ $attributes->class('arm-navlist__group') }} role="group" @if ($heading) aria-label="{{ $heading }}" @endif>
        @if ($heading)<div class="arm-navlist__heading">{{ $heading }}</div>@endif
        <div class="arm-navlist__items">{{ $slot }}</div>
    </div>
@endif
