{{-- Opens the drawer on phones/tablets, collapses the sidebar to a rail on desktop. --}}
<button type="button" {{ $attributes->class('arm-btn arm-btn--ghost arm-btn--square')->merge(['data-tone' => 'neutral', 'aria-label' => __('Toggle sidebar')]) }} x-on:click="toggle()">
    <armada:icon name="panel-left" size="md" class="arm-show-desktop" />
    <armada:icon name="menu" size="md" class="arm-hide-desktop" />
</button>
