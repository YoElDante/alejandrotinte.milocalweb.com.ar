<?php
/**
 * Router para el servidor embebido de PHP (SOLO desarrollo local).
 *
 * PHP 8.4 devuelve `index.php` ante cualquier ruta inexistente, así que las
 * URLs limpias (`/libros/{slug}`) muestran la home aunque la URL cambie.
 * En producción esas rutas las resuelve `.htaccess` con mod_rewrite.
 *
 * Uso:
 *   php -S 127.0.0.1:8099 router.php
 *
 * @package MiLocalWeb\Clientes
 */

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = is_string($path) ? rawurldecode($path) : '/';

// Archivos reales (php, css, js, imágenes): los sirve el propio servidor.
if ($path !== '/' && is_file(__DIR__ . $path)) {
    return false;
}

// /libros/{slug} → ficha del libro
if (preg_match('#^/libros/([a-z0-9-]+)/?$#', $path, $match)) {
    $_GET['slug'] = $match[1];
    require __DIR__ . '/libro.php';
    return true;
}

// /libros → ancla de la sección en la home
if (rtrim($path, '/') === '/libros') {
    header('Location: /#libros', true, 301);
    return true;
}

// Home
if ($path === '/' || $path === '/index.php') {
    require __DIR__ . '/index.php';
    return true;
}

// Cualquier otra ruta: 404 real
require __DIR__ . '/404.php';
return true;
