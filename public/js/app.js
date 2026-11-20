// Turath Cars - small progressive enhancements (no inline scripts, CSP-safe)
(function () {
  'use strict';

  // Mobile navigation
  var toggle = document.querySelector('[data-nav-toggle]');
  var links = document.querySelector('[data-nav-links]');
  if (toggle && links) {
    toggle.addEventListener('click', function () {
      var open = links.classList.toggle('open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  // Confirm destructive actions
  document.addEventListener('submit', function (event) {
    var message = event.target.getAttribute('data-confirm');
    if (message && !window.confirm(message)) {
      event.preventDefault();
    }
  });

  // Auto-submit selects (e.g. sort order)
  document.querySelectorAll('[data-autosubmit]').forEach(function (el) {
    el.addEventListener('change', function () { el.form.submit(); });
  });

  // Photo slider
  document.querySelectorAll('[data-slider]').forEach(function (root) {
    var track = root.querySelector('[data-slider-track]');
    var slides = track ? track.children : [];
    var dots = root.querySelectorAll('[data-slider-dot]');
    var thumbs = root.querySelectorAll('[data-slider-thumb]');
    var prev = root.querySelector('[data-slider-prev]');
    var next = root.querySelector('[data-slider-next]');
    if (!track || slides.length < 2) return;

    var index = 0;

    function mark(nodes, active) {
      nodes.forEach(function (node, i) {
        node.setAttribute('aria-current', i === active ? 'true' : 'false');
      });
    }

    function go(i) {
      index = (i + slides.length) % slides.length;
      track.scrollTo({ left: slides[index].offsetLeft - track.offsetLeft, behavior: 'smooth' });
      mark(dots, index);
      mark(thumbs, index);
    }

    if (prev) prev.addEventListener('click', function () { go(index - 1); });
    if (next) next.addEventListener('click', function () { go(index + 1); });
    dots.forEach(function (d, i) { d.addEventListener('click', function () { go(i); }); });
    thumbs.forEach(function (t, i) { t.addEventListener('click', function () { go(i); }); });

    track.addEventListener('scroll', function () {
      var i = Math.round(track.scrollLeft / track.clientWidth);
      if (i !== index) { index = i; mark(dots, index); mark(thumbs, index); }
    }, { passive: true });

    root.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowLeft') go(index - 1);
      if (e.key === 'ArrowRight') go(index + 1);
    });

    mark(dots, 0);
    mark(thumbs, 0);
  });
})();
