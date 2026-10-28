<?php
/**
 * Aside publicitario de MiLocalWeb.
 *
 * Logo discreto + slogan + CTA a WhatsApp para captar nuevos clientes.
 * No debe ser invasivo — es autopromoción sutil.
 *
 * @package MiLocalWeb\Clientes
 */

if (empty($cliente['aside_visible'])) {
    return;
}

$mlw_wa = mlw_wa_link('Hola! Vi la web de ' . ($cliente['nombre'] ?? '') . ' hecha por ustedes y me encantó. Quiero consultar para tener la mía.');
?>
<aside class="publicidad-aside" aria-label="Publicidad">
    <div class="aside-container">
        <a href="https://milocalweb.com.ar" target="_blank" rel="noopener" class="aside-logo" title="MiLocalWeb — más visibilidad, más clientes">
            <img src="<?= $img ?>milocalweb/logos/logo-principal-690x300-transp.webp"
                 alt="MiLocalWeb.com.ar — Páginas web para negocios locales"
                 class="aside-logo-img"
                 loading="lazy"
                 decoding="async"
                 width="691" height="300">
        </a>

        <div class="aside-texto">
            <span class="aside-slogan">más visibilidad, más clientes</span>
            <span class="aside-pregunta">¿Te gustaría tener una web como esta?</span>
        </div>

        <a href="<?= e($mlw_wa) ?>" target="_blank" rel="noopener noreferrer" class="aside-cta">
            <?php include $svg . 'whatsapp.svg'; ?>
            Pedila sin cargo
        </a>
    </div>
</aside>
