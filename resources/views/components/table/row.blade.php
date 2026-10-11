@props(['selected' => false])
<tr {{ $attributes->class(['is-selected' => $selected]) }}>{{ $slot }}</tr>
