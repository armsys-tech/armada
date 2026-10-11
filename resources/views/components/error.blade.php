@props(['name' => null, 'message' => null, 'bag' => 'default'])
@php
    // items[0][email] -> items.0.email, the key Laravel's validator uses
    $key = $name ? rtrim(str_replace(['[', ']'], ['.', ''], $name), '.') : null;
    $message ??= $key ? $errors->getBag($bag)->first($key) : null;
@endphp
@if ($message)
    <p {{ $attributes->class('arm-error') }} role="alert"><armada:icon name="circle-alert" size="sm" />{{ $message }}</p>
@endif
