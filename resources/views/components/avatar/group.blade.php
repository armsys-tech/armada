@props(['max' => null, 'total' => null, 'size' => 'md'])
<div {{ $attributes->class('arm-avatar-group') }}>
    {{ $slot }}
    @if ($total !== null && $max !== null && $total > $max)
        <span @class(['arm-avatar', 'arm-avatar-group__more', 'arm-avatar--'.$size => $size !== 'md'])>+{{ $total - $max }}</span>
    @endif
</div>
