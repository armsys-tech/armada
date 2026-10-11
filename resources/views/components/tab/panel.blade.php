@props(['name'])
<div {{ $attributes->class('arm-tab-panel') }} role="tabpanel" tabindex="0" x-show="tab === @js($name)" @if (! $attributes->has('x-cloak')) x-cloak @endif>{{ $slot }}</div>
