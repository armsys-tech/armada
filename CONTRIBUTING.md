# Contributing

Thanks for helping improve Armada.

- **Bugs and ideas:** open an issue at https://github.com/armsys-tech/armada/issues with the component, the Laravel/Livewire versions, the browser and, if possible, a short Blade snippet that shows the problem.
- **Pull requests:** keep them focused on one change. Describe what changed and how it looks on desktop and on a phone, in light and dark mode — a screenshot helps.

## Working on the code

- Components live in `resources/views/components` as anonymous Blade components. Document every prop in the `@props` block.
- Styles live in `resources/css`, behaviour in `resources/js`. Run `node build.mjs` and commit the regenerated `dist/` files with your change.
- Use the existing design tokens (`--arm-*`) instead of hard-coded colours, sizes or durations.
- New interface strings go through `__()` and need a Turkish entry in `lang/tr.json`.
- Every interactive component must work with keyboard, touch and screen readers, and fit a 360px-wide screen without horizontal scrolling.

## Security

Please report security issues privately by email to the maintainers rather than in a public issue.

By contributing you agree that your contributions are licensed under the MIT license.
