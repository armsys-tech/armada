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

## Components

| Group | Components |
| --- | --- |
| Layout | app, sidebar (+ toggle), header, brand, profile, container, page-header, toolbar, separator, spacer, card |
| Navigation | navlist (+ item, group), navbar, bottom-nav, breadcrumbs, tabs / tab / tab.group / tab.panel, pagination, stepper, dropdown + menu.*, command |
| Actions | button, toggle-group, chip, chip-group, fab (+ action), theme-switcher |
| Forms | field, label, description, error, input, textarea, select, combobox, checkbox (+ group), radio (+ group), switch, slider, number-input, otp, date-picker, color-picker, file-upload, rating, fieldset |
| Data | table (+ columns, column, rows, row, cell), list (+ item, subheader), deflist, avatar (+ group), badge, notification-badge, stat, timeline, tree, accordion, carousel, image, empty, code, heading, text, link, kbd, eyebrow |
| Feedback | callout, banner, toaster, modal (+ trigger, close; drawer and sheet variants), tooltip, popover, progress, spinner, skeleton |
| Charts | chart (bar, stacked, line, area), sparkline, donut |

Turkish translations ship in `lang/tr.json`.

Icons: [Lucide](https://lucide.dev) (ISC), `<armada:icon name="settings" />`.

## Development

Sources live in `resources/css` and `resources/js`; `node build.mjs` bundles them into `dist/`.

`tests/tag-compiler.php` checks the `<armada:*>` tag rewrite: `php tests/tag-compiler.php`.

## License

MIT © 2026 Armsys Technology
