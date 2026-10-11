@props([
    'handler' => null,  // Livewire method called on drop: $wire[handler](item, column, index)
])
{{-- Cards drag between columns with mouse, pen or touch (long-press on touch). Every drop also dispatches `kanban-move` ({ item, from, to, index }). --}}
<div {{ $attributes->class('arm-kanban') }} x-data="armKanban(@js($handler))">{{ $slot }}</div>
