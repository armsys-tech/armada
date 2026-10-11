@props(['name', 'email' => null, 'avatar' => null, 'compact' => false, 'chevron' => true])
<button type="button" {{ $attributes->class(['arm-profile', 'arm-profile--compact' => $compact]) }}>
    <armada:avatar :src="$avatar" :name="$name" size="sm" />
    @unless ($compact)
        <span class="arm-profile__text">
            <span class="arm-profile__name">{{ $name }}</span>
            @if ($email)<span class="arm-profile__email">{{ $email }}</span>@endif
        </span>
        @if ($chevron)<armada:icon name="chevrons-up-down" size="sm" class="arm-profile__chevron" />@endif
    @endunless
</button>
