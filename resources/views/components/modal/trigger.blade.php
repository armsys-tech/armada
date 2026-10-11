@props(['name'])
<div {{ $attributes }} style="display: contents" x-data x-on:click="$dispatch('modal-show', { name: @js($name) })">{{ $slot }}</div>
