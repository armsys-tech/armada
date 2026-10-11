# Armada

Blade and Livewire UI components for Laravel by Armsys Technology.
Light and dark themes, mobile-first, no Tailwind required (all CSS lives in `@layer arm.*`, so Tailwind utilities still win).

## Install

```bash
composer require armsys-tech/armada
```

```blade
<head>
    @armadaStyles
</head>
<body>
    <armada:button variant="solid" tone="primary" icon="plus">New server</armada:button>
    <armada:input wire:model="email" type="email" label="Email" icon="mail" />

    @armadaScripts {{-- after @livewireScripts; bundles Alpine when Livewire is absent --}}
</body>
```

Customise a component: `php artisan vendor:publish --tag=armada-components`.

Icons: [Lucide](https://lucide.dev) (ISC), `<armada:icon name="settings" />`.

## Development

`tests/tag-compiler.php` checks the `<armada:*>` tag rewrite: `php tests/tag-compiler.php`.

## License

MIT © 2026 Armsys Technology
