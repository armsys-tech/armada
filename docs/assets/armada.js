/* Armada — https://github.com/armsys-tech/armada — MIT. Built file, edit resources/js. */
/* ---- 00-theme.js ---- */
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

/* ---- 10-app.js ---- */
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

/* ---- 20-components.js ---- */
(() => {
  const iso = (d) => `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
  const parse = (s) => { if (!s) return null; const [y, m, d] = s.split('-').map(Number); return new Date(y, m - 1, d); };
  const lockScroll = (on) => { document.documentElement.style.overflow = on ? 'hidden' : ''; };

  /* Public API: Armada.toast(), Armada.modal(name).show() */
  window.Armada = Object.assign(window.Armada || {}, {
    toast(options) { window.dispatchEvent(new CustomEvent('toast', { detail: typeof options === 'string' ? { title: options } : options })); },
    modal(name) {
      return {
        show: () => window.dispatchEvent(new CustomEvent('modal-show', { detail: { name } })),
        close: () => window.dispatchEvent(new CustomEvent('modal-close', { detail: { name } })),
      };
    },
  });

  const register = (Alpine) => {
    /* ---------- Modal / drawer / sheet ---------- */
    Alpine.data('armModal', (name, dismissible = true) => ({
      init() {
        const el = this.$el;
        const match = (e) => (e.detail?.name ?? e.detail?.[0]?.name) === name;
        window.addEventListener('modal-show', (e) => match(e) && this.show());
        window.addEventListener('modal-close', (e) => match(e) && this.close());
        el.addEventListener('close', () => lockScroll(false));
        el.addEventListener('cancel', (e) => { if (!dismissible) e.preventDefault(); });
        el.addEventListener('click', (e) => { if (dismissible && e.target === el) this.close(); });
      },
      show() {
        if (this.$el.open) return;
        this.$el.showModal(); lockScroll(true);
        (this.$el.querySelector('[autofocus]') ?? this.$el).focus({ preventScroll: true });
      },
      close() { this.$el.close(); },
      drag(e) {
        const el = this.$el, y0 = e.clientY;
        let dy = 0;
        el.classList.add('is-dragging');
        const move = (ev) => { dy = Math.max(0, ev.clientY - y0); el.style.transform = `translateY(${dy}px)`; };
        const up = () => {
          removeEventListener('pointermove', move); removeEventListener('pointerup', up);
          el.classList.remove('is-dragging'); el.style.transition = 'transform .2s';
          if (dy > 90) { el.style.transform = 'translateY(100%)'; setTimeout(() => { this.close(); el.style.transform = ''; el.style.transition = ''; }, 180); }
          else { el.style.transform = ''; setTimeout(() => { el.style.transition = ''; }, 200); }
        };
        addEventListener('pointermove', move); addEventListener('pointerup', up);
      },
    }));

    /* ---------- Dropdown / popover ---------- */
    // Tooltip: fixed-positioned on show so overflow:hidden ancestors (cards, tables) can't clip it.
    Alpine.data('armTooltip', () => ({
      place() {
        const tip = this.$refs.tip; if (!tip) return;
        const r = this.$el.getBoundingClientRect(), pos = tip.dataset.position, gap = 8;
        tip.style.position = 'fixed'; tip.style.inset = 'auto'; tip.style.transform = 'none';
        const w = tip.offsetWidth, h = tip.offsetHeight;
        let x = r.left + r.width / 2 - w / 2, y = r.top - h - gap;
        if (pos === 'bottom') y = r.bottom + gap;
        if (pos === 'left') { x = r.left - w - gap; y = r.top + r.height / 2 - h / 2; }
        if (pos === 'right') { x = r.right + gap; y = r.top + r.height / 2 - h / 2; }
        x = Math.max(8, Math.min(x, innerWidth - w - 8)); y = Math.max(8, Math.min(y, innerHeight - h - 8));
        tip.style.left = x + 'px'; tip.style.top = y + 'px';
      },
    }));

    Alpine.data('armDropdown', () => ({
      open: false,
      toggle() { this.open ? this.close() : this.show(); },
      show() {
        this.open = true;
        this.$nextTick(() => {
          const menu = this.$refs.menu;
          if (!menu || matchMedia('(max-width: 639px)').matches) return;
          // keep the panel inside the viewport
          const r = menu.getBoundingClientRect();
          if (r.right > innerWidth - 8) menu.dataset.align = 'end';
          if (r.bottom > innerHeight - 8 && r.top > r.height + 60) menu.dataset.position = 'top';
          menu.querySelector('[role=menuitem], [role=menuitemcheckbox], input, button')?.focus({ preventScroll: true });
        });
      },
      close(focus = false) {
        if (!this.open) return;
        this.open = false;
        if (focus) this.$refs.trigger?.querySelector('button, a, [tabindex]')?.focus();
      },
      focusNext(dir) {
        const items = [...this.$refs.menu.querySelectorAll('.arm-menu__item:not([disabled])')];
        const i = items.indexOf(document.activeElement);
        items[(i + dir + items.length) % items.length]?.focus();
      },
    }));

    /* ---------- Combobox ---------- */
    Alpine.data('armCombobox', ({ options, value, multiple, creatable }) => ({
      options, value, multiple, creatable, open: false, query: '', active: 0,
      get filtered() {
        const q = this.query.trim().toLocaleLowerCase();
        return q ? this.options.filter((o) => `${o.label} ${o.description ?? ''}`.toLocaleLowerCase().includes(q)) : this.options;
      },
      labelOf(v) { return this.options.find((o) => String(o.value) === String(v))?.label ?? v; },
      isSelected(v) { return this.multiple ? this.value.map(String).includes(String(v)) : String(this.value) === String(v); },
      toggle() { this.open ? this.close() : this.openAndFocus(); },
      openAndFocus() {
        this.open = true; this.active = Math.max(0, this.filtered.findIndex((o) => this.isSelected(o.value)));
        this.$nextTick(() => (this.$refs.search ?? this.$el.querySelector('[role=listbox]'))?.focus({ preventScroll: true }));
      },
      close() { this.open = false; this.query = ''; },
      move(d) { const n = this.filtered.length; if (n) this.active = (this.active + d + n) % n; },
      pickActive() { const o = this.filtered[this.active]; o ? this.pick(o.value) : (this.creatable && this.create()); },
      pick(v) {
        if (this.multiple) { this.value = this.isSelected(v) ? this.value.filter((x) => String(x) !== String(v)) : [...this.value, v]; this.query = ''; }
        else { this.value = v; this.close(); }
      },
      remove(v) { this.value = this.value.filter((x) => String(x) !== String(v)); },
      clear() { this.value = this.multiple ? [] : null; },
      create() {
        const label = this.query.trim(); if (!label) return;
        this.options.push({ value: label, label }); this.pick(label);
      },
    }));

    /* ---------- Date picker ---------- */
    Alpine.data('armDatePicker', ({ value, mode, min, max, locale, placeholder }) => ({
      value, mode, placeholder, open: false,
      view: parse(mode === 'range' ? value?.start : value) ?? new Date(),
      init() { this.view = new Date(this.view.getFullYear(), this.view.getMonth(), 1); },
      title() { return this.view.toLocaleDateString(locale, { month: 'long', year: 'numeric' }); },
      weekdays() { return [...Array(7)].map((_, i) => new Date(2024, 0, i + 1).toLocaleDateString(locale, { weekday: 'narrow' })); },
      days() {
        const first = new Date(this.view.getFullYear(), this.view.getMonth(), 1);
        const start = new Date(first); start.setDate(1 - ((first.getDay() + 6) % 7)); // weeks start on Monday
        const today = iso(new Date());
        return [...Array(42)].map((_, i) => {
          const d = new Date(start); d.setDate(start.getDate() + i);
          const s = iso(d);
          return { iso: s, n: d.getDate(), outside: d.getMonth() !== this.view.getMonth(), today: s === today, disabled: (min && s < min) || (max && s > max) };
        });
      },
      shift(n) { this.view = new Date(this.view.getFullYear(), this.view.getMonth() + n, 1); },
      hasValue() { return this.mode === 'range' ? !!this.value?.start : !!this.value; },
      isSelected(s) { return this.mode === 'range' ? s === this.value?.start || s === this.value?.end : s === this.value; },
      inRange(s) { return this.mode === 'range' && this.value?.start && this.value?.end && s > this.value.start && s < this.value.end; },
      pick(s) {
        if (this.mode !== 'range') { this.value = s; this.open = false; return; }
        const { start, end } = this.value ?? {};
        if (!start || end || s < start) this.value = { start: s, end: null };
        else { this.value = { start, end: s }; this.open = false; }
      },
      preset(days) {
        const end = new Date(), start = new Date(); start.setDate(end.getDate() - days + 1);
        this.value = { start: iso(start), end: iso(end) }; this.view = new Date(start.getFullYear(), start.getMonth(), 1); this.open = false;
      },
      display() {
        const f = (s) => parse(s).toLocaleDateString(locale, { day: 'numeric', month: 'short', year: 'numeric' });
        if (!this.hasValue()) return this.placeholder;
        return this.mode === 'range' ? `${f(this.value.start)} – ${this.value.end ? f(this.value.end) : '…'}` : f(this.value);
      },
    }));

    /* ---------- Time picker ---------- */
    Alpine.data('armTimePicker', ({ value, step, min, max, hour12 }) => ({
      value, open: false,
      options() {
        const out = [];
        for (let m = 0; m < 1440; m += step) {
          const t = `${String(Math.floor(m / 60)).padStart(2, '0')}:${String(m % 60).padStart(2, '0')}`;
          if ((!min || t >= min) && (!max || t <= max)) out.push(t);
        }
        return out;
      },
      label(t) {
        if (!hour12) return t;
        const [h, m] = t.split(':').map(Number);
        return `${((h + 11) % 12) + 1}:${String(m).padStart(2, '0')} ${h < 12 ? 'AM' : 'PM'}`;
      },
      toggle() {
        this.open = !this.open;
        // after x-show paints; scrollTop (not scrollIntoView) so the page itself never jumps
        if (this.open) requestAnimationFrame(() => requestAnimationFrame(() => {
          const l = this.$refs.list, s = l.querySelector('[aria-selected="true"]');
          if (s) l.scrollTop = s.offsetTop - (l.clientHeight - s.offsetHeight) / 2;
        }));
      },
    }));

    /* ---------- Event calendar ---------- */
    Alpine.data('armCalendar', ({ events, month, locale }) => ({
      events, view: month ? parse(`${month}-01`) : new Date(), selected: iso(new Date()),
      init() { this.view = new Date(this.view.getFullYear(), this.view.getMonth(), 1); },
      title() { return this.view.toLocaleDateString(locale, { month: 'long', year: 'numeric' }); },
      weekdays() { return [...Array(7)].map((_, i) => new Date(2024, 0, i + 1).toLocaleDateString(locale, { weekday: 'short' })); },
      eventsOn(s) { return this.events.filter((e) => e.date === s).sort((a, b) => (a.time ?? '').localeCompare(b.time ?? '')); },
      days() {
        const first = new Date(this.view.getFullYear(), this.view.getMonth(), 1);
        const start = new Date(first); start.setDate(1 - ((first.getDay() + 6) % 7));
        const today = iso(new Date());
        return [...Array(42)].map((_, i) => {
          const d = new Date(start); d.setDate(start.getDate() + i);
          const s = iso(d);
          return { iso: s, n: d.getDate(), outside: d.getMonth() !== this.view.getMonth(), today: s === today, events: this.eventsOn(s) };
        });
      },
      shift(n) { this.view = new Date(this.view.getFullYear(), this.view.getMonth() + n, 1); },
      today() { const t = new Date(); this.view = new Date(t.getFullYear(), t.getMonth(), 1); this.pick(iso(t)); },
      pick(s) { this.selected = s; this.$dispatch('calendar-select', { date: s }); },
      selectedLabel() { return parse(this.selected).toLocaleDateString(locale, { weekday: 'long', day: 'numeric', month: 'long' }); },
    }));

    /* ---------- Kanban: pointer-driven drag (mouse, pen, touch) ---------- */
    Alpine.data('armKanban', (handler) => ({
      init() { this.$el.addEventListener('pointerdown', (e) => this.start(e)); },
      start(e) {
        const card = e.target.closest('[data-draggable]');
        if (!card || e.button > 0 || e.target.closest('a, button, input, select, textarea')) return;
        const touch = e.pointerType === 'touch';
        let timer = null, drag = null;
        const x0 = e.clientX, y0 = e.clientY;
        const begin = () => {
          const r = card.getBoundingClientRect();
          const ghost = card.cloneNode(true);
          ghost.classList.add('arm-kanban__ghost');
          Object.assign(ghost.style, { width: `${r.width}px`, left: `${r.left}px`, top: `${r.top}px` });
          document.body.appendChild(ghost);
          card.classList.add('is-placeholder');
          drag = { ghost, dx: x0 - r.left, dy: y0 - r.top, from: card.closest('[data-column]').dataset.column };
          navigator.vibrate?.(8);
        };
        const move = (ev) => {
          if (!drag) {
            if (Math.hypot(ev.clientX - x0, ev.clientY - y0) > 6) { if (touch) return end(); begin(); } else return; // touch: moving before the long-press means scrolling
          }
          ev.preventDefault();
          drag.ghost.style.left = `${ev.clientX - drag.dx}px`; drag.ghost.style.top = `${ev.clientY - drag.dy}px`;
          const list = document.elementFromPoint(ev.clientX, ev.clientY)?.closest('.arm-kanban__column')?.querySelector('[data-cards]');
          if (!list || !this.$el.contains(list)) return;
          const after = [...list.querySelectorAll('[data-item]:not(.is-placeholder)')].find((c) => { const b = c.getBoundingClientRect(); return ev.clientY < b.top + b.height / 2; });
          after ? list.insertBefore(card, after) : list.appendChild(card);
          // edge auto-scroll for wide boards
          const box = this.$el.getBoundingClientRect();
          if (ev.clientX > box.right - 40) this.$el.scrollLeft += 12; else if (ev.clientX < box.left + 40) this.$el.scrollLeft -= 12;
        };
        const block = (ev) => { if (drag) ev.preventDefault(); }; // after the long-press the finger drags the card, not the page
        const end = () => {
          clearTimeout(timer);
          removeEventListener('pointermove', move); removeEventListener('pointerup', end); removeEventListener('pointercancel', end);
          removeEventListener('touchmove', block); removeEventListener('contextmenu', block);
          if (!drag) return;
          drag.ghost.remove(); card.classList.remove('is-placeholder');
          const column = card.closest('[data-column]').dataset.column;
          const index = [...card.parentNode.querySelectorAll('[data-item]')].indexOf(card);
          const detail = { item: card.dataset.item, from: drag.from, to: column, index };
          this.$dispatch('kanban-move', detail);
          if (handler && this.$wire) this.$wire[handler](detail.item, detail.to, detail.index);
        };
        if (touch) timer = setTimeout(begin, 250);
        addEventListener('pointermove', move, { passive: false }); addEventListener('pointerup', end); addEventListener('pointercancel', end);
        addEventListener('touchmove', block, { passive: false }); addEventListener('contextmenu', block);
      },
    }));

    /* ---------- Rich text editor (contenteditable + execCommand; sanitise on the server) ---------- */
    Alpine.data('armEditor', ({ value, plainPaste }) => ({
      value, active: [],
      init() {
        this.$refs.content.innerHTML = this.value ?? '';
        this.empty();
        // external changes (Livewire reset) flow back into the editor
        this.$watch('value', (v) => { if (v !== this.$refs.content.innerHTML) { this.$refs.content.innerHTML = v ?? ''; this.empty(); } });
      },
      empty() { this.$refs.content.classList.toggle('is-empty', !this.$refs.content.textContent.trim() && !this.$refs.content.querySelector('img, li')); },
      sync() { this.value = this.$refs.content.innerHTML; this.empty(); this.state(); },
      run(tool) {
        this.$refs.content.focus();
        const block = (tag) => document.execCommand('formatBlock', false, document.queryCommandValue('formatBlock').toLowerCase() === tag ? 'p' : tag);
        ({
          bold: () => document.execCommand('bold'), italic: () => document.execCommand('italic'), underline: () => document.execCommand('underline'),
          strike: () => document.execCommand('strikeThrough'), ul: () => document.execCommand('insertUnorderedList'), ol: () => document.execCommand('insertOrderedList'),
          h2: () => block('h2'), h3: () => block('h3'), quote: () => block('blockquote'), code: () => block('pre'),
          clear: () => { document.execCommand('removeFormat'); document.execCommand('formatBlock', false, 'p'); },
          link: () => {
            const current = window.getSelection()?.anchorNode?.parentElement?.closest('a')?.href ?? 'https://';
            const url = prompt('URL', current);
            if (url === null) return;
            url ? document.execCommand('createLink', false, url) : document.execCommand('unlink');
          },
        })[tool]?.();
        this.sync();
      },
      state() {
        const q = (c) => { try { return document.queryCommandState(c); } catch { return false; } };
        const b = (document.queryCommandValue('formatBlock') || '').toLowerCase();
        this.active = [q('bold') && 'bold', q('italic') && 'italic', q('underline') && 'underline', q('strikeThrough') && 'strike',
          q('insertUnorderedList') && 'ul', q('insertOrderedList') && 'ol', b === 'h2' && 'h2', b === 'h3' && 'h3', b === 'blockquote' && 'quote', b === 'pre' && 'code'].filter(Boolean);
      },
      paste(e) {
        if (!plainPaste) return;
        e.preventDefault();
        document.execCommand('insertText', false, e.clipboardData.getData('text/plain'));
      },
    }));

    /* ---------- Infinite scroll ---------- */
    Alpine.data('armInfinite', (action, margin) => ({
      loading: false, observed: 'IntersectionObserver' in window,
      init() {
        if (!this.observed) return;
        this.io = new IntersectionObserver((entries) => entries[0].isIntersecting && this.load(), { rootMargin: margin });
        this.io.observe(this.$el);
      },
      async load() {
        if (this.loading || !this.$el.hasAttribute('data-more')) return;
        this.loading = true;
        try {
          if (action && this.$wire) await this.$wire[action]();
          else { this.$dispatch('load-more'); await new Promise((r) => setTimeout(r, 300)); }
        } finally {
          this.loading = false;
          // still visible after the new items arrived (short pages): keep going
          if (this.observed) { this.io.unobserve(this.$el); this.io.observe(this.$el); }
        }
      },
      destroy() { this.io?.disconnect(); },
    }));

    /* ---------- OTP ---------- */
    Alpine.data('armOtp', (length) => ({
      value: '',
      cells() { return [...this.$el.querySelectorAll('.arm-otp__cell')]; },
      sync() { this.value = this.cells().map((c) => c.value).join(''); if (this.value.length === length) this.$dispatch('complete', this.value); },
      input(e, i) {
        e.target.value = e.target.value.replace(/\D/g, '').slice(-1);
        if (e.target.value) this.cells()[i + 1]?.focus();
        this.sync();
      },
      back(e, i) { if (!e.target.value) { const p = this.cells()[i - 1]; if (p) { p.value = ''; p.focus(); } } this.$nextTick(() => this.sync()); },
      paste(e) {
        const digits = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, length).split('');
        this.cells().forEach((c, i) => { c.value = digits[i] ?? ''; });
        this.cells()[Math.min(digits.length, length - 1)]?.focus();
        this.sync();
      },
    }));

    /* ---------- File upload preview ---------- */
    Alpine.data('armFiles', () => ({
      files: [], dragging: false,
      read(list) {
        const size = (b) => b < 1024 ? `${b} B` : b < 1048576 ? `${(b / 1024).toFixed(1)} KB` : `${(b / 1048576).toFixed(1)} MB`;
        this.files.forEach((f) => f.url && URL.revokeObjectURL(f.url));
        this.files = [...list].map((f) => ({ name: f.name, size: size(f.size), url: f.type.startsWith('image/') ? URL.createObjectURL(f) : null }));
      },
    }));

    /* ---------- Carousel ---------- */
    Alpine.data('armCarousel', (autoplay = 0) => ({
      index: 0, count: 0, timer: null,
      init() {
        this.count = this.$refs.track.children.length;
        if (autoplay) {
          const start = () => { this.timer = setInterval(() => this.go((this.index + 1) % this.count), autoplay); };
          start();
          this.$el.addEventListener('pointerenter', () => clearInterval(this.timer));
          this.$el.addEventListener('pointerleave', start);
        }
      },
      sync() {
        const t = this.$refs.track, w = t.children[0]?.getBoundingClientRect().width || t.clientWidth;
        this.index = Math.round(t.scrollLeft / (w + parseFloat(getComputedStyle(t).columnGap || 0)));
      },
      go(i) {
        const t = this.$refs.track, slide = t.children[Math.max(0, Math.min(this.count - 1, i))];
        if (slide) t.scrollTo({ left: slide.offsetLeft - t.offsetLeft, behavior: 'smooth' });
        this.index = Math.max(0, Math.min(this.count - 1, i));
      },
    }));

    /* ---------- Toasts ---------- */
    Alpine.data('armToaster', (flash = null) => ({
      toasts: [], icons: {}, seq: 0,
      init() {
        this.$refs.icons.content.querySelectorAll('span').forEach((s) => { this.icons[Object.keys(s.dataset)[0]] = s.innerHTML; });
        window.addEventListener('toast', (e) => this.add(Array.isArray(e.detail) ? e.detail[0] : e.detail));
        if (flash) this.add(typeof flash === 'string' ? { title: flash, tone: 'success' } : flash);
      },
      add({ title = '', text = '', tone = 'info', variant, duration = 5000, action = null } = {}) {
        const t = { id: ++this.seq, title, text, tone: variant ?? tone, duration, action, paused: false, left: duration, since: Date.now() };
        this.toasts.push(t);
        if (this.toasts.length > 5) this.toasts.shift();
        if (duration) t.timer = setTimeout(() => this.dismiss(t.id), duration);
      },
      pause(t) { if (!t.duration) return; clearTimeout(t.timer); t.left -= Date.now() - t.since; t.paused = true; },
      resume(t) { if (!t.duration) return; t.since = Date.now(); t.paused = false; t.timer = setTimeout(() => this.dismiss(t.id), Math.max(t.left, 800)); },
      dismiss(id) { this.toasts = this.toasts.filter((t) => t.id !== id); },
    }));

    /* ---------- Command palette ---------- */
    Alpine.data('armCommand', (items, name, hotkey) => ({
      items, query: '', active: 0,
      init() {
        const el = this.$el;
        if (hotkey) addEventListener('keydown', (e) => { if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') { e.preventDefault(); el.open ? el.close() : this.show(); } });
        window.addEventListener('modal-show', (e) => (e.detail?.name ?? e.detail?.[0]?.name) === name && this.show());
        el.addEventListener('close', () => { this.query = ''; lockScroll(false); });
        this.$watch('query', () => { this.active = 0; });
      },
      show() { this.$el.showModal(); lockScroll(true); this.$nextTick(() => this.$refs.input.focus()); },
      get filtered() {
        const q = this.query.trim().toLocaleLowerCase();
        return q ? this.items.filter((i) => `${i.label} ${i.group ?? ''} ${i.keywords ?? ''}`.toLocaleLowerCase().includes(q)) : this.items;
      },
      move(d) { const n = this.filtered.length; if (n) this.active = (this.active + d + n) % n; this.$nextTick(() => this.$el.querySelector('[data-active]')?.scrollIntoView({ block: 'nearest' })); },
      run(item) {
        if (!item) return;
        this.$el.close();
        if (item.event) window.dispatchEvent(new CustomEvent(item.event, { detail: item }));
        else if (item.href) window.location.href = item.href;
      },
    }));
  };

  if (window.Alpine) register(window.Alpine);
  else document.addEventListener('alpine:init', () => register(window.Alpine));
})();
