/**
 * Scroll suave para links internos (#ancla).
 *
 * Compensa la altura del header fijo para que la sección no quede tapada.
 * Si el href no apunta a un nodo existente, deja el comportamiento nativo.
 */
(function () {
  'use strict';
  document.querySelectorAll('a[href^="#"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
      var targetId = this.getAttribute('href');

      // "#" pelado: convención de "volver arriba".
      if (targetId === '#') {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
        return;
      }

      var target = document.querySelector(targetId);
      if (target) {
        e.preventDefault();
        var header = document.querySelector('.site-header');
        var offset = header ? header.offsetHeight : 0;

        // 16px extra de aire entre el borde del header y el título.
        var position = target.getBoundingClientRect().top + window.scrollY - offset - 16;
        window.scrollTo({ top: position, behavior: 'smooth' });
      }
    });
  });
})();
