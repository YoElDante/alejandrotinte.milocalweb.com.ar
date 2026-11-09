/**
 * Mobile hamburger menu — toggle, link close, overlay close, outside click, Esc
 */
(function () {
  'use strict';
  var navbar = document.querySelector('.navbar');
  var toggle = document.querySelector('.navbar-toggle');
  var overlay = document.querySelector('.navbar-overlay');
  if (!toggle || !navbar) return;

  function closeMenu() {
    navbar.classList.remove('open');
    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('aria-label', 'Abrir menú');
  }

  function isOpen() {
    return navbar.classList.contains('open');
  }

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

  document.addEventListener('keydown', function (event) {
    if (event.key !== 'Escape' || !isOpen()) return;
    closeMenu();
    toggle.focus();
  });
})();
