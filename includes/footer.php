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
$redes       = array_filter($cliente['redes'] ?? []);
$email       = (string) ($cliente['email'] ?? '');
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
                <div class="footer-social-group">
                    <h4>Redes del autor</h4>
                    <div class="social-links">
                        <!-- Placeholders hasta tener las cuentas profesionales. -->
                        <span class="social-link social-link--instagram social-link--pending"
                              role="img"
                              aria-label="Instagram del autor (próximamente)"
                              title="Instagram del autor — próximamente">
                            <?= svg_icon('instagram-filled') ?>
                        </span>
                        <span class="social-link social-link--whatsapp social-link--pending"
                              role="img"
                              aria-label="WhatsApp del autor (próximamente)"
                              title="WhatsApp del autor — próximamente">
                            <?= svg_icon('whatsapp') ?>
                        </span>
                    </div>
                </div>

                <?php if ($redes): ?>
                <div class="footer-social-group">
                    <h4>Redes personales</h4>
                    <div class="social-links">
                        <?php if (!empty($redes['instagram'])): ?>
                        <a href="<?= e($redes['instagram']) ?>" target="_blank" rel="noopener noreferrer" class="social-link social-link--instagram" aria-label="Instagram personal de Alejandro Tinte">
                            <?= svg_icon('instagram-filled') ?>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($redes['facebook'])): ?>
                        <a href="<?= e($redes['facebook']) ?>" target="_blank" rel="noopener noreferrer" class="social-link social-link--facebook" aria-label="Facebook personal de Alejandro Tinte">
                            <?= svg_icon('facebook') ?>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($redes['linkedin'])): ?>
                        <a href="<?= e($redes['linkedin']) ?>" target="_blank" rel="noopener noreferrer" class="social-link social-link--linkedin" aria-label="LinkedIn de Alejandro Tinte">
                            <?= svg_icon('linkedin') ?>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($redes['tiktok'])): ?>
                        <a href="<?= e($redes['tiktok']) ?>" target="_blank" rel="noopener noreferrer" class="social-link social-link--tiktok" aria-label="TikTok de Alejandro Tinte">
                            <?= svg_icon('tiktok') ?>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($redes['web'])): ?>
                        <a href="<?= e($redes['web']) ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Sitio Web">
                            <?= svg_icon('globe') ?>
                        </a>
                        <?php endif; ?>
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

    <?php foreach ($jsFiles as $file): ?>
    <script src="<?= $js . $file . $jsVersions[$file] ?>" defer></script>
    <?php endforeach; ?>
</body>
</html>
