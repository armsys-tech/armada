# Changelog

All notable changes to this project are documented here. The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and the project uses [Semantic Versioning](https://semver.org/).

## [1.0.0] - 2026-10-11

First stable release.

### Added

- 100+ anonymous Blade components in the `armada` namespace, usable as `<armada:name>` or `<x-armada::name>`.
- Layout: app shell with collapsible sidebar rail, mobile drawer and bottom navigation; container, stack, grid, card, page header, toolbar.
- Navigation: navlist, navbar, breadcrumbs, tabs, pagination, stepper, dropdown menus with submenus, command palette, language switcher.
- Forms: input, textarea, select, combobox, checkbox, radio, switch, slider, number input, OTP, date picker, time picker, colour picker, file upload, rating, rich text editor — all with `wire:model` and automatic validation errors.
- Overlays and feedback: modal, drawer and sheet; toaster with Livewire, flash and JavaScript APIs; tooltip, popover, callout, banner, progress, spinner, skeleton, empty state.
- Data display: responsive table, list, description list, avatar, badge, stat, timeline, tree, accordion, carousel, image, video (privacy-friendly YouTube/Vimeo facade), code, event calendar, kanban board with touch drag and drop, infinite scroll.
- Charts: bar/line/area chart, donut, sparkline, calendar and matrix heatmaps — server-rendered SVG, no chart library.
- Light, dark and system themes with no flash on load; design tokens as `--arm-*` CSS custom properties; styles in cascade layers so application CSS always wins.
- Mobile behaviour: bottom sheets for menus and pickers on phones, larger hit areas on touch screens, tables that become cards.
- 2,100+ Lucide icons.
- English and Turkish interface strings.
- `vendor:publish --tag=armada-components` to override individual components.

[1.0.0]: https://github.com/armsys-tech/armada/releases/tag/v1.0.0
