<?php
/**
 * Sección Obra destacada — libro principal a pantalla completa.
 *
 * Toma el slug de `config.php['libro_destacado']`, muestra la portada y la
 * descripción completa desde `productos/{slug}/descripcion.txt`, y ofrece
 * los CTA de compra/consulta. El libro destacado se excluye de la grilla.
 *
 * @package MiLocalWeb\Clientes
 */

$slug_destacado = (string) ($cliente['libro_destacado'] ?? '');

// Sin slug configurado la sección no se imprime (la home sigue funcionando).
if ($slug_destacado === '') {
    return;
}

// El libro se busca por slug en el catálogo; si no existe, se omite el bloque
// para no mostrar una sección vacía.
$libro = null;
foreach (($cliente['libros'] ?? []) as $candidato) {
    if (($candidato['slug'] ?? '') === $slug_destacado) {
        $libro = $candidato;
        break;
    }
}

if ($libro === null) {
    return;
}

$parrafos    = texto_parrafos(libro_descripcion($libro));
$amazon      = (string) ($cliente['amazon'][$slug_destacado] ?? '');
$google_play = (string) ($cliente['google_play'][$slug_destacado] ?? '');
$wa_hay      = wa_disponible($cliente);
$url_libro = '/libros/' . $libro['slug'];
$consultar = wa_link($cliente, 'Hola! Quiero pedir mi copia de "' . $libro['titulo'] . '"');

// Portada: se prefiere la miniatura de 600px y se cae a la full si falta.
// La imagen decorativa es independiente del libro y también es opcional.
$portada = asset_existe((string) ($libro['portada'] ?? '')) ? $libro['portada'] : '';
$portada_grilla = asset_existe((string) ($libro['portada_600'] ?? '')) ? $libro['portada_600'] : $portada;
$alt     = 'Portada de ' . $libro['titulo'] . ' — ' . $cliente['nombre'];

$flotante = (string) ($cliente['destacado_flotante'] ?? '');
$flotante = asset_existe($flotante) ? $flotante : '';
?>
<section class="featured" id="destacado" aria-labelledby="destacado-titulo">
    <div class="featured-effects" aria-hidden="true"></div>

    <div class="container featured-inner">
        <div class="featured-media">
            <?php if ($portada !== ''): ?>
            <a href="<?= e($url_libro) ?>" class="featured-cover" aria-label="Ver la ficha de <?= e($libro['titulo']) ?>">
                <img src="<?= e($portada_grilla) ?>"
                     alt="<?= e($alt) ?>"
                     width="600" height="900"
                     loading="eager"
                     decoding="async">
            </a>
            <?php endif; ?>

            <a href="<?= e($url_libro) ?>" class="featured-more">
                <?= svg_icon('book-open') ?>
                Ver ficha completa
            </a>
        </div>

        <div class="featured-body">
            <p class="section-kicker">Obra destacada</p>

            <h2 class="featured-title" id="destacado-titulo"><?= e($libro['titulo']) ?></h2>

            <?php if (!empty($libro['subtitulo'])): ?>
            <p class="featured-subtitle"><?= e($libro['subtitulo']) ?></p>
            <?php endif; ?>

            <?php if ($parrafos): ?>
            <div class="featured-text">
                <?php foreach ($parrafos as $parrafo): ?>
                <p><?= e($parrafo) ?></p>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <div class="featured-actions">
                <a href="<?= e($consultar) ?>"
                   class="btn btn--whatsapp"
                   <?= $wa_hay ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                    <?= wa_icon() ?>
                    Pedir tu copia
                </a>

                <?php if ($amazon !== ''): ?>
                <?php $amazon_logo = amazon_icon(); ?>
                <a href="<?= e($amazon) ?>"
                   class="btn btn--amazon"
                   target="_blank"
                   rel="noopener noreferrer">
                    Conseguir en <?= $amazon_logo !== '' ? $amazon_logo : 'Amazon' ?>
                </a>
                <?php endif; ?>

                <?php if ($google_play !== ''): ?>
                <?php $google_logo = google_play_icon(); ?>
                <a href="<?= e($google_play) ?>"
                   class="btn btn--google"
                   target="_blank"
                   rel="noopener noreferrer">
                    <?= $google_logo !== '' ? $google_logo : '' ?>
                    Comprar en Google Play
                </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($flotante !== ''): ?>
        <img class="featured-float" src="<?= e($flotante) ?>"<?= img_dims_attrs($flotante) ?> alt="" aria-hidden="true" loading="eager" decoding="async">
        <?php endif; ?>
    </div>
</section>
