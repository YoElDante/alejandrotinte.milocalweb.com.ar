/**
 * Lightbox de portadas — abre la imagen completa en un <dialog>.
 *
 * Progressive enhancement: los links a la portada grande funcionan sin JS.
 * Solo se intercepta el click si el navegador soporta dialog.showModal().
 */
(function () {
  'use strict';

  var dialog = document.getElementById('lightbox');
  if (!dialog || typeof dialog.showModal !== 'function') return;

  var img = dialog.querySelector('.lightbox-img');
  var closeBtn = dialog.querySelector('.lightbox-close');
  if (!img || !closeBtn) return;

  function cerrar() {
    dialog.close();
    img.removeAttribute('src');
    img.alt = '';
  }

  document.querySelectorAll('[data-lightbox]').forEach(function (link) {
    link.addEventListener('click', function (event) {
      event.preventDefault();

      var fullSrc = link.getAttribute('href');
      var thumb = link.querySelector('img');

      img.alt = link.getAttribute('data-lightbox') || '';
      img.loading = 'eager';

      // Mostramos la miniatura al instante y cambiamos a la imagen completa
      // cuando termina de cargar (evita un overlay vacío en conexiones lentas).
      img.src = thumb ? (thumb.currentSrc || thumb.src) : fullSrc;
      dialog.showModal();

      var preload = new Image();
      preload.onload = function () {
        if (dialog.open) {
          img.src = fullSrc;
        }
      };
      preload.src = fullSrc;
    });
  });

  closeBtn.addEventListener('click', cerrar);

  // Click en el fondo (fuera de la imagen) cierra el lightbox.
  dialog.addEventListener('click', function (event) {
    if (event.target === dialog) {
      cerrar();
    }
  });

  // Cierre con Esc: el <dialog> lo maneja solo, limpiamos la imagen al cerrar.
  dialog.addEventListener('close', function () {
    img.removeAttribute('src');
    img.alt = '';
  });
})();
