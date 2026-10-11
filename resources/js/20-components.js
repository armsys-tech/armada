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
