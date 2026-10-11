@props(['required' => false, 'badge' => null])
<label {{ $attributes->class('arm-label') }}>{{ $slot }}@if ($required)<span class="arm-label__required" aria-hidden="true">*</span>@endif @if ($badge)<span class="arm-label__badge">{{ $badge }}</span>@endif</label>
