@props(['legend' => null, 'description' => null])
<fieldset {{ $attributes->class('arm-fieldset') }}>
    @if ($legend)<legend>{{ $legend }}</legend>@endif
    @if ($description)<p class="arm-fieldset__description">{{ $description }}</p>@endif
    {{ $slot }}
</fieldset>
