@props(['gap' => null, 'row' => false, 'align' => null, 'justify' => null, 'wrap' => null])
{{-- gap: xs | sm | md | lg | xl or any CSS length · align: start | center | end | stretch | baseline · justify: start | center | end | between · wrap: rows wrap by default --}}
@php
    $gaps = ['xs' => '4px', 'sm' => '8px', 'md' => '16px', 'lg' => '24px', 'xl' => '32px'];
    $flex = ['start' => 'flex-start', 'end' => 'flex-end', 'between' => 'space-between', 'center' => 'center', 'stretch' => 'stretch', 'baseline' => 'baseline'];
@endphp
<div {{ $attributes->class(['arm-stack', 'arm-stack--row' => $row])->style([
    '--gap: '.($gaps[$gap] ?? $gap) => $gap,
    'align-items: '.($flex[$align] ?? $align) => $align,
    'justify-content: '.($flex[$justify] ?? $justify) => $justify,
    'flex-wrap: '.($wrap ? 'wrap' : 'nowrap') => $wrap !== null,
]) }}>{{ $slot }}</div>
