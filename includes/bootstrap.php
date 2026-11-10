<?php
/**
 * Bootstrap compartido por las páginas del sitio.
 *
 * Carga config + helpers y resuelve las rutas de assets, versiones de CSS/JS
 * y el listado de archivos estáticos. Lo usan `index.php` y `libro.php`.
 *
 * @package MiLocalWeb\Clientes
 */

require_once __DIR__ . '/helpers.php';

$cliente = require __DIR__ . '/../config.php';

// ─── Rutas a assets ──────────────────────────────────────────────
// En local (php -S o localhost) se usan rutas relativas; en producción se
// arma la URL absoluta con el dominio canónico de config.php, no con
// HTTP_HOST, para evitar canonical poisoning desde dominios alias.
$esLocal = (php_sapi_name() === 'cli-server')
    || in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1'], true);

$base   = $esLocal ? '' : 'https://' . ($cliente['dominio'] ?? 'alejandrotinte.milocalweb.com.ar');
$assets = $base . '/assets';
$css    = $assets . '/css/';
$js     = $assets . '/js/';
$img    = $assets . '/img/';
$svg    = __DIR__ . '/../assets/img/svg/';

// Archivos CSS fuente (modulares). Se combinan en build time con:
//   php tools/build-css.php
// El resultado es assets/css/styles.css, que se carga de forma asíncrona.
// El CSS crítico (navbar + hero + variables) va inline en header.php.
$cssSourceFiles = [
    'base.css',
    'navbar.css',
    'hero.css',
    'sections/01-destacado.css',
    'sections/02-libros.css',
    'sections/03-lightbox.css',
    'sections/04-proyectos.css',
    'sections/05-autor.css',
    'sections/06-contacto.css',
    'sections/07-libro.css',
    'sections/08-animaciones.css',
    'aside.css',
    'clientes.css',
    'footer.css',
    'responsive/01-tablet.css',
    'responsive/02-mobile.css',
];

// Orden de ejecución de los scripts. Todos se cargan con `defer`, por lo que
// respetan este orden y corren después de parsear el HTML. Cada archivo se
// auto-protege si su nodo no existe en la página.
$jsFiles = [
    'back-to-top.js',
    'navbar.js',
    'smooth-scroll.js',
    'lightbox.js',
    'featured-float.js',
    'books-decor.js',
];

// Cache busting para styles.css (filemtime del bundle generado).
// Si el bundle todavía no existe, se usa time() para no cachear una 404.
$stylesPath    = __DIR__ . '/../assets/css/styles.css';
$stylesVersion = is_file($stylesPath) ? filemtime($stylesPath) : time();

// Mismo criterio de versionado por archivo para los JS.
$jsVersions = [];
foreach ($jsFiles as $file) {
    $path = __DIR__ . '/../assets/js/' . $file;
    $jsVersions[$file] = is_file($path) ? '?v=' . filemtime($path) : '';
}
