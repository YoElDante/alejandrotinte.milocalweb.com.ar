<?php
/**
 * Sección Sobre el autor.
 *
 * @package MiLocalWeb\Clientes
 */

$bio       = array_filter((array) ($cliente['autor_bio'] ?? []));
$foto      = (string) ($cliente['autor_foto'] ?? '');
$wa_hay    = wa_disponible($cliente);

$iniciales = '';
foreach (preg_split('/\s+/', trim((string) $cliente['nombre'])) ?: [] as $palabra) {
    if ($palabra !== '') {
        $iniciales .= mb_strtoupper(mb_substr($palabra, 0, 1));
    }
}
$iniciales = mb_substr($iniciales, 0, 2);
?>
<section class="section" id="autor">
    <div class="container about">
        <div class="about-media">
            <?php if (asset_existe($foto)): ?>
            <img src="<?= e($foto) ?>"
                 alt="<?= e($cliente['nombre']) ?> — autor"
                 class="about-photo"
                 loading="lazy"
                 decoding="async"
                 width="600" height="800">
            <?php else: ?>
            <div class="about-photo about-photo--empty" role="img" aria-label="Retrato del autor pendiente">
                <span><?= e($iniciales) ?></span>
            </div>
            <?php endif; ?>

            <?php
            $autor_flotante = (string) ($cliente['autor_flotante'] ?? '');
            if ($autor_flotante !== '' && asset_existe($autor_flotante)):
            ?>
            <img class="about-media-float"
                 src="<?= e($autor_flotante) ?>"
                 alt=""
                 aria-hidden="true"
                 loading="lazy"
                 decoding="async">
            <?php endif; ?>
        </div>

        <div class="about-body">
            <p class="section-kicker">Sobre el autor</p>
            <h2 class="section-title">Quién es <?= e($cliente['nombre']) ?></h2>

            <?php if ($bio): ?>
                <?php foreach ($bio as $parrafo): ?>
                <p class="about-text"><?= e($parrafo) ?></p>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="about-text"><span class="pending">Biografía pendiente</span></p>
            <?php endif; ?>

            <?php
            $amazon_perfil = trim((string) ($cliente['amazon_perfil'] ?? ''));
            $amazon_logo   = amazon_icon();
            ?>
            <div class="about-actions">
                <a href="<?= e(wa_link($cliente)) ?>"
                   class="btn btn--whatsapp"
                   <?= $wa_hay ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                    <?= wa_icon() ?>
                    Escribile al autor
                </a>

                <?php if ($amazon_perfil !== ''): ?>
                <a href="<?= e($amazon_perfil) ?>"
                   class="btn btn--amazon"
                   target="_blank"
                   rel="noopener noreferrer">
                    Perfil del autor en <?= $amazon_logo !== '' ? $amazon_logo : 'Amazon' ?>
                </a>
                <?php endif; ?>
            </div>

            <?php
            $autor_imagen = (string) ($cliente['autor_imagen'] ?? '');
            $libro_cta    = null;
            $slug_cta     = (string) ($cliente['libro_destacado'] ?? '');

            foreach (($cliente['libros'] ?? []) as $candidato) {
                if (($candidato['slug'] ?? '') === $slug_cta) {
                    $libro_cta = $candidato;
                    break;
                }
            }
            ?>

            <?php if (asset_existe($autor_imagen)): ?>
            <div class="about-extra">
                <img src="<?= e($autor_imagen) ?>"
                     alt="Alejandro Tinte leyendo A Fuego y Fierro en un café de Buenos Aires"
                     width="900" height="506"
                     loading="lazy"
                     decoding="async">
            </div>
            <?php endif; ?>

            <?php if ($libro_cta !== null): ?>
            <?php
            $cta_portada = (string) ($libro_cta['portada_600'] ?? '');
            $cta_portada = asset_existe($cta_portada) ? $cta_portada : (string) ($libro_cta['portada'] ?? '');
            ?>
            <aside class="about-cta">
                <?php if ($cta_portada !== ''): ?>
                <a href="/libros/<?= e($libro_cta['slug']) ?>" class="about-cta-cover" tabindex="-1" aria-hidden="true">
                    <img src="<?= e($cta_portada) ?>" alt="" loading="lazy" decoding="async" width="120" height="180">
                </a>
                <?php endif; ?>

                <div class="about-cta-body">
                    <p class="about-cta-title"><?= e($cliente['autor_cta_texto'] ?? 'Te invito a conocer mi mayor obra') ?></p>

                    <?php
                    $cta_amazon = (string) ($cliente['amazon'][$slug_cta] ?? '');
                    $cta_logo   = amazon_icon();
                    ?>
                    <div class="about-cta-actions">
                        <a href="/libros/<?= e($libro_cta['slug']) ?>" class="btn btn--primary btn--book">
                            <?= svg_icon('book-closed') ?>
                            Conocer <?= e($libro_cta['titulo']) ?>
                        </a>

                        <?php if ($cta_amazon !== ''): ?>
                        <a href="<?= e($cta_amazon) ?>"
                           class="btn btn--amazon"
                           target="_blank"
                           rel="noopener noreferrer">
                            Adquirilo en <?= $cta_logo !== '' ? $cta_logo : 'Amazon' ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </aside>
            <?php endif; ?>
        </div>
    </div>
</section>
