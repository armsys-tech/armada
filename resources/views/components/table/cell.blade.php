@props(['label' => null, 'align' => null, 'primary' => false, 'check' => false]) {{-- label: shown on phones when the row becomes a card --}}
<td {{ $attributes->class(['arm-table__num' => $align === 'end', 'arm-table__primary' => $primary, 'arm-table__check' => $check])->merge(['data-label' => $label]) }}>{{ $slot }}</td>
