@props(['gap' => null, 'row' => false]) {{-- gap: xs | sm | md | lg | xl or any CSS length --}}
@php $gaps = ['xs' => '4px', 'sm' => '8px', 'md' => '16px', 'lg' => '24px', 'xl' => '32px']; @endphp
<div {{ $attributes->class(['arm-stack', 'arm-stack--row' => $row])->style(['--gap: '.($gaps[$gap] ?? $gap) => $gap]) }}>{{ $slot }}</div>
