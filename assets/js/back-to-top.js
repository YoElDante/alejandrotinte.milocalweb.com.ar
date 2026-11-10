/**
 * Botón "volver arriba".
 *
 * Aparece después de 600px de scroll (aproximadamente una pantalla de hero)
 * y sube con animación suave. El botón se oculta por CSS con .visible.
 */
(function () {
  'use strict';
  var btn = document.querySelector('.back-to-top');
  if (!btn) return;

  // Umbral fijo: antes de 600px el usuario todavía está en la zona del hero.
  window.addEventListener('scroll', function () {
    if (window.scrollY > 600) {
      btn.classList.add('visible');
    } else {
      btn.classList.remove('visible');
    }
  });

  btn.addEventListener('click', function () {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
})();
