/**
 * Menú hamburguesa mobile.
 *
 * Abre/cierra el panel lateral de navegación y mantiene sincronizados los
 * atributos ARIA. Se cierra al tocar un link, al tocar el overlay, al hacer
 * click fuera del navbar y con Esc (devolviendo el foco al botón).
 */
(function () {
  'use strict';
  var navbar = document.querySelector('.navbar');
  var toggle = document.querySelector('.navbar-toggle');
  var overlay = document.querySelector('.navbar-overlay');
  if (!toggle || !navbar) return;

  /**
   * Cierra el menú y restaura el estado accesible del botón.
   */
  function closeMenu() {
    navbar.classList.remove('open');
    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('aria-label', 'Abrir menú');
  }

  /**
   * Indica si el menú está desplegado.
   *
   * @returns {boolean}
   */
  function isOpen() {
    return navbar.classList.contains('open');
  }

  // El estado visual y el ARIA se actualizan juntos para no desincronizarse.
  toggle.addEventListener('click', function () {
    var open = navbar.classList.toggle('open');
    toggle.setAttribute('aria-expanded', open);
    toggle.setAttribute('aria-label', open ? 'Cerrar menú' : 'Abrir menú');
  });

  navbar.querySelectorAll('.navbar-menu a').forEach(function (link) {
    link.addEventListener('click', closeMenu);
  });

  if (overlay) {
    overlay.addEventListener('click', closeMenu);
  }

  /* Click fuera del menú: el overlay no siempre cubre toda la pantalla
     (el header lo acota), así que se escucha a nivel documento. */
  document.addEventListener('click', function (event) {
    if (!isOpen()) return;
    if (navbar.contains(event.target)) return;
    closeMenu();
  });

  // Esc cierra y devuelve el foco al botón: sin esto el foco queda perdido
  // dentro del panel que acaba de ocultarse.
  document.addEventListener('keydown', function (event) {
    if (event.key !== 'Escape' || !isOpen()) return;
    closeMenu();
    toggle.focus();
  });
})();
