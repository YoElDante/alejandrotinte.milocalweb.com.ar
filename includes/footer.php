<?php
/**
 * Pie de página de la landing page del cliente.
 *
 * Incluye identidad del autor, redes, contacto, badge publicitario de
 * MiLocalWeb, botón volver arriba y el botón flotante de WhatsApp.
 *
 * @package MiLocalWeb\Clientes
 */

$wa_full     = wa_link($cliente);
$wa_hay      = wa_disponible($cliente);
$email       = (string) ($cliente['email'] ?? '');

// Redes oficiales (primarias) y personales (secundarias, sin repetir URLs).
// Si una cuenta personal ya figura como oficial, se omite para no duplicar
// iconos en el footer ni repetir enlaces para el usuario.
$redes_oficiales  = redes_oficiales($cliente);
$urls_oficiales   = array_map(static function ($url) {
    return trim((string) $url);
}, array_values($redes_oficiales));
$redes_personales = array_filter($cliente['redes'] ?? [], static function ($url) use ($urls_oficiales) {
    return trim((string) $url) !== '' && !in_array(trim((string) $url), $urls_oficiales, true);
});

// Traducción clave de config -> [nombre accesible, icono SVG]. Si aparece una
// red nueva sin entrada acá, se usa la clave como texto y no se pinta icono.
$red_social_meta = [
    'instagram' => ['Instagram', 'instagram-filled'],
    'facebook'  => ['Facebook', 'facebook'],
    'linkedin'  => ['LinkedIn', 'linkedin'],
    'tiktok'    => ['TikTok', 'tiktok'],
    'web'       => ['Sitio web', 'globe'],
];
$logo_src    = (string) ($cliente['logo_img'] ?? '');
$wa_target   = $wa_hay ? 'target="_blank" rel="noopener noreferrer"' : '';
$mlw_msg     = 'Hola! Quiero una web como la de ' . ($cliente['nombre'] ?? '') . ' para mi proyecto';
?>
    </main>

    <footer class="site-footer" role="contentinfo">
        <div class="footer-content">
            <div class="footer-brand">
                <?php if (asset_existe($logo_src)): ?>
                <img src="<?= e($logo_src) ?>"
                     alt="<?= e($cliente['nombre']) ?>"
                     class="footer-logo"
                     loading="lazy"
                     decoding="async"
                     width="300" height="300">
                <?php else: ?>
                <p class="footer-brand-name"><?= e($cliente['nombre']) ?></p>
                <?php endif; ?>
                <p class="footer-slogan"><?= e($cliente['slogan'] ?? '') ?></p>
            </div>

            <div class="footer-social">
                <?php if ($redes_oficiales): ?>
                <div class="footer-social-group">
                    <h4>Redes del autor</h4>
                    <div class="social-links">
                        <?php foreach ($redes_oficiales as $clave => $url): ?>
                        <?php
                            $meta = $red_social_meta[$clave] ?? [$clave, ''];
                            $nombre = $meta[0];
                            $icono  = $meta[1];
                        ?>
                        <a href="<?= e($url) ?>" target="_blank" rel="noopener noreferrer"
                           class="social-link social-link--<?= e($clave) ?>"
                           aria-label="<?= e($nombre) ?> oficial de <?= e($cliente['nombre']) ?>">
                            <?= $icono !== '' ? svg_icon($icono) : '' ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($redes_personales): ?>
                <div class="footer-social-group">
                    <h4>Redes personales</h4>
                    <div class="social-links">
                        <?php foreach ($redes_personales as $clave => $url): ?>
                        <?php
                            $meta = $red_social_meta[$clave] ?? [$clave, ''];
                            $nombre = $meta[0];
                            $icono  = $meta[1];
                        ?>
                        <a href="<?= e($url) ?>" target="_blank" rel="noopener noreferrer"
                           class="social-link social-link--<?= e($clave) ?>"
                           aria-label="<?= e($nombre) ?> personal de <?= e($cliente['nombre']) ?>">
                            <?= $icono !== '' ? svg_icon($icono) : '' ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="footer-actions">
                <h4>Contacto</h4>
                <?php if ($wa_hay): ?>
                <a href="<?= e($wa_full) ?>" target="_blank" rel="noopener noreferrer" class="footer-wa-link">
                    <?php include $svg . 'whatsapp.svg'; ?>
                    Escribile por WhatsApp
                </a>
                <?php endif; ?>
                <?php if ($email !== ''): ?>
                <a href="mailto:<?= e($email) ?>" class="footer-mail-link"><?= e($email) ?></a>
                <?php endif; ?>
                <?php if (!$wa_hay && $email === ''): ?>
                <p class="footer-pending">Datos de contacto <span class="pending">pendientes</span></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Badge MiLocalWeb -->
        <div class="footer-badge">
            <a href="https://milocalweb.com.ar#contacto" target="_blank" rel="noopener" class="footer-mlw-logo" title="MiLocalWeb.com.ar — Páginas web para negocios locales">
                <img src="<?= $img ?>milocalweb/logos/logo-principal-690x300-transp.webp"
                     alt="MiLocalWeb.com.ar — Páginas web para negocios locales"
                     class="mlw-logo-img"
                     loading="lazy"
                     decoding="async"
                     width="691" height="300">
            </a>
            <div class="footer-badge-text">
                <p>
                    Hecho con <?php include $svg . 'heart.svg'; ?>
                    por
                    <a href="https://milocalweb.com.ar#contacto" target="_blank" rel="noopener" class="mlw-link">
                        <strong>MiLocalWeb.com.ar</strong>
                    </a>
                </p>
                <p class="footer-badge-cta">
                    ¿Te gustó esta web?
                    <a href="<?= e(mlw_wa_link($mlw_msg)) ?>" target="_blank" rel="noopener noreferrer">
                        Pedí la tuya sin cargo
                        <?php include $svg . 'whatsapp.svg'; ?>
                    </a>
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> <?= e($cliente['nombre']) ?>. Todos los derechos reservados.</p>
        </div>

        <!-- Botón volver arriba -->
        <button class="back-to-top" aria-label="Volver arriba" title="Volver arriba">
            <?php include $svg . 'chevron-up.svg'; ?>
        </button>
    </footer>

    <!-- Botón flotante de WhatsApp -->
    <a href="<?= e($wa_full) ?>"
       <?= $wa_target ?>
       class="whatsapp-float"
       title="<?= $wa_hay ? 'Escribinos por WhatsApp' : 'Número de WhatsApp pendiente' ?>"
       aria-label="Chatear por WhatsApp">
        <?php include $svg . 'whatsapp.svg'; ?>
    </a>

    <!-- Lightbox de portadas (compartido por la home y las fichas de libro) -->
    <dialog class="lightbox" id="lightbox" aria-label="Portada ampliada">
        <button type="button" class="lightbox-close" aria-label="Cerrar imagen ampliada">&times;</button>
        <img src="" alt="" class="lightbox-img">
    </dialog>

    <?php
    // Scripts al final del body y con defer: se descargan en paralelo y se
    // ejecutan en el orden definido en bootstrap.php. El ?v= viene de filemtime.
    ?>
    <?php foreach ($jsFiles as $file): ?>
    <script src="<?= $js . $file . $jsVersions[$file] ?>" defer></script>
    <?php endforeach; ?>
</body>
</html>
