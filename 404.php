<?php
/**
 * Página 404.
 *
 * La usa el router de desarrollo y Apache (`ErrorDocument`) para rutas que
 * no existen. Devuelve el header HTTP 404 real y no se indexa.
 *
 * @package MiLocalWeb\Clientes
 */

require_once __DIR__ . '/includes/bootstrap.php';

http_response_code(404);

$page_title       = 'Página no encontrada — ' . $cliente['nombre'];
$page_descripcion = 'La dirección que abriste no existe o cambió de lugar.';
$page_robots      = 'noindex, follow';

require_once __DIR__ . '/includes/header.php';
?>
<section class="section">
    <div class="container book-notfound">
        <p class="section-kicker">Error 404</p>
        <h1 class="section-title">Página no encontrada</h1>
        <p class="section-lead">La dirección que abriste no existe o cambió de lugar.</p>
        <p class="book-page-actions">
            <a href="/" class="btn btn--primary">Ir al inicio</a>
            <a href="/#libros" class="btn btn--ghost">Ver los libros</a>
        </p>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
