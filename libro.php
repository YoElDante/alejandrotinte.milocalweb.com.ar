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

// El slug llega por query string desde router.php / .htaccess. Se normaliza a
// minúsculas y se descarta todo lo que no sea [a-z0-9-] para que no pueda
// usarse como ruta de archivo ni inyectar HTML en la comparación.
$slug = strtolower((string) ($_GET['slug'] ?? ''));
$slug = preg_replace('/[^a-z0-9-]/', '', $slug);

// La ficha se resuelve por comparación exacta contra config.php['libros'].
$libro = null;
foreach (($cliente['libros'] ?? []) as $candidato) {
    if (($candidato['slug'] ?? '') === $slug) {
        $libro = $candidato;
        break;
    }
}

$nav_base = '/';

// ─── 404 ────────────────────────────────────────────────────────
// Slug inválido o inexistente: se responde 404 real (no redirect) para no
// indexar URLs basura y se reutiliza el layout completo del sitio.
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
// CTA de compra: cada tienda se resuelve por slug y solo se muestra si el
// link existe. `$consultar` cae a #contacto cuando no hay WhatsApp cargado.
$descripcion = libro_descripcion($libro);
$parrafos    = texto_parrafos($descripcion);
$amazon      = (string) ($cliente['amazon'][$slug] ?? '');
$google_play = (string) ($cliente['google_play'][$slug] ?? '');
$url_libro   = '/libros/' . $slug;
$wa_hay      = wa_disponible($cliente);
$consultar   = wa_link($cliente, 'Hola! Quiero pedir mi copia de "' . $libro['titulo'] . '"');

// La portada de 600px se usa en pantalla; la grande solo como destino del
// lightbox y para og:image. Si falta la miniatura se cae a la imagen full.
$portada_full  = asset_existe((string) ($libro['portada'] ?? '')) ? (string) $libro['portada'] : '';
$portada_thumb = asset_existe((string) ($libro['portada_600'] ?? '')) ? (string) $libro['portada_600'] : $portada_full;
$alt           = 'Portada de ' . $libro['titulo'] . ' — ' . $cliente['nombre'];

// Línea de metadatos: solo se agregan los datos realmente cargados.
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
if ($google_play !== '') {
    $meta[] = 'Disponible en Google Play';
}

// ─── Variables que consume header.php ───────────────────────────
// `$libro_actual` activa el modo ficha: header.php genera el JSON-LD de Book
// + BreadcrumbList (en vez del catálogo completo) y ajusta canonical/OG.
$libro_actual     = $libro;
$libro_actual['descripcion'] = $descripcion;

// Title: "Título — Autor" entra en el límite de ~65 caracteres de Google;
// en títulos largos (antología) queda solo el título, que nunca se recorta.
$page_title = $libro['titulo'] . ' — ' . $cliente['nombre'];
if (mb_strlen($page_title) > 65) {
    $page_title = $libro['titulo'];
}

// Description: resumen recortado a 155 caracteres para que Google no lo
// trunque a mitad de palabra en resultados.
$page_descripcion = libro_resumen($libro, 155);

// Un primer párrafo corto deja una meta description pobre (ej. la antología,
// que abre con "Seis historias..."): se suman los dos primeros párrafos y se
// recorta con la misma lógica de límite de palabra.
if (mb_strlen($page_descripcion) < 70) {
    $parrafos_meta = texto_parrafos(libro_descripcion($libro));

    if (count($parrafos_meta) > 1) {
        $page_descripcion = libro_resumen(['sinopsis' => implode(' ', $parrafos_meta)], 155);
    }
}
$page_canonical   = 'https://' . ($cliente['dominio'] ?? 'alejandrotinte.milocalweb.com.ar') . $url_libro;
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

            <header class="book-hero-head">
                <p class="section-kicker">Libro</p>

                <?php if (!empty($libro['novedad'])): ?>
                <p class="book-flag book-flag--inline">Novedad</p>
                <?php endif; ?>

                <h1 class="book-page-title"><?= e($libro['titulo']) ?></h1>

                <?php if (!empty($libro['subtitulo'])): ?>
                <p class="book-page-subtitle"><?= e($libro['subtitulo']) ?></p>
                <?php endif; ?>

                <?php if ($meta): ?>
                <p class="book-page-meta"><?= e(implode(' · ', $meta)) ?></p>
                <?php endif; ?>
            </header>

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

                <?php if ($google_play !== ''): ?>
                <?php $google_logo = google_play_icon(); ?>
                <div class="book-page-actions-break">
                    <a href="<?= e($google_play) ?>"
                       class="btn btn--google"
                       target="_blank"
                       rel="noopener noreferrer">
                        <?= $google_logo !== '' ? $google_logo : '' ?>
                        Comprar en Google Play
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($cliente['libros_metodologia'])): ?>
            <p class="book-page-note"><?= e($cliente['libros_metodologia']) ?></p>
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

            <p class="book-page-back">
                <a href="/#libros" class="book-back">
                    <?= svg_icon('chevron-left') ?>
                    Volver a las obras
                </a>
            </p>
        </div>
    </div>

    <?php
    // Ilustración decorativa opcional por libro. La rotación llega como
    // variable CSS inline (--float-rotate); si es >= 45° se usa la variante
    // "side", que en desktop vuelve a enderezarse por CSS (ver 07-libro.css).
    $flotante = (string) ($cliente['libros_flotantes'][$slug] ?? '');
    $rotacion = (int) ($cliente['libros_flotantes_rotacion'][$slug] ?? 0);
    if ($flotante !== '' && asset_existe($flotante)):
        $float_clase = 'book-page-float' . (abs($rotacion) >= 45 ? ' book-page-float--side' : '');
    ?>
    <img class="<?= e($float_clase) ?>" src="<?= e($flotante) ?>"<?= img_dims_attrs($flotante) ?> alt="" aria-hidden="true" loading="lazy" decoding="async"<?= $rotacion !== 0 ? ' style="--float-rotate: ' . $rotacion . 'deg"' : '' ?>>
    <?php endif; ?>
</article>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
