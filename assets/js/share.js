/**
 * Compartir en la ficha de libro (/libros/{slug}).
 *
 * Un único botón "Compartir" compacto que despliega un menú pequeño con las
 * opciones (WhatsApp y Copiar link). El menú se cierra al hacer clic fuera o
 * al pulsar Escape. "Copiar link" usa la Clipboard API cuando está disponible
 * y cae a un <textarea> con execCommand como respaldo.
 *
 * @package MiLocalWeb\Clientes
 */
(function () {
  'use strict';

  var contenedor = document.querySelector('.book-share');
  if (!contenedor) {
    return;
  }

  var toggle = contenedor.querySelector('.js-share-toggle');
  var menu = contenedor.querySelector('.book-share-menu');

  function abrirMenu() {
    menu.hidden = false;
    toggle.setAttribute('aria-expanded', 'true');
  }

  function cerrarMenu() {
    menu.hidden = true;
    toggle.setAttribute('aria-expanded', 'false');
  }

  if (toggle && menu) {
    toggle.addEventListener('click', function () {
      if (menu.hidden) {
        abrirMenu();
      } else {
        cerrarMenu();
      }
    });

    document.addEventListener('click', function (event) {
      if (!contenedor.contains(event.target)) {
        cerrarMenu();
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        cerrarMenu();
      }
    });
  }

  // Copiar link: copia al portapapeles y muestra confirmación en el ítem.
  var botonCopiar = contenedor.querySelector('.js-copy-link');
  if (botonCopiar) {
    botonCopiar.addEventListener('click', function () {
      var enlace = botonCopiar.getAttribute('data-url') || window.location.href;
      var etiqueta = botonCopiar.querySelector('.js-copy-label');

      function copiarFallback(texto) {
        var area = document.createElement('textarea');
        area.value = texto;
        area.setAttribute('readonly', '');
        area.style.position = 'fixed';
        area.style.left = '-9999px';
        document.body.appendChild(area);
        area.select();
        try {
          document.execCommand('copy');
        } catch (e) {
          /* sin acción */
        }
        document.body.removeChild(area);
      }

      function listo() {
        var original = etiqueta ? etiqueta.textContent : botonCopiar.textContent;

        if (etiqueta) {
          etiqueta.textContent = '¡Link copiado!';
        } else {
          botonCopiar.textContent = '¡Link copiado!';
        }
        botonCopiar.classList.add('is-copied');

        window.setTimeout(function () {
          if (etiqueta) {
            etiqueta.textContent = original;
          } else {
            botonCopiar.textContent = original;
          }
          botonCopiar.classList.remove('is-copied');
        }, 2000);
      }

      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(enlace).then(listo, function () {
          copiarFallback(enlace);
          listo();
        });
      } else {
        copiarFallback(enlace);
        listo();
      }
    });
  }
})();
