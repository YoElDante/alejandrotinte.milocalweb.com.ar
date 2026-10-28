/**
 * Parallax sutil de las imágenes flotantes decorativas.
 *
 * Funciona con `.featured-float` (obra destacada) y `.book-page-float`
 * (fichas de libro). Se desplazan apenas unos píxeles según el scroll, sin
 * animación constante. Respeta prefers-reduced-motion.
 */
(function () {
  'use strict';

  var els = document.querySelectorAll('.featured-float, .book-page-float');
  if (!els.length) return;

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  var ticking = false;

  function update() {
    ticking = false;

    var vh = window.innerHeight || document.documentElement.clientHeight;

    els.forEach(function (el) {
      var rect = el.getBoundingClientRect();

      // -1 cuando el elemento entra por abajo, 1 cuando sale por arriba.
      var progress = ((vh - rect.top) / (vh + rect.height)) * 2 - 1;
      var offset = Math.max(-24, Math.min(24, progress * -24));

      el.style.setProperty('--float-y', offset.toFixed(2) + 'px');
    });
  }

  window.addEventListener('scroll', function () {
    if (!ticking) {
      ticking = true;
      window.requestAnimationFrame(update);
    }
  }, { passive: true });

  update();
})();
