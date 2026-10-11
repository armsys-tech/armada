@props([
    'label' => null, 'description' => null, 'name' => null,
    'value' => '',          // HTML
    'placeholder' => null,
    'toolbar' => ['bold', 'italic', 'underline', 'strike', '|', 'h2', 'h3', '|', 'ul', 'ol', 'quote', 'code', '|', 'link', 'clear'],
    'minHeight' => '160px',
    'plainPaste' => true,   // pasted content arrives as text, not as foreign HTML
    'required' => false,
])
{{-- Rich text editor. The value is HTML: sanitise it on the server before storing or showing it (e.g. with HTML Purifier). --}}
@php
    [$name, $key, $id, $invalid] = \Armada\Armada::field($attributes, $name, $errors ?? null);
    $tools = [
        'bold' => ['bold', 'Bold', 'Ctrl+B'], 'italic' => ['italic', 'Italic', 'Ctrl+I'], 'underline' => ['underline', 'Underline', 'Ctrl+U'], 'strike' => ['strikethrough', 'Strikethrough', null],
        'h2' => ['heading-2', 'Heading', null], 'h3' => ['heading-3', 'Subheading', null], 'ul' => ['list', 'Bulleted list', null], 'ol' => ['list-ordered', 'Numbered list', null],
        'quote' => ['text-quote', 'Quote', null], 'code' => ['code', 'Code', null], 'link' => ['link', 'Link', 'Ctrl+K'], 'clear' => ['remove-formatting', 'Clear formatting', null],
    ];
@endphp
<armada:field :label="$label" :description="$description" :name="$key" :for="$id" :required="$required">
    <div {{ $attributes->only(['class', 'style'])->class('arm-editor') }} @if ($invalid) data-invalid @endif
        {{ $attributes->whereStartsWith(['wire:model', 'x-model']) }}
        x-data="armEditor({ value: @js((string) $value), plainPaste: @js($plainPaste) })" x-modelable="value">
        <div class="arm-editor__toolbar" role="toolbar" aria-label="{{ __('Formatting') }}">
            @foreach ($toolbar as $tool)
                @if ($tool === '|')<span class="arm-editor__sep"></span>@continue @endif
                @php [$icon, $title, $kbd] = $tools[$tool]; @endphp
                <armada:tooltip :content="__($title)" :kbd="$kbd">
                    <button type="button" class="arm-editor__tool" aria-label="{{ __($title) }}" x-on:mousedown.prevent x-on:click="run('{{ $tool }}')" x-bind:aria-pressed="active.includes('{{ $tool }}')"><armada:icon :name="$icon" /></button>
                </armada:tooltip>
            @endforeach
        </div>
        <div class="arm-editor__content arm-prose" contenteditable="true" role="textbox" aria-multiline="true" id="{{ $id }}" x-ref="content"
            data-placeholder="{{ $placeholder }}" style="min-height: {{ $minHeight }}"
            x-on:input="sync()" x-on:keyup="state()" x-on:mouseup="state()" x-on:paste="paste($event)"
            x-on:keydown.ctrl.k.prevent="run('link')" x-on:keydown.meta.k.prevent="run('link')"></div>
        @if ($name)<input type="hidden" name="{{ $name }}" x-bind:value="value">@endif
    </div>
</armada:field>
