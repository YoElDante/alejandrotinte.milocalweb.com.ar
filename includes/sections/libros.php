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
$antologia_slug = (string) ($cliente['libro_antologia'] ?? '');

// La antología ocupa un bloque propio al final de la sección.
$antologia = null;
if ($antologia_slug !== '') {
    foreach ($libros as $candidato) {
        if (($candidato['slug'] ?? '') === $antologia_slug) {
            $antologia = $candidato;
            break;
        }
    }
}

// El libro destacado tiene su propia sección: no se repite en la grilla.
$libros = array_values(array_filter($libros, static function ($libro) use ($destacado, $antologia_slug) {
    $slug = (string) ($libro['slug'] ?? '');

    return $slug !== $destacado && $slug !== $antologia_slug;
}));

if (empty($libros) && $antologia === null) {
    return;
}

$hay_portada = false;
$section_clases = 'section section--alt books-section';
?>
<section class="<?= e($section_clases) ?>" id="libros">
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
                    <?php if (!empty($libro['novedad'])): ?>
                    <p class="book-flag">Novedad</p>
                    <?php endif; ?>

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

            <?php
            // Imagen de cierre de la sección: no es una card. El JS decide si
            // ocupa el hueco libre de la última fila o si va abajo como divisor.
            $imagen_decor = (string) ($cliente['libros_imagen_decor'] ?? '');
            if ($imagen_decor !== '' && asset_existe($imagen_decor)):
            ?>
            <img class="books-decor" src="<?= e($imagen_decor) ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">
            <?php endif; ?>
        </div>

        <?php if ($antologia !== null): ?>
        <?php
            $ant_slug      = (string) $antologia['slug'];
            $ant_url       = '/libros/' . $ant_slug;
            $ant_full      = (string) ($antologia['portada'] ?? '');
            $ant_thumb     = (string) ($antologia['portada_600'] ?? '');
            $ant_portada   = asset_existe($ant_thumb) ? $ant_thumb : (asset_existe($ant_full) ? $ant_full : '');
            $ant_arte      = (string) ($cliente['libro_antologia_imagen'] ?? '');
            $ant_arte      = asset_existe($ant_arte) ? $ant_arte : '';
            $ant_parrafos  = texto_parrafos(libro_descripcion($antologia));
            $ant_resumen   = implode(' ', array_slice($ant_parrafos, 0, 2));
            $ant_play      = (string) ($cliente['google_play'][$ant_slug] ?? '');
            $ant_consultar = wa_link($cliente, 'Hola! Quiero pedir mi copia de "' . $antologia['titulo'] . '"');
            $ant_alt       = 'Portada de ' . $antologia['titulo'] . ' — ' . $cliente['nombre'];
        ?>
        <aside class="books-collection" id="antologia" aria-labelledby="antologia-titulo">
            <div class="books-collection-media">
                <?php if ($ant_portada !== ''): ?>
                <a class="books-collection-cover"
                   href="<?= e($ant_url) ?>"
                   aria-label="Ver la ficha de <?= e($antologia['titulo']) ?>">
                    <img src="<?= e($ant_portada) ?>"
                         alt="<?= e($ant_alt) ?>"
                         loading="lazy"
                         decoding="async"
                         width="600" height="900">
                </a>
                <?php endif; ?>

                <?php if ($ant_arte !== ''): ?>
                <img class="books-collection-float"
                     src="<?= e($ant_arte) ?>"
                     alt=""
                     aria-hidden="true"
                     loading="lazy"
                     decoding="async">
                <?php endif; ?>
            </div>

            <div class="books-collection-body">
                <p class="section-kicker">Antología</p>
                <h3 class="books-collection-title" id="antologia-titulo"><?= e($antologia['titulo']) ?></h3>

                <?php if (!empty($antologia['subtitulo'])): ?>
                <p class="books-collection-subtitle"><?= e($antologia['subtitulo']) ?></p>
                <?php endif; ?>

                <?php if ($ant_resumen !== ''): ?>
                <p class="books-collection-text"><?= e($ant_resumen) ?></p>
                <?php endif; ?>

                <div class="books-collection-actions">
                    <a href="<?= e($ant_url) ?>" class="book-btn book-btn--more">
                        <?= svg_icon('book-open') ?>
                        Conocer la antología
                    </a>

                    <?php if ($ant_play !== ''): ?>
                    <a href="<?= e($ant_play) ?>"
                       class="btn btn--google"
                       target="_blank"
                       rel="noopener noreferrer">
                        <?= google_play_icon() ?>
                        Comprar en Google Play
                    </a>
                    <?php endif; ?>

                    <a href="<?= e($ant_consultar) ?>"
                       class="btn btn--whatsapp"
                       <?= $wa_hay ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                        <?= wa_icon() ?>
                        Pedir tu copia
                    </a>
                </div>
            </div>
        </aside>
        <?php endif; ?>
    </div>
</section>
