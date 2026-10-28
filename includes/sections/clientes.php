<?php
/**
 * Sección "Nuestros Clientes" de MiLocalWeb.
 *
 * Muestra logos de otros clientes del portfolio como vitrina discreta.
 * Solo logos en escala de grises, sin textos llamativos.
 *
 * @package MiLocalWeb\Clientes
 */

if (empty($cliente['mostrar_clientes'])) {
    return;
}

$lista_clientes = $cliente['clientes'] ?? [];
if (empty($lista_clientes)) {
    return;
}
?>
<section class="section section-clientes" aria-label="Otros clientes de MiLocalWeb">
    <div class="container">
        <p class="clientes-titulo">
            <?= e($cliente['clientes_encabezado'] ?? 'Otros negocios que confían en MiLocalWeb') ?>
        </p>
        <div class="clientes-grid">
            <?php foreach ($lista_clientes as $c): ?>
            <a href="<?= e($c['url'] ?? 'https://milocalweb.com.ar') ?>"
               target="_blank" rel="noopener noreferrer"
               class="cliente-logo-link"
               title="<?= e($c['nombre'] ?? '') ?>">
                <img src="<?= e($c['logo'] ?? '') ?>"
                     alt="<?= e($c['nombre'] ?? '') ?>"
                     class="cliente-logo"
                     loading="lazy"
                     width="<?= (int) ($c['ancho'] ?? 120) ?>"
                     height="<?= (int) ($c['alto'] ?? 60) ?>">
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
