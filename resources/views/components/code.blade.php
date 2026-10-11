@props(['language' => null, 'filename' => null, 'code' => null, 'copy' => true])
@php
    $source = $code ?? trim((string) $slot, "\n");
    $labels = \Illuminate\Support\Js::from(['copy' => __('Copy'), 'copied' => __('Copied')]);
@endphp
<div {{ $attributes->class('arm-code') }} x-data="{ copied: false }">
    @if ($filename || $language)
        <div class="arm-code__header">
            <armada:icon name="file-code" size="sm" /><span>{{ $filename ?? $language }}</span>
            @if ($copy)
                <armada:button variant="ghost" size="xs" class="arm-code__copy" x-on:click="navigator.clipboard.writeText($root.querySelector('code').innerText); copied = true; setTimeout(() => copied = false, 1500)">
                    <span x-text="copied ? @js(__('Copied')) : @js(__('Copy'))">{{ __('Copy') }}</span>
                </armada:button>
            @endif
        </div>
    @elseif ($copy)
        <armada:button variant="ghost" size="sm" class="arm-code__copy" x-bind:aria-label="copied ? {{ $labels }}.copied : {{ $labels }}.copy"
            x-on:click="navigator.clipboard.writeText($root.querySelector('code').innerText); copied = true; setTimeout(() => copied = false, 1500)">
            <armada:icon name="copy" size="sm" x-show="! copied" /><armada:icon name="check" size="sm" x-show="copied" x-cloak />
        </armada:button>
    @endif
    <pre><code>{{ $source }}</code></pre>
</div>
