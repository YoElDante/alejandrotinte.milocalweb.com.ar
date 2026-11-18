<?php
/**
 * Hero — presentación del autor.
 *
 * Escritorio: la foto va detrás del texto, fundida de derecha a izquierda, con
 * el autor sobre el margen derecho. Mobile: texto arriba y foto horizontal abajo
 * (se resuelve con <picture>, sin descargar la imagen que no corresponde).
 *
 * @package MiLocalWeb\Clientes
 */

$wa_hay = wa_disponible($cliente);

// La foto es opcional: si el archivo no existe, el hero queda solo con el
// degradado de fondo y no se imprime el <div> de imagen.
$hero_img   = (string) ($cliente['hero_imagen'] ?? '');
$tiene_hero = asset_existe($hero_img);

// Instagram oficial: botón de redes del hero (debajo del CTA de WhatsApp).
$ig_url = trim((string) (redes_oficiales($cliente)['instagram'] ?? ''));
?>
<section class="hero" id="inicio">
    <?php if ($tiene_hero): ?>
    <div class="hero-bg" aria-hidden="true">
        <img src="<?= e($hero_img) ?>"<?= img_dims_attrs($hero_img) ?>
             alt=""
             loading="eager"
             fetchpriority="high"
             decoding="async">
    </div>
    <div class="hero-overlay" aria-hidden="true"></div>
    <?php endif; ?>

    <div class="container hero-inner">
        <div class="hero-content">
            <p class="hero-kicker"><?= e($cliente['hero_kicker'] ?? '') ?></p>
            <h1 class="hero-title"><?= e($cliente['nombre']) ?></h1>
            <p class="hero-subtitle"><?= e($cliente['slogan'] ?? '') ?></p>
            <p class="hero-lead"><?= e($cliente['hero_descripcion'] ?? '') ?></p>

            <div class="hero-actions">
                <?php /* CTA principal del negocio: WhatsApp primero; después
                         Instagram y "Ver las obras" como acción que baja a #libros. */ ?>
                <a href="<?= e(wa_link($cliente)) ?>"
                   class="btn btn--whatsapp"
                   <?= $wa_hay ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                    <?= wa_icon() ?>
                    <?= e($cliente['hero_boton_sec'] ?? 'Contacto') ?>
                </a>
                <?php if ($ig_url !== ''): ?>
                <a href="<?= e($ig_url) ?>"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="btn btn--instagram"
                   aria-label="Instagram oficial de <?= e($cliente['nombre']) ?>">
                    <?= svg_icon('instagram-filled') ?>
                    Seguir en Instagram
                </a>
                <?php endif; ?>
                <a href="#libros" class="btn btn--primary">
                    <?= svg_icon('books') ?>
                    <?= e($cliente['hero_boton_ppal'] ?? 'Ver las obras') ?>
                </a>
            </div>
        </div>
    </div>
</section>
