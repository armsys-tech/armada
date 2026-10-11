@props([
    'values' => [],       // calendar: ['2026-10-11' => 4, …] · matrix: ['Pzt' => ['00' => 3, '01' => 0, …], …]
    'type' => 'calendar', // calendar (GitHub-style year) | matrix (rows × columns)
    'from' => null, 'to' => null,   // calendar range, default: the last 52 weeks
    'tone' => 'primary',
    'locale' => null,
    'unit' => null,       // tooltip suffix, e.g. "yayın"
    'showValues' => false,// matrix: print numbers inside cells
    'legend' => true,
])
@php
    $locale ??= str_replace('_', '-', app()->getLocale());
    $levelOf = function (float $v, float $max): int { return $v <= 0 || $max <= 0 ? 0 : min(4, (int) ceil($v / $max * 4)); };
    $mix = [0 => null, 1 => 22, 2 => 45, 3 => 70, 4 => 100];
    if ($type === 'calendar') {
        $end = \Carbon\CarbonImmutable::parse($to ?? 'today');
        $start = \Carbon\CarbonImmutable::parse($from ?? $end->subWeeks(52)->addDay())->startOfWeek(\Carbon\CarbonInterface::MONDAY);
        $max = (float) max([0, ...array_values($values)]);
        $weeks = [];
        for ($d = $start; $d <= $end->endOfWeek(\Carbon\CarbonInterface::SUNDAY); $d = $d->addDay()) {
            $weeks[intdiv($start->diffInDays($d), 7)][] = $d;
        }
    } else {
        $columns = collect($values)->flatMap(fn ($row) => array_keys((array) $row))->unique()->values();
        $max = (float) collect($values)->flatten()->max();
    }
@endphp
<div {{ $attributes->class(['arm-heatmap', 'arm-heatmap--'.$type]) }} data-tone="{{ $tone }}" style="--cols: {{ $type === 'calendar' ? count($weeks) : $columns->count() }}">
    <div class="arm-heatmap__scroll" x-data x-init="$el.scrollLeft = $el.scrollWidth">
        @if ($type === 'calendar')
            <div class="arm-heatmap__grid">
                <span></span>
                @foreach ($weeks as $i => $week)
                    <span class="arm-heatmap__month">@if ($week[0]->day <= 7 || $i === 0){{ $week[0]->locale($locale)->translatedFormat('M') }}@endif</span>
                @endforeach
                @foreach (range(0, 6) as $row)
                    <span class="arm-heatmap__dow">@if ($row % 2 === 0){{ $start->addDays($row)->locale($locale)->translatedFormat('D') }}@endif</span>
                    @foreach ($weeks as $week)
                        @php $day = $week[$row] ?? null; $v = $day ? (float) ($values[$day->toDateString()] ?? 0) : 0; $l = $levelOf($v, $max); @endphp
                        @if ($day && $day <= $end && $day >= $start)
                            <span class="arm-heatmap__cell" data-level="{{ $l }}" @if ($mix[$l]) style="--mix: {{ $mix[$l] }}%" @endif title="{{ $day->locale($locale)->isoFormat('LL') }}: {{ $v + 0 }} {{ $unit }}"></span>
                        @else
                            <span></span>
                        @endif
                    @endforeach
                @endforeach
            </div>
        @else
            <div class="arm-heatmap__grid">
                <span></span>
                @foreach ($columns as $c)<span class="arm-heatmap__col">{{ $c }}</span>@endforeach
                @foreach ($values as $rowLabel => $row)
                    <span class="arm-heatmap__dow">{{ $rowLabel }}</span>
                    @foreach ($columns as $c)
                        @php $v = (float) ($row[$c] ?? 0); $l = $levelOf($v, $max); @endphp
                        <span class="arm-heatmap__cell" data-level="{{ $l }}" @if ($mix[$l]) style="--mix: {{ $mix[$l] }}%" @endif title="{{ $rowLabel }} · {{ $c }}: {{ $v + 0 }} {{ $unit }}">@if ($showValues && $v){{ $v + 0 }}@endif</span>
                    @endforeach
                @endforeach
            </div>
        @endif
    </div>
    @if ($legend)
        <div class="arm-heatmap__legend">{{ __('Less') }} @foreach ($mix as $l => $m)<span class="arm-heatmap__cell" data-level="{{ $l }}" @if ($m) style="--mix: {{ $m }}%" @endif></span>@endforeach {{ __('More') }}</div>
    @endif
</div>
