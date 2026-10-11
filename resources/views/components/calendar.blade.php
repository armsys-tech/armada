@props([
    'events' => [],     // [['date' => '2026-10-14', 'title' => 'Yayın', 'time' => '20:00', 'tone' => 'primary', 'href' => null], …]
    'month' => null,    // 'Y-m' shown first; default: this month
    'locale' => null,
    'max' => 3,         // event chips per day before "+N"
    'agenda' => true,   // list of the selected day's events under the grid (always on phones)
])
@php $locale ??= str_replace('_', '-', app()->getLocale()); @endphp
{{-- Picking a day dispatches `calendar-select` ({ date }); Livewire: x-on:calendar-select="$wire.selectDay($event.detail.date)" --}}
<div {{ $attributes->class(['arm-cal', 'arm-cal--agenda' => $agenda]) }}
    x-data="armCalendar({ events: @js(array_values(collect($events)->all())), month: @js($month), locale: @js($locale) })">
    <div class="arm-cal__head">
        <div class="arm-cal__title" x-text="title()"></div>
        <armada:button size="sm" x-on:click="today()">{{ __('Today') }}</armada:button>
        <armada:button size="sm" variant="ghost" icon="chevron-left" :aria-label="__('Previous month')" x-on:click="shift(-1)" />
        <armada:button size="sm" variant="ghost" icon="chevron-right" :aria-label="__('Next month')" x-on:click="shift(1)" />
    </div>
    <div class="arm-cal__grid" role="grid">
        <template x-for="d in weekdays()"><div class="arm-cal__dow" role="columnheader" x-text="d"></div></template>
        <template x-for="day in days()" :key="day.iso">
            <div class="arm-cal__day" role="gridcell" tabindex="0" x-on:click="pick(day.iso)" x-on:keydown.enter="pick(day.iso)"
                x-bind:class="{ 'is-outside': day.outside, 'is-today': day.today, 'is-selected': day.iso === selected }">
                <span class="arm-cal__num" x-text="day.n"></span>
                <div class="arm-cal__events">
                    <template x-for="e in day.events.slice(0, {{ (int) $max }})">
                        <a class="arm-cal__event" x-bind:href="e.href || null" x-bind:data-tone="e.tone || 'primary'" x-on:click.stop="e.href || pick(day.iso)">
                            <span class="arm-cal__time" x-show="e.time" x-text="e.time"></span><span x-text="e.title"></span>
                        </a>
                    </template>
                    <span class="arm-cal__more" x-show="day.events.length > {{ (int) $max }}" x-text="'+' + (day.events.length - {{ (int) $max }})"></span>
                </div>
                <span class="arm-cal__dots"><template x-for="e in day.events.slice(0, 3)"><i x-bind:data-tone="e.tone || 'primary'"></i></template></span>
            </div>
        </template>
    </div>
    @if ($agenda)
        <div class="arm-cal__agenda">
            <div class="arm-cal__agenda-title" x-text="selectedLabel()"></div>
            <template x-for="e in eventsOn(selected)">
                <a class="arm-cal__agenda-item" x-bind:href="e.href || null" x-bind:data-tone="e.tone || 'primary'">
                    <i></i><span class="arm-cal__time" x-text="e.time || ''"></span><span x-text="e.title"></span>
                </a>
            </template>
            <div class="arm-cal__agenda-empty" x-show="! eventsOn(selected).length">{{ __('No events') }}</div>
        </div>
    @endif
</div>
