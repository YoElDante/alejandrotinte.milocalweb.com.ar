/**
 * Mobile hamburger menu — toggle, link close, overlay close
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
})();
