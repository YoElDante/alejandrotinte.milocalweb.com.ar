/**
 * Cierre de la grilla de libros.
 *
 * La imagen decorativa no es una card. Si la última fila de la grilla queda
 * incompleta, ocupa el primer hueco libre; si está completa, baja y ocupa
 * toda la fila como divisor antes de la antología.
 */
(function () {
  'use strict';

  var grid = document.querySelector('.books-grid');
  var decor = grid && grid.querySelector('.books-decor');
  if (!grid || !decor) return;

  var ticking = false;

  /**
   * Cuenta cuántas cards entran en la primera fila comparando su posición
   * vertical. Evita depender de un breakpoint fijo en JS.
   *
   * @returns {number} Columnas efectivas de la grilla (mínimo 1).
   */
  function columnas() {
    var cards = grid.querySelectorAll('.book-card');
    if (!cards.length) return 1;

    var primeraFila = Math.round(cards[0].getBoundingClientRect().top);
    var total = 0;

    for (var i = 0; i < cards.length; i++) {
      if (Math.round(cards[i].getBoundingClientRect().top) !== primeraFila) break;
      total++;
    }

    return total || 1;
  }

  /**
   * Decide si la imagen de cierre ocupa el hueco libre de la última fila
   * (clase --gap) o si baja como divisor de ancho completo.
   */
  function update() {
    ticking = false;

    var cards = grid.querySelectorAll('.book-card').length;
    var cols = columnas();
    var huecoLibre = cards % cols !== 0;

    decor.classList.toggle('books-decor--gap', huecoLibre);
  }

  /**
   * Agenda un recálculo en el próximo frame, sin duplicar tareas pendientes.
   */
  function schedule() {
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(update);
  }

  window.addEventListener('resize', schedule, { passive: true });
  update();
})();
