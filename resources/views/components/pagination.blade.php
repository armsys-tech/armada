@props([
    'paginator' => null,  // a LengthAwarePaginator; or set current/last/url
    'current' => 1,
    'last' => 1,
    'total' => null,
    'perPage' => null,
    'url' => '?page={page}',
    'info' => true,
])
@php
    if ($paginator) {
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $total = $paginator->total();
        $perPage = $paginator->perPage();
        $link = fn ($p) => $paginator->url($p);
    } else {
        $link = fn ($p) => str_replace('{page}', $p, $url);
    }

    // 1 … 4 5 [6] 7 8 … 20
    $pages = collect([1, $last, ...range(max(1, $current - 2), min($last, $current + 2))])->unique()->sort()->values();
    $items = [];
    foreach ($pages as $i => $page) {
        if ($i > 0 && $page - $pages[$i - 1] > 1) $items[] = null;
        $items[] = $page;
    }
@endphp
<nav {{ $attributes->class('arm-pagination') }} aria-label="{{ __('Pagination') }}">
    @if ($info && $total !== null && $perPage)
        <span class="arm-pagination__info">{{ __(':from–:to / :total', ['from' => ($current - 1) * $perPage + 1, 'to' => min($total, $current * $perPage), 'total' => number_format($total)]) }}</span>
    @endif
    <div class="arm-pagination__pages">
        <a class="arm-pagination__page" href="{{ $link(max(1, $current - 1)) }}" aria-label="{{ __('Previous') }}" @if ($current <= 1) aria-disabled="true" @endif><armada:icon name="chevron-left" size="md" /></a>
        @foreach ($items as $page)
            @if ($page === null)
                <span class="arm-pagination__page arm-pagination__number" aria-hidden="true">…</span>
            @else
                <a class="arm-pagination__page arm-pagination__number" href="{{ $link($page) }}" @if ($page === $current) aria-current="page" @endif>{{ $page }}</a>
            @endif
        @endforeach
        <span class="arm-pagination__compact">{{ $current }} / {{ $last }}</span>
        <a class="arm-pagination__page" href="{{ $link(min($last, $current + 1)) }}" aria-label="{{ __('Next') }}" @if ($current >= $last) aria-disabled="true" @endif><armada:icon name="chevron-right" size="md" /></a>
    </div>
</nav>
