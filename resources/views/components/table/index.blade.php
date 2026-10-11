@props([
    'stack' => true,      // rows become cards on phones
    'striped' => false,
    'compact' => false,
    'plain' => false,     // no outer border (inside a card)
    'footer' => null,
])
<div {{ $attributes->only(['class', 'style'])->class(['arm-table-wrap', 'arm-table-wrap--plain' => $plain]) }}>
    <table {{ $attributes->except(['class', 'style'])->class(['arm-table', 'arm-table--stack' => $stack, 'arm-table--striped' => $striped, 'arm-table--compact' => $compact]) }}>
        {{ $slot }}
    </table>
    {{ $footer }}
</div>
