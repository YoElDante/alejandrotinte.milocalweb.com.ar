<?php
/**
 * Sección Contacto — canal directo con el autor.
 *
 * Muestra WhatsApp y/o email según lo cargado en config.php. Si no hay
 * ninguno, imprime un aviso de dato pendiente en lugar de botones rotos.
 * Las redes oficiales se listan debajo como refuerzo de presencia digital.
 *
 * @package MiLocalWeb\Clientes
 */

$wa_hay = wa_disponible($cliente);
$email  = (string) ($cliente['email'] ?? '');
$redes  = redes_oficiales($cliente);

// Clave de config -> [nombre accesible, icono SVG]; mismo mapa que el footer.
$red_social_meta = [
    'instagram' => ['Instagram', 'instagram-filled'],
    'facebook'  => ['Facebook', 'facebook'],
    'linkedin'  => ['LinkedIn', 'linkedin'],
    'tiktok'    => ['TikTok', 'tiktok'],
    'web'       => ['Sitio web', 'globe'],
];
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

        <?php if ($email !== '' && !empty($cliente['email_nota'])): ?>
        <p class="contact-note"><?= e($cliente['email_nota']) ?></p>
        <?php endif; ?>

        <?php if (!$wa_hay && $email === ''): ?>
        <p><span class="pending">WhatsApp y email pendientes de relevamiento</span></p>
        <?php endif; ?>

        <?php if ($redes): ?>
        <div class="social-links contact-social">
            <?php foreach ($redes as $clave => $url): ?>
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
        <?php endif; ?>
    </div>
</section>
