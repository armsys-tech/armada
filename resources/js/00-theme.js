(() => {
  const KEY = 'arm-theme';
  const dark = matchMedia('(prefers-color-scheme: dark)');

  const read = () => { try { return localStorage.getItem(KEY) || 'system'; } catch { return 'system'; } };
  const apply = (mode) => {
    const isDark = mode === 'dark' || (mode === 'system' && dark.matches);
    document.documentElement.dataset.armTheme = isDark ? 'dark' : 'light';
  };

  const register = (Alpine) => {
    Alpine.store('armTheme', {
      mode: read(),
      set(mode) {
        this.mode = mode;
        try { localStorage.setItem(KEY, mode); } catch {}
        apply(mode);
      },
      toggle() {
        this.set(document.documentElement.dataset.armTheme === 'dark' ? 'light' : 'dark');
      },
    });

    dark.addEventListener('change', () => apply(Alpine.store('armTheme').mode));

    // Livewire's wire:navigate swaps <html> attributes; keep the theme.
    document.addEventListener('livewire:navigated', () => apply(Alpine.store('armTheme').mode));
  };

  if (window.Alpine) register(window.Alpine);
  else document.addEventListener('alpine:init', () => register(window.Alpine));
})();
