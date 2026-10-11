@props(['size' => null]) {{-- e.g. 24px --}}
<span {{ $attributes->class('arm-spinner')->merge(['role' => 'status', 'aria-label' => __('Loading'), 'style' => $size ? "font-size: $size" : null]) }}></span>
