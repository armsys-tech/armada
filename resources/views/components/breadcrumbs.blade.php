@props(['separator' => 'chevron']) {{-- chevron | slash --}}
<nav {{ $attributes->class(['arm-breadcrumbs', 'arm-breadcrumbs--slash' => $separator === 'slash'])->merge(['aria-label' => __('Breadcrumb')]) }}>
    <ol>{{ $slot }}</ol>
</nav>
