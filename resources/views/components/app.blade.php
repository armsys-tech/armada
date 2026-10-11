@props(['sidebar' => null, 'header' => null, 'bottomNav' => null, 'content' => 'default']) {{-- content: default | narrow | flush --}}
<div {{ $attributes->class('arm-app') }} x-data="armApp" x-bind:class="{ 'arm-app--collapsed': collapsed }" x-on:keydown.escape.window="open = false">
    {{ $sidebar }}
    <template x-if="open"><div class="arm-app__backdrop" x-on:click="open = false"></div></template>
    <div class="arm-app__main">
        {{ $header }}
        <main @class(['arm-app__content', 'arm-app__content--'.$content => $content !== 'default'])>{{ $slot }}</main>
    </div>
    {{ $bottomNav }}
</div>
