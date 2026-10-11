@props([
    'locales' => [],      // ['tr' => '/tr/…', 'en' => '/en/…'] (code => URL) or ['tr', 'en'] (URL = ?lang=code)
    'current' => null,    // default: app()->getLocale()
    'variant' => 'menu',  // menu (dropdown) | select (native, best on phones) | inline (segment)
    'align' => 'end',
    'compact' => false,   // trigger shows only the code
])
@php
    // Endonyms: a Turkish speaker looks for "Türkçe", never "Turkish".
    $names = ['tr' => 'Türkçe', 'en' => 'English', 'de' => 'Deutsch', 'fr' => 'Français', 'es' => 'Español', 'it' => 'Italiano', 'pt' => 'Português', 'nl' => 'Nederlands',
        'ru' => 'Русский', 'uk' => 'Українська', 'pl' => 'Polski', 'ar' => 'العربية', 'fa' => 'فارسی', 'he' => 'עברית', 'zh' => '中文', 'ja' => '日本語', 'ko' => '한국어',
        'vi' => 'Tiếng Việt', 'id' => 'Bahasa Indonesia', 'ms' => 'Bahasa Melayu', 'th' => 'ไทย', 'hi' => 'हिन्दी', 'az' => 'Azərbaycanca', 'el' => 'Ελληνικά', 'sv' => 'Svenska'];
    $current ??= str_replace('_', '-', app()->getLocale());
    $items = collect(array_is_list($locales) ? array_combine($locales, array_map(fn ($c) => '?lang='.$c, $locales)) : $locales)
        ->map(fn ($url, $code) => ['code' => $code, 'url' => $url, 'name' => $names[strtolower(strtok((string) $code, '-'))] ?? strtoupper((string) $code)]);
    $active = $items->firstWhere('code', $current);
@endphp
@if ($variant === 'select')
    <armada:select {{ $attributes }} :aria-label="__('Language')" x-data x-on:change="window.location.href = $event.target.value">
        @foreach ($items as $item)<option value="{{ $item['url'] }}" @selected($item['code'] === $current)>{{ $item['name'] }}</option>@endforeach
    </armada:select>
@elseif ($variant === 'inline')
    <nav {{ $attributes->class('arm-lang arm-lang--inline') }} aria-label="{{ __('Language') }}">
        @foreach ($items as $item)
            <a href="{{ $item['url'] }}" hreflang="{{ $item['code'] }}" lang="{{ $item['code'] }}" @if ($item['code'] === $current) aria-current="true" @endif title="{{ $item['name'] }}">{{ strtoupper($item['code']) }}</a>
        @endforeach
    </nav>
@else
    <armada:dropdown :align="$align" {{ $attributes }}>
        <x-slot:trigger>
            <armada:button variant="ghost" icon="languages" :aria-label="__('Language')">@unless ($compact){{ $active['name'] ?? strtoupper($current) }}@else{{ strtoupper($current) }}@endunless</armada:button>
        </x-slot:trigger>
        @foreach ($items as $item)
            <armada:menu.item :href="$item['url']" :checked="$item['code'] === $current" hreflang="{{ $item['code'] }}" lang="{{ $item['code'] }}">{{ $item['name'] }}</armada:menu.item>
        @endforeach
    </armada:dropdown>
@endif
