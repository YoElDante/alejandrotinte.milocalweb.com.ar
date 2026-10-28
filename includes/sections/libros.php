<?php
/**
 * Sección Libros — grilla de títulos del autor.
 *
 * Los datos salen de config.php['libros']. Cada tapa usa la miniatura de
 * 600px para la grilla y enlaza a la imagen completa, que se abre en un
 * lightbox (sin JS, el link abre la imagen directamente).
 *
 * @package MiLocalWeb\Clientes
 */

$libros      = $cliente['libros'] ?? [];
$pdf_publico = !empty($cliente['libros_pdf_publico']);
$wa_hay      = wa_disponible($cliente);
$destacado   = (string) ($cliente['libro_destacado'] ?? '');

// El libro destacado tiene su propia sección: no se repite en la grilla.
$libros = array_values(array_filter($libros, static function ($libro) use ($destacado) {
    return ($libro['slug'] ?? '') !== $destacado;
}));

if (empty($libros)) {
    return;
}

$hay_portada = false;
?>
<section class="section section--alt books-section" id="libros">
    <div class="container books-container">
        <header class="section-head">
            <p class="section-kicker">Obra</p>
            <h2 class="section-title">Los libros</h2>
            <?php if (!empty($cliente['libros_intro'])): ?>
            <p class="section-lead"><?= e($cliente['libros_intro']) ?></p>
            <?php endif; ?>
        </header>

        <div class="books-grid">
            <?php foreach ($libros as $libro): ?>
            <?php
                $portada_full  = (string) ($libro['portada'] ?? '');
                $portada_thumb = (string) ($libro['portada_600'] ?? '');

                $tiene_full  = asset_existe($portada_full);
                $tiene_thumb = asset_existe($portada_thumb);
                $tiene_pdf   = asset_existe((string) ($libro['pdf'] ?? ''));

                // Si falta la miniatura, se usa la imagen completa como fallback.
                $imagen_grilla = $tiene_thumb ? $portada_thumb : ($tiene_full ? $portada_full : '');
                $tiene_portada = $imagen_grilla !== '';
                $alt_portada   = 'Portada de ' . $libro['titulo'] . ' — ' . $cliente['nombre'];

                if ($tiene_portada) {
                    $hay_portada = true;
                }

                $consultar = wa_link($cliente, 'Hola! Quiero pedir mi copia de "' . $libro['titulo'] . '"');
                $resumen   = libro_resumen($libro);
                $url_libro = '/libros/' . $libro['slug'];
                $meta      = [];
                if (!empty($libro['paginas'])) {
                    $meta[] = (int) $libro['paginas'] . ' páginas';
                }
                if (!empty($libro['anio'])) {
                    $meta[] = (string) $libro['anio'];
                }
            ?>
            <article class="book-card">
                <?php if ($tiene_portada): ?>
                <a class="book-cover"
                   href="<?= e($url_libro) ?>"
                   aria-label="Ver la ficha de <?= e($libro['titulo']) ?>">
                    <img src="<?= e($imagen_grilla) ?>"
                         alt="<?= e($alt_portada) ?>"
                         loading="lazy"
                         decoding="async"
                         width="600" height="900">
                </a>
                <?php else: ?>
                <div class="book-cover book-cover--empty">
                    <span>Portada pendiente</span>
                </div>
                <?php endif; ?>

                <div class="book-body">
                    <h3 class="book-title"><?= e($libro['titulo']) ?></h3>

                    <?php if ($meta): ?>
                    <p class="book-meta"><?= e(implode(' · ', $meta)) ?></p>
                    <?php endif; ?>

                    <?php if ($resumen !== ''): ?>
                    <p class="book-desc"><?= e($resumen) ?></p>
                    <?php else: ?>
                    <p class="book-desc"><span class="pending">Sinopsis pendiente</span></p>
                    <?php endif; ?>

                    <div class="book-actions">
                        <?php if ($pdf_publico && $tiene_pdf): ?>
                        <a href="<?= e($libro['pdf']) ?>" class="book-btn" target="_blank" rel="noopener">
                            Leer libro
                        </a>
                        <?php endif; ?>
                        <a href="<?= e($url_libro) ?>" class="book-btn book-btn--more">
                            <?= svg_icon('book-open') ?>
                            Conocer más
                        </a>
                        <a href="<?= e($consultar) ?>"
                           class="book-btn book-btn--whatsapp"
                           <?= $wa_hay ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                            <?= wa_icon() ?>
                            Pedir tu copia
                        </a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <?php
        $imagen_decor = (string) ($cliente['libros_imagen_decor'] ?? '');
        if ($imagen_decor !== '' && asset_existe($imagen_decor)):
        ?>
        <img class="books-decor" src="<?= e($imagen_decor) ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">
        <?php endif; ?>
    </div>
</section>
