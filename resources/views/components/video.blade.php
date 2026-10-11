@props([
    'src' => null,          // file URL (mp4/webm) or a YouTube / Vimeo link
    'poster' => null,
    'ratio' => '16/9',
    'caption' => null,
    'controls' => true, 'autoplay' => false, 'muted' => false, 'loop' => false, 'playsinline' => true,
    'tracks' => [],         // [['src' => '/subs/tr.vtt', 'lang' => 'tr', 'label' => 'Türkçe', 'default' => true]]
    'title' => null,
])
@php
    // YouTube / Vimeo: nothing loads from the provider until the viewer presses play (privacy + speed).
    $embed = null;
    if (preg_match('~(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)([\w-]{11})~', (string) $src, $m)) {
        $embed = 'https://www.youtube-nocookie.com/embed/'.$m[1].'?autoplay=1&rel=0';
        $poster ??= 'https://i.ytimg.com/vi/'.$m[1].'/hqdefault.jpg';
    } elseif (preg_match('~vimeo\.com/(?:video/)?(\d+)~', (string) $src, $m)) {
        $embed = 'https://player.vimeo.com/video/'.$m[1].'?autoplay=1&dnt=1';
    }
@endphp
<figure {{ $attributes->class('arm-video') }} style="--ratio: {{ $ratio }}">
    <div class="arm-video__frame">
        @if ($embed)
            <div x-data="{ play: false }" class="arm-video__facade">
                <template x-if="play"><iframe src="{{ $embed }}" title="{{ $title ?? $caption ?? 'Video' }}" allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen loading="lazy"></iframe></template>
                <button type="button" x-show="! play" x-on:click="play = true" aria-label="{{ __('Play') }}">
                    @if ($poster)<img src="{{ $poster }}" alt="" loading="lazy">@endif
                    <span class="arm-video__play"><armada:icon name="play" /></span>
                </button>
            </div>
        @else
            <video src="{{ $src }}" @if ($poster) poster="{{ $poster }}" @endif preload="metadata" @if ($title) aria-label="{{ $title }}" @endif
                @if ($controls) controls @endif @if ($autoplay) autoplay @endif @if ($muted || $autoplay) muted @endif @if ($loop) loop @endif @if ($playsinline) playsinline @endif>
                @foreach ($tracks as $t)<track kind="subtitles" src="{{ $t['src'] }}" srclang="{{ $t['lang'] }}" label="{{ $t['label'] ?? $t['lang'] }}" @if ($t['default'] ?? false) default @endif>@endforeach
                {{ $slot }}
            </video>
        @endif
    </div>
    @if ($caption)<figcaption>{{ $caption }}</figcaption>@endif
</figure>
