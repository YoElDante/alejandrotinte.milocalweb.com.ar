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
$esLocal = (php_sapi_name() === 'cli-server')
    || in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1'], true);

$base   = $esLocal ? '' : 'https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
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
    'sections.css',
    'aside.css',
    'clientes.css',
    'footer.css',
    'responsive.css',
];

$jsFiles = [
    'back-to-top.js',
    'navbar.js',
    'smooth-scroll.js',
    'lightbox.js',
    'featured-float.js',
];

// Cache busting para styles.css (filemtime del bundle generado).
$stylesPath    = __DIR__ . '/../assets/css/styles.css';
$stylesVersion = is_file($stylesPath) ? filemtime($stylesPath) : time();

$jsVersions = [];
foreach ($jsFiles as $file) {
    $path = __DIR__ . '/../assets/js/' . $file;
    $jsVersions[$file] = is_file($path) ? '?v=' . filemtime($path) : '';
}
