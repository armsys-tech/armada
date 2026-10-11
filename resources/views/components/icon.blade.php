@props(['name', 'size' => null])
<span {{ $attributes->class(['arm-icon', 'arm-icon--'.$size => $size]) }} aria-hidden="true">{!! \Armada\Armada::icon($name) !!}</span>
