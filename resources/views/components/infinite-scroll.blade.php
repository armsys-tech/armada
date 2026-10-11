@props([
    'action' => null,     // Livewire method to call, e.g. "loadMore"
    'more' => true,       // false: show the end message and stop observing
    'margin' => '400px',  // start loading this far before the end comes into view
    'end' => null,        // text when there is nothing more
])
{{-- Put it after the last item. Without `action` it dispatches `load-more` and waits for the next render. --}}
<div {{ $attributes->class('arm-infinite')->merge(['data-more' => $more ? 'true' : null]) }} x-data="armInfinite(@js($action), @js($margin))">
    @if ($more)
        <div class="arm-infinite__loading" x-show="loading"><armada:spinner /> {{ __('Loading…') }}</div>
        <armada:button variant="ghost" x-show="! loading && ! observed" x-cloak x-on:click="load()">{{ __('Load more') }}</armada:button>
    @else
        <div class="arm-infinite__end">{{ $end ?? __('That is everything') }}</div>
    @endif
</div>
