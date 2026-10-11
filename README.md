# Armada

Blade and Livewire UI components for Laravel, by Armsys Technology.

**Live demo:** https://armsys-tech.github.io/armada/ — every component with examples, light and dark, works on phones.

- **100+ components** — app shell, navigation, forms, overlays, tables, charts, calendar, kanban, rich text editor and more.
- **Light and dark** themes with a system mode and no flash on load.
- **Mobile-first** — menus and pickers become bottom sheets on phones, tables turn into cards, touch targets grow on coarse pointers.
- **No build step, no Tailwind** — one stylesheet and one script. All CSS lives in `@layer arm.*`, so your own CSS or Tailwind utilities always win.
- **Livewire-ready** — `wire:model` on every form control, validation errors picked up automatically, server-side toasts and modals.
- **2,100+ icons** from [Lucide](https://lucide.dev), inlined as SVG.

## Requirements

- PHP 8.3+
- Laravel 12 or 13
- Livewire 3 or 4 (optional — without it Armada ships its own copy of Alpine.js)

## Installation

```bash
composer require armsys-tech/armada
```

The service provider is auto-discovered. Add the two directives to your layout:

```blade
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    @armadaStyles
</head>
<body>
    {{ $slot }}

    @livewireScripts {{-- if you use Livewire --}}
    @armadaScripts
</body>
</html>
```

`@armadaStyles` adds the stylesheet and a tiny inline script that applies the saved theme before first paint. `@armadaScripts` adds the component behaviours, plus Alpine.js when Livewire is not installed (Livewire already bundles Alpine). Assets are served by the package from `/armada/{file}` with long-lived cache headers and a file-time version string, so there is nothing to publish or build.

## Usage

Components are anonymous Blade components in the `armada` namespace. Both spellings work:

```blade
<armada:button variant="solid" tone="primary" icon="plus">New creator</armada:button>
<x-armada::button variant="solid" tone="primary" icon="plus">New creator</x-armada::button>
```

### Forms and Livewire

Every form control accepts `label`, `description` and `name`, and forwards `wire:model` / `x-model`. When no `name` is given, the `wire:model` property is used, and the matching validation error is shown under the field.

```blade
<form wire:submit="save">
    <armada:input wire:model="email" type="email" label="Email" icon="mail" required />
    <armada:select wire:model="country" label="Country" :options="['TR' => 'Türkiye', 'DE' => 'Germany']" />
    <armada:date-picker wire:model="startsOn" label="Start date" />
    <armada:time-picker wire:model="startsAt" label="Start time" step="30" min="09:00" max="18:00" />
    <armada:switch wire:model="active" label="Active" />
    <armada:button type="submit" variant="solid" tone="primary">Save</armada:button>
</form>
```

Nested names map to validation keys: `items[0][email]` → `items.0.email`.

### Tones and variants

Coloured components take a `tone`: `primary`, `neutral`, `success`, `warning`, `danger`, `info`, `purple`, `pink`, `orange`, `yellow`, `teal`. Buttons take a `variant`: `solid`, `soft`, `outline` (default), `ghost`, `plain`, and a `size`: `xs`, `sm`, `md`, `lg`, `xl`.

### Modals, drawers and sheets

```blade
<armada:modal name="invite" title="Invite creator" variant="modal" size="md">
    …
    <x-slot:footer>
        <armada:button x-on:click="$dispatch('modal-close', { name: 'invite' })">Cancel</armada:button>
    </x-slot:footer>
</armada:modal>

<armada:button x-on:click="$dispatch('modal-show', { name: 'invite' })">Invite</armada:button>
```

From Livewire: `$this->dispatch('modal-show', name: 'invite')`. From JavaScript: `Armada.modal('invite').show()`. `variant` is `modal`, `drawer` or `sheet`; modals become bottom sheets on phones unless `:sheet-on-mobile="false"`.

### Toasts

Place `<armada:toaster />` once in your layout, then:

```php
// Livewire
$this->dispatch('toast', title: 'Saved', tone: 'success');

// Redirect with a flash message
return redirect()->route('creators.index')->with('toast', 'Creator created');
```

```js
Armada.toast({ title: 'Copied', text: 'Link is on your clipboard', tone: 'info' });
```

### Application shell

```blade
<armada:app>
    <x-slot:sidebar>
        <armada:sidebar>
            <armada:brand name="Backstage" href="/" />
            <armada:navlist>
                <armada:navlist.item icon="house" href="/" :current="request()->is('/')">Dashboard</armada:navlist.item>
                <armada:navlist.item icon="users" href="/creators" badge="12">Creators</armada:navlist.item>
            </armada:navlist>
        </armada:sidebar>
    </x-slot:sidebar>

    <x-slot:header>
        <armada:header>
            <armada:theme-switcher />
        </armada:header>
    </x-slot:header>

    {{ $slot }}
</armada:app>
```

The sidebar collapses to an icon rail on desktop and becomes a drawer on phones. Add a `bottomNav` slot with `<armada:bottom-nav>` for phone navigation.

### Layout helpers

```blade
<armada:container>                                  {{-- page width --}}
    <armada:stack gap="lg">                         {{-- vertical stack --}}
        <armada:stack row justify="between" wrap>…</armada:stack>
        <armada:grid min="240px" gap="16px">…</armada:grid>   {{-- auto-fit grid --}}
    </armada:stack>
</armada:container>
```

## Components

| Group | Components |
|---|---|
| Layout | `app`, `sidebar`, `header`, `brand`, `profile`, `container`, `stack`, `grid`, `card`, `separator`, `spacer`, `page-header`, `toolbar` |
| Navigation | `navlist`, `bottom-nav`, `navbar`, `breadcrumbs`, `tabs` / `tab`, `pagination`, `stepper`, `dropdown` / `menu.item` / `menu.submenu`, `command`, `lang-switcher` |
| Actions | `button`, `toggle-group`, `chip`, `chip-group`, `fab` |
| Forms | `input`, `textarea`, `select`, `combobox`, `checkbox`, `radio`, `switch`, `slider`, `number-input`, `otp`, `date-picker`, `time-picker`, `color-picker`, `file-upload`, `rating`, `editor`, `field`, `fieldset` |
| Feedback & overlays | `callout`, `banner`, `toaster`, `modal`, `tooltip`, `popover`, `progress`, `spinner`, `skeleton`, `empty` |
| Data display | `table`, `list`, `deflist`, `avatar`, `badge`, `notification-badge`, `stat`, `timeline`, `tree`, `accordion`, `carousel`, `image`, `video`, `code`, `heading`, `text`, `link`, `kbd`, `calendar`, `kanban`, `infinite-scroll` |
| Charts | `chart` (bar, line, area), `donut`, `sparkline`, `heatmap` |
| Utilities | `icon`, `theme-switcher` |

Each component documents its props in the `@props` block at the top of its file in `resources/views/components`. The reference project renders every component with live examples.

### Notes on a few components

- **`editor`** — a lightweight rich text editor whose value is HTML. **Always sanitise that HTML on the server** before storing or rendering it (for example with [HTML Purifier](http://htmlpurifier.org/)). Render saved content inside `.arm-prose` to get the same typography.
- **`kanban`** — drag and drop with mouse, pen or touch (long-press on touch). Pass `handler="move"` to call `$wire.move(item, column, index)`; every drop also dispatches a `kanban-move` browser event.
- **`calendar`** — monthly event calendar; event chips on desktop, dots and a day agenda on phones. Selecting a day dispatches `calendar-select`.
- **`infinite-scroll`** — calls a Livewire action (`action="loadMore"`) when the sentinel scrolls into view, while `:more="$hasMore"` is true.
- **`video`** — plays files with `<video>`, and YouTube/Vimeo links through a click-to-load facade (youtube-nocookie.com), so no third-party request is made until the user presses play.
- **`table`** — sortable headers, row selection; rows become cards on phones.

## Theming

Armada reads everything from CSS custom properties prefixed `--arm-`. Override them after `@armadaStyles`:

```css
:root {
    --arm-font: "Geist", system-ui, sans-serif;
    --arm-radius-md: 10px;
}
[data-arm-theme="dark"]  { --arm-primary: #7c5cff; --arm-primary-hover: #9479ff; }
[data-arm-theme="light"] { --arm-primary: #6741ff; --arm-primary-hover: #5530f0; }
```

Useful groups: colours (`--arm-bg`, `--arm-surface-1`, `--arm-text`, `--arm-border`, `--arm-primary`…), type (`--arm-font`, `--arm-fs-*`), radius (`--arm-radius-*`), shadows (`--arm-shadow-*`), motion (`--arm-dur-*`, `--arm-ease-*`), layout (`--arm-header-h`, `--arm-sidebar-w`, `--arm-frame`), z-index (`--arm-z-*`) and the chart palette (`--arm-chart-1` … `--arm-chart-6`). See `resources/css/00-core.css` and `01-tokens.css` for the full list.

The theme is set by `data-arm-theme="light|dark"` on `<html>`. `<armada:theme-switcher />` lets users choose light, dark or system; the choice is kept in `localStorage` under `arm-theme`.

Because Armada's styles live in cascade layers (`arm.tokens`, `arm.base`, `arm.components`, `arm.utilities`), any unlayered CSS you write — including Tailwind — overrides them without `!important`.

## Customising components

Publish the Blade files and edit your copies:

```bash
php artisan vendor:publish --tag=armada-components
```

Published files in `resources/views/vendor/armada/components` take precedence over the package's. You only need to keep the files you changed.

## Translations

Interface strings (Close, Search…, Pick a time, Today, …) go through `__()`. Armada ships English and Turkish (`lang/tr.json`). To add a language or change a string, add the keys to your application's `lang/{locale}.json`.

## Browser support

Current versions of Chrome, Edge, Firefox and Safari (iOS 16.4+). Armada uses cascade layers, container queries, `color-mix()` and `:has()`.

## Development

`docs/` is the live demo: a static HTML export of the reference project's component showcase, served by GitHub Pages. It is not part of the Composer package.

```bash
node build.mjs   # concatenates resources/css and resources/js into dist/
```

Edit the sources in `resources/css` and `resources/js`, never `dist/` directly. Commit the rebuilt `dist/` files — they are what the package serves.

## License

Armada is open-source software licensed under the [MIT license](LICENSE). © 2026 Armsys Technology.

It bundles third-party code under their own permissive licenses; see [THIRD-PARTY-NOTICES.md](THIRD-PARTY-NOTICES.md):

- [Lucide](https://lucide.dev) icons — ISC License (some icons derived from Feather, MIT)
- [Alpine.js](https://alpinejs.dev) — MIT License (served only when Livewire is not installed)
