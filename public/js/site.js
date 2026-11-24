// Turath Cars - progressive enhancements. No inline scripts or styles (strict CSP).
(function () {
  'use strict';
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };
  var rtl = document.documentElement.dir === 'rtl';
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Re-enable buttons when coming back via the browser's back button
  window.addEventListener('pageshow', function (e) { if (e.persisted) { $$('button[disabled]').forEach(function (b) { b.disabled = false; b.classList.remove('opacity-60', 'cursor-wait'); }); } });

  // Flash messages fade out by themselves (errors stay)
  $$('[data-flash][role=status]').forEach(function (el) {
    setTimeout(function () { el.remove(); }, 6000);
  });

  // Confirm destructive actions + stop double submits
  document.addEventListener('submit', function (e) {
    var f = e.target, msg = f.getAttribute('data-confirm');
    if (msg && !window.confirm(msg)) { e.preventDefault(); return; }
    var b = f.querySelector('button[type=submit]:not([data-allow-resubmit]), button:not([type])');
    if (b && !f.hasAttribute('data-allow-resubmit')) { setTimeout(function () { b.disabled = true; b.classList.add('opacity-60', 'cursor-wait'); }, 0); }
  });
  $$('[data-autosubmit]').forEach(function (el) { el.addEventListener('change', function () { el.form.submit(); }); });

  // Sticky header + back to top
  var sticky = $('#stickyHeader'), top = $('[data-to-top]');
  function onScroll() {
    var y = window.scrollY || 0;
    if (sticky) { sticky.classList.toggle('-translate-y-full', y < 240); sticky.setAttribute('aria-hidden', y < 240 ? 'true' : 'false'); }
    if (top) { top.classList.toggle('hidden', y < 500); top.classList.toggle('flex', y >= 500); if (y >= 500) top.classList.add('dock-pop'); }
  }
  window.addEventListener('scroll', onScroll, { passive: true }); onScroll();
  if (top) top.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'auto' }); });

  // Mobile drawer (site + admin)
  var drawer = $('[data-drawer]'), overlay = $('[data-drawer-overlay]');
  function setDrawer(open) {
    if (!drawer) return;
    var fromStart = drawer.hasAttribute('data-from-start');
    (fromStart ? ['-translate-x-full', 'rtl:translate-x-full'] : ['translate-x-full', 'rtl:-translate-x-full']).forEach(function (c) { drawer.classList.toggle(c, !open); });
    drawer.classList.toggle('invisible', !open);
    if (overlay) { overlay.classList.toggle('opacity-0', !open); overlay.classList.toggle('pointer-events-none', !open); }
    document.body.classList.toggle('overflow-hidden', open);
    drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
  }
  $$('[data-menu-toggle]').forEach(function (b) { b.addEventListener('click', function () { setDrawer(true); }); });
  $$('[data-drawer-close]').forEach(function (b) { b.addEventListener('click', function () { setDrawer(false); }); });
  if (overlay) overlay.addEventListener('click', function () { setDrawer(false); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setDrawer(false); });

  // Shop filter panel: opens from the side, hides again (no animation)
  var fPanel = $('[data-fdrawer]'), fOverlay = $('[data-fdrawer-overlay]');
  function setFilters(open) {
    if (!fPanel) return;
    fPanel.classList.toggle('hidden', !open);
    if (fOverlay) fOverlay.classList.toggle('hidden', !open);
    fPanel.setAttribute('aria-hidden', open ? 'false' : 'true');
    document.body.classList.toggle('overflow-hidden', open);
    if (open) { var first = $('input, select', fPanel); if (first) first.focus(); }
  }
  $$('[data-fdrawer-open]').forEach(function (b) { b.addEventListener('click', function () { setFilters(true); }); });
  $$('[data-fdrawer-close]').forEach(function (b) { b.addEventListener('click', function () { setFilters(false); }); });
  if (fOverlay) fOverlay.addEventListener('click', function () { setFilters(false); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setFilters(false); });

  // Numbers are shown as they are (no counting animation)
  $$('[data-count]').forEach(function (el) { el.textContent = (parseInt(el.getAttribute('data-count'), 10) || 0).toLocaleString('en-US'); });

  // Horizontal rows with arrow buttons (icons only)
  $$('[data-snap-prev],[data-snap-next]').forEach(function (b) {
    b.addEventListener('click', function () {
      var forward = b.hasAttribute('data-snap-next');
      var row = document.getElementById(b.getAttribute('data-snap-prev') || b.getAttribute('data-snap-next'));
      if (!row) return;
      var dir = (forward ? 1 : -1) * (rtl ? -1 : 1);
      row.scrollBy({ left: dir * Math.max(300, row.clientWidth * .8), behavior: 'auto' });
    });
  });

  // Car photo gallery (arrows, dots, thumbnails, keyboard, swipe via native scroll-snap)
  $$('[data-gallery]').forEach(function (g) {
    var track = $('[data-gal-track]', g), slides = track ? track.children : [];
    var dots = $$('[data-gal-dot]', g), thumbs = $$('[data-gal-thumb]', g);
    if (!track || slides.length < 2) return;
    var idx = 0;
    function mark(i) {
      dots.concat(thumbs).forEach(function (n) { n.setAttribute('aria-current', 'false'); });
      if (dots[i]) dots[i].setAttribute('aria-current', 'true');
      if (thumbs[i]) thumbs[i].setAttribute('aria-current', 'true');
    }
    function go(i) {
      idx = (i + slides.length) % slides.length;
      track.scrollTo({ left: (rtl ? -1 : 1) * idx * track.clientWidth, behavior: 'auto' });
      mark(idx);
    }
    var prev = $('[data-gal-prev]', g), next = $('[data-gal-next]', g);
    if (prev) prev.addEventListener('click', function () { go(idx - 1); });
    if (next) next.addEventListener('click', function () { go(idx + 1); });
    dots.forEach(function (d, i) { d.addEventListener('click', function () { go(i); }); });
    thumbs.forEach(function (t, i) { t.addEventListener('click', function () { go(i); }); });
    track.addEventListener('scroll', function () {
      var i = Math.round(Math.abs(track.scrollLeft) / track.clientWidth);
      if (i !== idx) { idx = i; mark(idx); }
    }, { passive: true });
    g.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowLeft') go(idx + (rtl ? 1 : -1));
      if (e.key === 'ArrowRight') go(idx + (rtl ? -1 : 1));
    });
    mark(0);
  });

  // Search forms: body-type tabs, dependent traffic units, live result counter
  $$('[data-search]').forEach(function (form) {
    var out = $('[data-count-out]', form), tabInput = $('[name=body_type]', form), timer;
    var gov = $('[name=governorate]', form), unit = $('[name=traffic_unit]', form);

    function refreshCount() {
      if (!out || !form.getAttribute('data-count-url')) return;
      clearTimeout(timer);
      timer = setTimeout(function () {
        var params = new URLSearchParams(new FormData(form));
        Array.from(params.keys()).forEach(function (k) { if (!params.get(k)) params.delete(k); });
        params.delete('sort');
        fetch(form.getAttribute('data-count-url') + '?' + params.toString(), { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' })
          .then(function (r) { return r.ok ? r.json() : null; })
          .then(function (j) { if (j && typeof j.count === 'number') { out.textContent = j.count; out.classList.remove('count-pop'); void out.offsetWidth; out.classList.add('count-pop'); } })
          .catch(function () {});
      }, 250);
    }

    function filterUnits() {
      if (!gov || !unit) return;
      var g = gov.value, keep = false;
      $$('option[data-gov]', unit).forEach(function (o) {
        var show = !g || o.getAttribute('data-gov') === g;
        o.hidden = !show; o.disabled = !show;
        if (show && o.value === unit.value) keep = true;
      });
      if (!keep) unit.value = '';
    }

    $$('[data-tab]', form).forEach(function (btn) {
      btn.addEventListener('click', function () {
        $$('[data-tab]', form).forEach(function (b) { b.classList.remove('is-on'); b.setAttribute('aria-selected', 'false'); });
        btn.classList.add('is-on'); btn.setAttribute('aria-selected', 'true');
        if (tabInput) tabInput.value = btn.getAttribute('data-tab');
        refreshCount();
      });
    });
    if (gov) gov.addEventListener('change', function () { filterUnits(); refreshCount(); });
    form.addEventListener('input', refreshCount);
    form.addEventListener('change', refreshCount);
    filterUnits();
  });

  // Colour swatches update their label
  $$('[data-swatches]').forEach(function (wrap) {
    var label = $('[data-swatch-label]', wrap);
    $$('input[type=radio]', wrap).forEach(function (r) {
      r.addEventListener('change', function () { if (label) label.textContent = r.getAttribute('data-name') || ''; });
    });
  });

  // Dependent traffic unit select inside the admin car form
  var carGov = $('[data-form-gov]'), carUnit = $('[data-form-unit]');
  if (carGov && carUnit) {
    var sync = function () {
      var g = carGov.value, keep = false;
      $$('option[data-gov]', carUnit).forEach(function (o) {
        var show = o.getAttribute('data-gov') === g; o.hidden = !show; o.disabled = !show;
        if (show && o.value === carUnit.value) keep = true;
      });
      if (!keep) carUnit.value = '';
    };
    carGov.addEventListener('change', sync); sync();
  }

  // Legend dots (colour comes from data attribute; CSSOM is allowed by the CSP)
  $$('[data-bg]').forEach(function (el) { el.style.backgroundColor = el.getAttribute('data-bg'); });

  // Set bar widths from data attributes (CSP-safe CSSOM, no inline style attributes)
  $$('[data-w]').forEach(function (el) { el.style.width = el.getAttribute('data-w') + '%'; });

  // Hero background: the clips play one after another (loop of all clips)
  var hv = $('[data-hv]');
  if (hv) {
    var hvVideo = $('[data-hv-video]', hv);
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var hvPlay = function () { try { var pr = hvVideo.play(); if (pr && pr.catch) pr.catch(function () {}); } catch (e) {} };
    var saveData = navigator.connection && navigator.connection.saveData;
    if (hvVideo && !reduce && !saveData) hvPlay();
    document.addEventListener('visibilitychange', function () {
      if (!hvVideo || reduce || saveData) return;
      if (document.hidden) hvVideo.pause(); else hvPlay();
    });
  }

  // Search suggestions (brands, models, governorates, traffic units); text only, no innerHTML
  $$('[data-suggest]').forEach(function (box) {
    var input = $('input', box), list = $('[data-suggest-list]', box), url = box.getAttribute('data-url');
    var labels = JSON.parse(document.documentElement.getAttribute('data-suggest-labels') || '{}');
    var timer = null, idx = -1, items = [], seq = 0;
    var close = function () { list.classList.add('hidden'); input.setAttribute('aria-expanded', 'false'); idx = -1; };
    var mark = function () { $$('li', list).forEach(function (li, k) { li.setAttribute('aria-selected', k === idx ? 'true' : 'false'); }); };
    var render = function () {
      list.textContent = '';
      if (!items.length) { close(); return; }
      items.forEach(function (it) {
        var li = document.createElement('li'); li.setAttribute('role', 'option');
        var t = document.createElement('span'); t.textContent = it.label;
        var k = document.createElement('small'); k.textContent = labels[it.type] || it.type;
        li.appendChild(t); li.appendChild(k);
        li.addEventListener('mousedown', function (e) { e.preventDefault(); window.location.href = it.url; });
        list.appendChild(li);
      });
      list.classList.remove('hidden'); input.setAttribute('aria-expanded', 'true');
    };
    input.addEventListener('input', function () {
      clearTimeout(timer);
      var q = input.value.trim();
      if (q.length < 2) { items = []; close(); return; }
      timer = setTimeout(function () {
        var my = ++seq;
        fetch(url + '?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' })
          .then(function (r) { return r.ok ? r.json() : { items: [] }; })
          .then(function (d) { if (my !== seq) return; items = d.items || []; idx = -1; render(); })
          .catch(function () { close(); });
      }, 180);
    });
    input.addEventListener('keydown', function (e) {
      if (list.classList.contains('hidden')) return;
      if (e.key === 'ArrowDown') { e.preventDefault(); idx = (idx + 1) % items.length; mark(); }
      else if (e.key === 'ArrowUp') { e.preventDefault(); idx = (idx - 1 + items.length) % items.length; mark(); }
      else if (e.key === 'Enter' && idx >= 0) { e.preventDefault(); window.location.href = items[idx].url; }
      else if (e.key === 'Escape') { close(); }
    });
    input.addEventListener('blur', function () { setTimeout(close, 120); });
  });
})();
