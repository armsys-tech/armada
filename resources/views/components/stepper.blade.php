@props([
    'steps' => [],      // ['Hesap', 'Profil'] or [['label' => 'Hesap', 'description' => '…', 'error' => false], …]
    'current' => 0,     // zero-based index of the active step
    'vertical' => false,
])
@php
    $steps = collect($steps)->map(fn ($s) => is_array($s) ? $s : ['label' => $s])->values();
@endphp
<ol {{ $attributes->class(['arm-stepper', 'arm-stepper--vertical' => $vertical]) }}>
    @foreach ($steps as $i => $step)
        @php $state = ($step['error'] ?? false) ? 'error' : ($i < $current ? 'complete' : ($i === $current ? 'current' : 'upcoming')); @endphp
        <li class="arm-stepper__step" data-state="{{ $state }}" @if ($state === 'current') aria-current="step" @endif>
            <span class="arm-stepper__marker">
                @if ($state === 'complete')<armada:icon name="check" size="md" />
                @elseif ($state === 'error')<armada:icon name="x" size="md" />
                @else{{ $i + 1 }}@endif
            </span>
            <span class="arm-stepper__text">
                <span class="arm-stepper__label">{{ $step['label'] }}</span>
                @isset($step['description'])<span class="arm-stepper__description">{{ $step['description'] }}</span>@endisset
            </span>
        </li>
    @endforeach
</ol>
@unless ($vertical)
    <p class="arm-stepper__summary">{{ __('Step :n of :total', ['n' => $current + 1, 'total' => $steps->count()]) }} · <strong>{{ $steps[$current]['label'] ?? '' }}</strong></p>
@endunless
