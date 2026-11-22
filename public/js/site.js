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
    setTimeout(function () { el.style.transition = 'opacity .4s'; el.style.opacity = '0'; setTimeout(function () { el.remove(); }, 400); }, 6000);
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
  if (top) top.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });

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

  // Reveal on scroll + count-up numbers
  var countUp = function (el) {
    var to = parseInt(el.getAttribute('data-count'), 10) || 0, t0 = null;
    if (reduce) { el.textContent = to.toLocaleString('en-US'); return; }
    var step = function (t) { t0 = t0 || t; var p = Math.min((t - t0) / 1400, 1); el.textContent = Math.round(to * (1 - Math.pow(1 - p, 3))).toLocaleString('en-US'); if (p < 1) requestAnimationFrame(step); };
    requestAnimationFrame(step);
  };
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (!e.isIntersecting) return;
        e.target.classList.add('is-visible');
        $$('[data-count]', e.target).forEach(countUp);
        if (e.target.hasAttribute('data-count')) countUp(e.target);
        io.unobserve(e.target);
      });
    }, { threshold: .12 });
    $$('.reveal, .reveal-l, .reveal-r, [data-count]').forEach(function (el) { io.observe(el); });
  } else {
    $$('.reveal, .reveal-l, .reveal-r').forEach(function (el) { el.classList.add('is-visible'); });
    $$('[data-count]').forEach(countUp);
  }

  // Horizontal rows with arrow buttons (icons only)
  $$('[data-snap-prev],[data-snap-next]').forEach(function (b) {
    b.addEventListener('click', function () {
      var forward = b.hasAttribute('data-snap-next');
      var row = document.getElementById(b.getAttribute('data-snap-prev') || b.getAttribute('data-snap-next'));
      if (!row) return;
      var dir = (forward ? 1 : -1) * (rtl ? -1 : 1);
      row.scrollBy({ left: dir * Math.max(300, row.clientWidth * .8), behavior: 'smooth' });
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
      track.scrollTo({ left: (rtl ? -1 : 1) * idx * track.clientWidth, behavior: reduce ? 'auto' : 'smooth' });
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

  // Hero video slider: each clip enters from a different side
  var hv = $('[data-hv]');
  if (hv) {
    var slides = $$('[data-hv-slide]', hv), dots = $$('[data-hv-dot]', hv), cur = 0, timer = null, DUR = 7000;
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    hv.style.setProperty('--hv-dur', (DUR / 1000) + 's');
    var playOnly = function (i) {
      slides.forEach(function (sl, k) {
        var v = sl.querySelector('video'); if (!v) return;
        if (k === i && !reduce) { try { v.currentTime = 0; var pr = v.play(); if (pr && pr.catch) pr.catch(function () {}); } catch (e) {} }
        else if (k !== i) { setTimeout(function () { try { v.pause(); } catch (e) {} }, 950); }
      });
    };
    var go = function (n) {
      n = (n + slides.length) % slides.length;
      if (n === cur) return;
      var old = slides[cur], nu = slides[n];
      old.classList.remove('is-active', 'is-entering'); old.classList.add('is-out');
      setTimeout(function () { old.classList.remove('is-out'); old.setAttribute('aria-hidden', 'true'); }, 950);
      nu.classList.remove('is-out'); nu.classList.add('is-active', 'is-entering'); nu.setAttribute('aria-hidden', 'false');
      dots.forEach(function (d, k) { d.classList.remove('is-on'); void d.offsetWidth; if (k === n) d.classList.add('is-on'); });
      cur = n; playOnly(n); schedule();
    };
    var schedule = function () { clearTimeout(timer); if (!reduce) timer = setTimeout(function () { go(cur + 1); }, DUR); };
    var isRtl = document.documentElement.dir === 'rtl';
    $('[data-hv-prev]', hv).addEventListener('click', function () { go(cur + (isRtl ? 1 : -1)); });
    $('[data-hv-next]', hv).addEventListener('click', function () { go(cur + (isRtl ? -1 : 1)); });
    dots.forEach(function (d, k) { d.addEventListener('click', function () { go(k); }); });
    document.addEventListener('visibilitychange', function () {
      var v = slides[cur].querySelector('video');
      if (document.hidden) { clearTimeout(timer); if (v) v.pause(); } else { if (v && !reduce) v.play().catch(function () {}); schedule(); }
    });
    slides[0].classList.add('is-entering'); playOnly(0); schedule();
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
