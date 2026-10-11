@props(['stacked' => false])
<dl {{ $attributes->class(['arm-deflist', 'arm-deflist--stacked' => $stacked]) }}>{{ $slot }}</dl>
