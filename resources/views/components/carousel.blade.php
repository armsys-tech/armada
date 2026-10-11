@props(['perView' => 1, 'gap' => '12px', 'arrows' => true, 'dots' => true, 'autoplay' => null]) {{-- autoplay: ms --}}
<div {{ $attributes->class('arm-carousel') }}
    x-data="armCarousel({{ (int) $autoplay }})" style="--slide: calc((100% - {{ $gap }} * {{ $perView - 1 }}) / {{ $perView }}); --gap: {{ $gap }}">
    <div class="arm-carousel__track" x-ref="track" x-on:scroll.debounce.60ms="sync()">{{ $slot }}</div>
    @if ($arrows)
        <armada:button variant="outline" size="sm" icon="chevron-left" pill class="arm-carousel__nav arm-carousel__nav--prev" :aria-label="__('Previous')" x-on:click="go(index - 1)" x-show="index > 0" />
        <armada:button variant="outline" size="sm" icon="chevron-right" pill class="arm-carousel__nav arm-carousel__nav--next" :aria-label="__('Next')" x-on:click="go(index + 1)" x-show="index < count - 1" />
    @endif
    @if ($dots)
        <div class="arm-carousel__dots">
            <template x-for="i in count" :key="i">
                <button type="button" class="arm-carousel__dot" x-bind:aria-current="index === i - 1" x-bind:aria-label="i" x-on:click="go(i - 1)"></button>
            </template>
        </div>
    @endif
</div>
