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

if ($slug_destacado === '') {
    return;
}

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

$parrafos  = texto_parrafos(libro_descripcion($libro));
$amazon    = (string) ($cliente['amazon'][$slug_destacado] ?? '');
$wa_hay    = wa_disponible($cliente);
$url_libro = '/libros/' . $libro['slug'];
$consultar = wa_link($cliente, 'Hola! Quiero pedir mi copia de "' . $libro['titulo'] . '"');

$portada = asset_existe((string) ($libro['portada'] ?? '')) ? $libro['portada'] : '';
$portada_grilla = asset_existe((string) ($libro['portada_600'] ?? '')) ? $libro['portada_600'] : $portada;
$alt     = 'Portada de ' . $libro['titulo'] . ' — ' . $cliente['nombre'];
?>
<section class="featured" id="destacado" aria-labelledby="destacado-titulo">
    <div class="featured-effects" aria-hidden="true"></div>

    <div class="container featured-inner">
        <?php if ($portada !== ''): ?>
        <div class="featured-media">
            <a href="<?= e($url_libro) ?>" class="featured-cover" aria-label="Ver la ficha de <?= e($libro['titulo']) ?>">
                <img src="<?= e($portada_grilla) ?>"
                     alt="<?= e($alt) ?>"
                     width="600" height="900"
                     loading="eager"
                     decoding="async">
            </a>
        </div>
        <?php endif; ?>

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

                <a href="<?= e($url_libro) ?>" class="featured-link">Ver ficha completa</a>
            </div>
        </div>
    </div>
</section>
