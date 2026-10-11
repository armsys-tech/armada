@props(['label' => null, 'description' => null, 'name' => null, 'multiple' => false, 'accept' => null, 'hint' => null, 'icon' => 'cloud-upload'])
@php [$name, $key, $id] = \Armada\Armada::field($attributes, $name, $errors ?? null); @endphp
<armada:field :label="$label" :description="$description" :name="$key">
    <div x-data="armFiles()">
        <label class="arm-dropzone" for="{{ $id }}" x-bind:class="{ 'is-dragging': dragging }"
            x-on:dragover.prevent="dragging = true" x-on:dragleave="dragging = false"
            x-on:drop.prevent="dragging = false; $refs.input.files = $event.dataTransfer.files; $refs.input.dispatchEvent(new Event('change', { bubbles: true }))">
            <span class="arm-dropzone__icon"><armada:icon :name="$icon" size="xl" /></span>
            <span class="arm-dropzone__title">{!! __('<b>Click to upload</b> or drag and drop') !!}</span>
            @if ($hint)<span class="arm-dropzone__hint">{{ $hint }}</span>@endif
            <input type="file" x-ref="input" x-on:change="read($event.target.files)"
                {{ $attributes->merge(['id' => $id, 'name' => $name ? $name.($multiple ? '[]' : '') : null, 'multiple' => $multiple, 'accept' => $accept]) }}>
        </label>
        <div class="arm-file-list" x-show="files.length" x-cloak>
            <template x-for="(f, i) in files" :key="f.name + i">
                <div class="arm-file">
                    <span class="arm-file__icon"><template x-if="f.url"><img x-bind:src="f.url" alt=""></template><template x-if="! f.url"><armada:icon name="file" size="lg" /></template></span>
                    <span class="arm-file__meta">
                        <span class="arm-file__name" x-text="f.name"></span>
                        <span class="arm-file__size" x-text="f.size"></span>
                    </span>
                </div>
            </template>
        </div>
    </div>
</armada:field>
