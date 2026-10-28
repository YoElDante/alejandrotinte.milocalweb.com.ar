<?php
/**
 * Sección Contacto — canal directo con el autor.
 *
 * @package MiLocalWeb\Clientes
 */

$wa_hay = wa_disponible($cliente);
$email  = (string) ($cliente['email'] ?? '');
$redes  = array_filter($cliente['redes'] ?? []);
?>
<section class="section section--contact" id="contacto">
    <div class="container contact-box">
        <p class="section-kicker">Contacto</p>
        <h2 class="section-title">Hablemos</h2>
        <p class="section-lead"><?= e($cliente['contacto_texto'] ?? '') ?></p>

        <div class="contact-actions">
            <?php if ($wa_hay): ?>
            <a href="<?= e(wa_link($cliente)) ?>" class="btn btn--whatsapp" target="_blank" rel="noopener noreferrer">
                <?= wa_icon() ?>
                Escribir por WhatsApp
            </a>
            <?php endif; ?>

            <?php if ($email !== ''): ?>
            <a href="mailto:<?= e($email) ?>" class="btn btn--ghost"><?= e($email) ?></a>
            <?php endif; ?>
        </div>

        <?php if (!$wa_hay && $email === ''): ?>
        <p><span class="pending">WhatsApp y email pendientes de relevamiento</span></p>
        <?php endif; ?>

        <?php if ($redes): ?>
        <div class="social-links contact-social">
            <?php if (!empty($redes['instagram'])): ?>
            <a href="<?= e($redes['instagram']) ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Instagram">
                <?php include $svg . 'instagram-filled.svg'; ?>
            </a>
            <?php endif; ?>
            <?php if (!empty($redes['facebook'])): ?>
            <a href="<?= e($redes['facebook']) ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Facebook">
                <?php include $svg . 'facebook.svg'; ?>
            </a>
            <?php endif; ?>
            <?php if (!empty($redes['tiktok'])): ?>
            <a href="<?= e($redes['tiktok']) ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="TikTok">
                <?php include $svg . 'tiktok.svg'; ?>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
