{{-- Place once in the layout. Show toasts with Armada.toast({ title, text, tone }), $dispatch('toast', {...}),
     Livewire $this->dispatch('toast', title: '…', tone: 'success') or session()->flash('toast', '…'). --}}
@props(['position' => 'bottom-right']) {{-- bottom-right | bottom-left | bottom-center | top-right | top-center --}}
<div {{ $attributes->class('arm-toaster') }} data-position="{{ $position }}" x-data="armToaster(@js(session('toast')))" aria-live="polite" role="region" aria-label="{{ __('Notifications') }}">
    <template x-for="t in toasts" :key="t.id">
        <div class="arm-toast" x-bind:data-tone="t.tone" role="status"
            x-on:mouseenter="pause(t)" x-on:mouseleave="resume(t)">
            <span class="arm-icon" aria-hidden="true" x-html="icons[t.tone] || icons.info"></span>
            <div class="arm-toast__content">
                <span class="arm-toast__title" x-text="t.title" x-show="t.title"></span>
                <span class="arm-toast__text" x-text="t.text" x-show="t.text"></span>
            </div>
            <template x-if="t.action"><button type="button" class="arm-btn arm-btn--soft arm-btn--sm arm-toast__action" data-tone="neutral" x-text="t.action.label" x-on:click="t.action.onClick?.(); dismiss(t.id)"></button></template>
            <button type="button" class="arm-btn arm-btn--ghost arm-btn--sm arm-btn--square arm-toast__close" data-tone="neutral" aria-label="{{ __('Close') }}" x-on:click="dismiss(t.id)"><span class="arm-icon arm-icon--md">{!! \Armada\Armada::icon('x') !!}</span></button>
            <span class="arm-toast__progress" x-show="t.duration" x-bind:style="`animation-duration: ${t.duration}ms; animation-play-state: ${t.paused ? 'paused' : 'running'}`"></span>
        </div>
    </template>
    <template x-ref="icons">
        <span data-info>{!! \Armada\Armada::icon('info') !!}</span><span data-success>{!! \Armada\Armada::icon('circle-check') !!}</span>
        <span data-warning>{!! \Armada\Armada::icon('triangle-alert') !!}</span><span data-danger>{!! \Armada\Armada::icon('circle-alert') !!}</span>
    </template>
</div>
