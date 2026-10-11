(() => {
  const KEY = 'arm-sidebar-collapsed';
  const desktop = matchMedia('(min-width: 1024px)');

  const register = (Alpine) => {
    Alpine.data('armApp', () => ({
      open: false,
      collapsed: (() => { try { return localStorage.getItem(KEY) === '1'; } catch { return false; } })(),
      toggle() {
        if (!desktop.matches) { this.open = !this.open; return; }
        this.collapsed = !this.collapsed;
        try { localStorage.setItem(KEY, this.collapsed ? '1' : '0'); } catch {}
      },
      init() {
        desktop.addEventListener('change', () => { this.open = false; });
        document.addEventListener('livewire:navigated', () => { this.open = false; });
      },
    }));
  };

  if (window.Alpine) register(window.Alpine);
  else document.addEventListener('alpine:init', () => register(window.Alpine));
})();
