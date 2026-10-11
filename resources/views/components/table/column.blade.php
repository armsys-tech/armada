@props(['sortable' => false, 'sorted' => false, 'direction' => 'asc', 'align' => null]) {{-- align: end --}}
<th {{ $attributes->except(['wire:click', 'x-on:click'])->class(['arm-table__num' => $align === 'end'])->merge(['aria-sort' => $sorted ? ($direction === 'asc' ? 'ascending' : 'descending') : null]) }}>
    @if ($sortable)
        <button type="button" class="arm-table__sort" {{ $attributes->only(['wire:click', 'x-on:click']) }} @if ($sorted) aria-sort="{{ $direction }}" @endif>
            {{ $slot }}<armada:icon :name="! $sorted ? 'chevrons-up-down' : ($direction === 'asc' ? 'chevron-up' : 'chevron-down')" size="xs" />
        </button>
    @else
        {{ $slot }}
    @endif
</th>
