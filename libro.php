<?php
/**
 * Ficha de un libro — /libros/{slug}
 *
 * Página individual por obra: portada, datos, descripción completa y CTAs de
 * compra/consulta. El slug se valida contra `config.php['libros']`.
 *
 * @package MiLocalWeb\Clientes
 */

require_once __DIR__ . '/includes/bootstrap.php';

$slug = strtolower((string) ($_GET['slug'] ?? ''));
$slug = preg_replace('/[^a-z0-9-]/', '', $slug);

$libro = null;
foreach (($cliente['libros'] ?? []) as $candidato) {
    if (($candidato['slug'] ?? '') === $slug) {
        $libro = $candidato;
        break;
    }
}

$nav_base = '/';

// ─── 404 ────────────────────────────────────────────────────────
if ($libro === null) {
    http_response_code(404);

    $page_title       = 'Libro no encontrado — ' . $cliente['nombre'];
    $page_descripcion = 'La página que buscás no existe o el libro ya no está disponible.';

    require_once __DIR__ . '/includes/header.php';
    ?>
    <section class="section">
        <div class="container book-notfound">
            <p class="section-kicker">Error 404</p>
            <h1 class="section-title">Ese libro no existe</h1>
            <p class="section-lead">Puede que el enlace esté mal escrito o que el título ya no esté disponible.</p>
            <p class="book-page-actions">
                <a href="/#libros" class="btn btn--primary">Ver todos los libros</a>
            </p>
        </div>
    </section>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// ─── Datos de la página ─────────────────────────────────────────
$descripcion = libro_descripcion($libro);
$parrafos    = texto_parrafos($descripcion);
$amazon      = (string) ($cliente['amazon'][$slug] ?? '');
$url_libro   = '/libros/' . $slug;
$wa_hay      = wa_disponible($cliente);
$consultar   = wa_link($cliente, 'Hola! Quiero pedir mi copia de "' . $libro['titulo'] . '"');

$portada_full  = asset_existe((string) ($libro['portada'] ?? '')) ? (string) $libro['portada'] : '';
$portada_thumb = asset_existe((string) ($libro['portada_600'] ?? '')) ? (string) $libro['portada_600'] : $portada_full;
$alt           = 'Portada de ' . $libro['titulo'] . ' — ' . $cliente['nombre'];

$meta = [];
if (!empty($libro['paginas'])) {
    $meta[] = (int) $libro['paginas'] . ' páginas';
}
if (!empty($libro['anio'])) {
    $meta[] = (string) $libro['anio'];
}
if ($amazon !== '') {
    $meta[] = 'Disponible en Kindle';
}

$resumen_seo = $parrafos[0] ?? (string) ($libro['sinopsis'] ?? '');

// ─── Variables que consume header.php ───────────────────────────
$libro_actual     = $libro;
$libro_actual['descripcion'] = $descripcion;
$page_title       = $libro['titulo'] . ' — ' . $cliente['nombre'];
$page_descripcion = $resumen_seo;
$page_canonical   = 'https://' . ($_SERVER['HTTP_HOST'] ?? 'alejandrotinte.milocalweb.com.ar') . $url_libro;
$page_og_image    = $portada_full;

require_once __DIR__ . '/includes/header.php';
?>
<article class="book-page">
    <div class="container">
        <div class="book-topbar">
            <a href="/#libros" class="book-back">
                <?= svg_icon('chevron-left') ?>
                Volver a las obras
            </a>
        </div>

        <nav class="breadcrumb" aria-label="Migas de pan">
            <a href="/">Inicio</a>
            <span aria-hidden="true">/</span>
            <a href="/#libros">Libros</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page"><?= e($libro['titulo']) ?></span>
        </nav>

        <div class="book-hero">
            <div class="book-hero-cover">
                <?php if ($portada_thumb !== ''): ?>
                <a class="book-hero-cover-link"
                   href="<?= e($portada_full) ?>"
                   data-lightbox="<?= e($alt) ?>"
                   aria-label="Ampliar portada de <?= e($libro['titulo']) ?>">
                    <img src="<?= e($portada_thumb) ?>"
                         alt="<?= e($alt) ?>"
                         width="600" height="900"
                         loading="eager"
                         decoding="async">
                </a>
                <?php endif; ?>
            </div>

            <div class="book-hero-body">
                <p class="section-kicker">Libro</p>
                <h1 class="book-page-title"><?= e($libro['titulo']) ?></h1>

                <?php if (!empty($libro['subtitulo'])): ?>
                <p class="book-page-subtitle"><?= e($libro['subtitulo']) ?></p>
                <?php endif; ?>

                <?php if ($meta): ?>
                <p class="book-page-meta"><?= e(implode(' · ', $meta)) ?></p>
                <?php endif; ?>

                <?php if ($parrafos): ?>
                <div class="book-page-text">
                    <?php foreach ($parrafos as $parrafo): ?>
                    <p><?= e($parrafo) ?></p>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p class="book-page-text"><span class="pending">Sinopsis pendiente</span></p>
                <?php endif; ?>

                <div class="book-page-actions">
                    <a href="<?= e($consultar) ?>"
                       class="btn btn--whatsapp"
                       <?= $wa_hay ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                        <?= wa_icon() ?>
                        Pedir tu copia
                    </a>

                    <?php if ($amazon !== ''): ?>
                    <?php $amazon_logo = amazon_icon(); ?>
                    <a href="<?= e($amazon) ?>"
                       class="btn btn--amazon"
                       target="_blank"
                       rel="noopener noreferrer">
                        Conseguir en <?= $amazon_logo !== '' ? $amazon_logo : 'Amazon' ?>
                    </a>
                    <?php endif; ?>
                </div>

                <p class="book-page-back">
                    <a href="/#libros" class="book-back">
                        <?= svg_icon('chevron-left') ?>
                        Volver a las obras
                    </a>
                </p>
            </div>
        </div>
    </div>
</article>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
