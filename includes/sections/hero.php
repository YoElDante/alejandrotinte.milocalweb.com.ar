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

$hero_img   = (string) ($cliente['hero_imagen'] ?? '');
$tiene_hero = asset_existe($hero_img);
?>
<section class="hero" id="inicio">
    <?php if ($tiene_hero): ?>
    <div class="hero-bg" aria-hidden="true">
        <img src="<?= e($hero_img) ?>"
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
                <a href="<?= e(wa_link($cliente)) ?>"
                   class="btn btn--whatsapp"
                   <?= $wa_hay ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                    <?= wa_icon() ?>
                    <?= e($cliente['hero_boton_sec'] ?? 'Contacto') ?>
                </a>
                <a href="#libros" class="btn btn--primary">
                    <?= e($cliente['hero_boton_ppal'] ?? 'Ver las obras') ?>
                    <?= svg_icon('chevron-down') ?>
                </a>
            </div>
        </div>
    </div>
</section>
