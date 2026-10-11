<?php

namespace Armada;

use Illuminate\Support\Str;
use Illuminate\View\ComponentAttributeBag;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class Armada
{
    private const ASSETS = [
        'armada.css' => 'text/css; charset=utf-8',
        'armada.js' => 'application/javascript; charset=utf-8',
        'alpine.js' => 'application/javascript; charset=utf-8',
    ];

    /** @var array<string, string> */
    private static array $icons = [];

    /** Stylesheet + an inline script that sets the theme before first paint (no light/dark flash). */
    public static function styles(): string
    {
        return '<link rel="stylesheet" href="'.self::url('armada.css').'">'
            .'<script>(()=>{let m="system";try{m=localStorage.getItem("arm-theme")||m}catch(e){}'
            .'const d=m==="dark"||(m==="system"&&matchMedia("(prefers-color-scheme: dark)").matches);'
            .'document.documentElement.dataset.armTheme=d?"dark":"light"})()</script>';
    }

    /** Armada behaviours. Livewire already ships Alpine; plain Blade pages get the bundled copy. */
    public static function scripts(): string
    {
        $html = '<script src="'.self::url('armada.js').'"></script>';

        if (! class_exists(\Livewire\Livewire::class)) {
            $html .= '<script src="'.self::url('alpine.js').'" defer></script>';
        }

        return $html;
    }

    /**
     * Name, validation key, id and invalid flag for a form control.
     * The name falls back to the wire:model property; items[0][email] maps to the key items.0.email.
     *
     * @return array{0: ?string, 1: ?string, 2: string, 3: bool}
     */
    public static function field(ComponentAttributeBag $attributes, ?string $name, $errors = null): array
    {
        $name ??= $attributes->whereStartsWith('wire:model')->first();
        $key = $name ? rtrim(str_replace(['[', ']'], ['.', ''], $name), '.') : null;
        $id = $attributes->get('id') ?? ($key ? 'arm-'.str_replace('.', '-', $key) : 'arm-'.Str::lower(Str::random(8)));
        $invalid = $key !== null && $errors !== null && $errors->has($key);

        return [$name, $key, $id, $invalid];
    }

    /** Normalises ['a' => 'A'], ['A', 'B'] or [['value' => …, 'label' => …], …] into a list of option arrays. */
    public static function options(iterable $options): array
    {
        $list = [];
        $isList = is_array($options) && array_is_list($options);

        foreach ($options as $value => $option) {
            $list[] = is_array($option)
                ? $option + ['value' => $option['value'] ?? $value, 'label' => $option['label'] ?? (string) ($option['value'] ?? $value)]
                : ['value' => $isList ? $option : $value, 'label' => (string) $option];
        }

        return $list;
    }

    public static function icon(string $name): string
    {
        if (! preg_match('/^[a-z0-9-]+$/', $name)) {
            throw new InvalidArgumentException("Invalid Armada icon name [$name].");
        }

        return self::$icons[$name] ??= file_get_contents(self::iconPath($name));
    }

    public static function asset(string $file): BinaryFileResponse
    {
        abort_unless(isset(self::ASSETS[$file]), 404);

        return response()->file(__DIR__.'/../dist/'.$file, [
            'Content-Type' => self::ASSETS[$file],
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    private static function iconPath(string $name): string
    {
        $path = __DIR__.'/../resources/icons/'.$name.'.svg';

        if (! is_file($path)) {
            throw new InvalidArgumentException("Armada icon [$name] not found.");
        }

        return $path;
    }

    private static function url(string $file): string
    {
        return route('armada.asset', ['file' => $file, 'v' => filemtime(__DIR__.'/../dist/'.$file)]);
    }
}
