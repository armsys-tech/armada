@props(['min' => null, 'cols' => null, 'gap' => null]) {{-- min: column min width (auto-fill) · cols: fixed column count · gap: CSS length --}}
<div {{ $attributes->class('arm-grid')->style([
    '--min: '.$min => $min,
    'grid-template-columns: repeat('.$cols.', minmax(0, 1fr))' => $cols,
    '--gap: '.$gap => $gap,
]) }}>{{ $slot }}</div>
