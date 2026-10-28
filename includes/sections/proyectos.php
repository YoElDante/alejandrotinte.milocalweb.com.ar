<?php
/**
 * Sección Proyectos en curso.
 *
 * Libros en producción + HacemeGancho, con CTAs para sumarse al desarrollo
 * y para hacer un aporte (donación o WhatsApp si no hay link de pago).
 *
 * @package MiLocalWeb\Clientes
 */

if (empty($cliente['proyectos_visible'])) {
    return;
}

$proyectos = $cliente['proyectos'] ?? [];
if (empty($proyectos)) {
    return;
}

$libros_prod = $proyectos['libros'] ?? [];
$hg          = $proyectos['hacemegancho'] ?? [];
$wa_hay      = wa_disponible($cliente);

$sumarse = wa_link($cliente, (string) ($cliente['proyectos_whatsapp_mensaje'] ?? 'Hola! Quiero sumarme al proyecto'));

$donacion_url = trim((string) ($cliente['proyectos_donacion_url'] ?? ''));
if ($donacion_url !== '') {
    $donacion = $donacion_url;
} else {
    $donacion = wa_link($cliente, (string) ($cliente['proyectos_donacion_mensaje'] ?? 'Hola! Quiero hacer un aporte'));
}

$hg_img = (string) ($hg['imagen'] ?? '');
$hg_img = asset_existe($hg_img) ? $hg_img : '';
$hg_url = trim((string) ($hg['url'] ?? ''));
?>
<section class="section section--alt projects" id="proyectos">
    <div class="container">
        <header class="section-head">
            <p class="section-kicker">En marcha</p>
            <h2 class="section-title">Proyectos en curso</h2>
            <?php if (!empty($proyectos['intro'])): ?>
            <p class="section-lead"><?= e($proyectos['intro']) ?></p>
            <?php endif; ?>
        </header>

        <div class="projects-grid">
            <?php if ($libros_prod): ?>
            <div class="project-card">
                <p class="project-label">Libros en producción</p>
                <ul class="project-books">
                    <?php foreach ($libros_prod as $libro): ?>
                    <li class="project-book">
                        <h3 class="project-book-title"><?= e($libro['titulo'] ?? '') ?></h3>
                        <?php if (!empty($libro['descripcion'])): ?>
                        <p class="project-book-desc"><?= e($libro['descripcion']) ?></p>
                        <?php endif; ?>
                        <span class="project-status">En escritura</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php if ($hg): ?>
            <div class="project-card project-card--hg">
                <?php if ($hg_img !== ''): ?>
                <div class="project-media">
                    <img src="<?= e($hg_img) ?>"
                         alt="HacemeGancho — sistema de interacción humana"
                         width="900" height="600"
                         loading="lazy"
                         decoding="async">
                </div>
                <?php endif; ?>

                <p class="project-label">Plataforma propia</p>
                <h3 class="project-title"><?= e($hg['nombre'] ?? 'HacemeGancho') ?></h3>

                <?php if (!empty($hg['descripcion'])): ?>
                <p class="project-desc"><?= e($hg['descripcion']) ?></p>
                <?php endif; ?>

                <?php if ($hg_url !== ''): ?>
                <p class="project-link-wrap">
                    <a href="<?= e($hg_url) ?>" class="project-link" target="_blank" rel="noopener noreferrer">
                        Conocer el proyecto &rarr;
                    </a>
                </p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="project-cta">
            <div class="project-cta-text">
                <h3 class="project-cta-title">¿Querés ser parte?</h3>
                <p>Se buscan ideas, miradas y manos para el desarrollo. Escribí y contá qué te gustaría aportar.</p>
            </div>

            <div class="project-cta-actions">
                <a href="<?= e($sumarse) ?>"
                   class="btn btn--whatsapp"
                   <?= $wa_hay ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                    <?= wa_icon() ?>
                    Sumarme al proyecto
                </a>

                <a href="<?= e($donacion) ?>"
                   class="btn <?= $donacion_url === '' ? 'btn--whatsapp' : 'btn--ghost' ?>"
                   <?= $donacion_url !== '' ? 'target="_blank" rel="noopener noreferrer"' : ($wa_hay ? 'target="_blank" rel="noopener noreferrer"' : '') ?>>
                    <?php if ($donacion_url === ''): ?><?= wa_icon() ?><?php endif; ?>
                    Apoyar con una donación
                </a>
            </div>

            <?php if (!empty($cliente['proyectos_donacion_texto'])): ?>
            <p class="project-credits"><?= e($cliente['proyectos_donacion_texto']) ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>
