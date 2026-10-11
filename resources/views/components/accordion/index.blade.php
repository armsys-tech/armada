@props(['variant' => 'plain', 'exclusive' => false]) {{-- plain | bordered | separated · exclusive: one item open at a time --}}
<div {{ $attributes->class(['arm-accordion', 'arm-accordion--'.$variant => $variant !== 'plain']) }}
    @if ($exclusive) data-exclusive="{{ 'arm-acc-'.\Illuminate\Support\Str::random(6) }}" x-data x-init="$el.querySelectorAll(':scope > details').forEach(d => d.name = $el.dataset.exclusive)" @endif>{{ $slot }}</div>
