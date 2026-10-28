<?php
/**
 * Cabecera de la landing page del cliente.
 *
 * Incluye metadatos, Open Graph, JSON-LD, inyección de colores
 * del cliente y la barra de navegación sticky.
 *
 * @package MiLocalWeb\Clientes
 */

$cs     = $cliente['colors'] ?? [];
$libros = $cliente['libros'] ?? [];

// Página de libro: si viene definida, header.php ajusta SEO, navegación y JSON-LD.
$libro_actual = $libro_actual ?? null;
$nav_base     = $nav_base ?? '';

// ─── Variables SEO ──────────────────────────────────────────
$seo_domain     = $_SERVER['HTTP_HOST'] ?? 'alejandrotinte.milocalweb.com.ar';
$seo_base_url   = 'https://' . $seo_domain;
$canonical_url  = $page_canonical ?? ($seo_base_url . '/');
$seo_keywords   = $cliente['seo_keywords_primarias'] ?? 'Libros y relatos';
$seo_title      = isset($page_title)
    ? e($page_title)
    : e($cliente['nombre']) . ' — ' . e($seo_keywords);
$seo_descripcion = isset($page_descripcion)
    ? e($page_descripcion)
    : e($cliente['seo_descripcion'] ?? $cliente['hero_descripcion'] ?? '');

$og_image_path = (string) ($cliente['seo_og_image'] ?? '');
$og_image_url  = !empty($page_og_image)
    ? $seo_base_url . $page_og_image
    : (asset_existe($og_image_path) ? $seo_base_url . $og_image_path : '');

$favicon = (string) ($cliente['favicon'] ?? '');
$favicon = asset_existe($favicon) ? $favicon : '';

// ─── JSON-LD Structured Data ────────────────────────────────
$ld_autor = [
    '@type'       => 'Person',
    '@id'         => $seo_base_url . '/#autor',
    'name'        => $cliente['nombre'],
    'jobTitle'    => $cliente['rubro'] ?? 'Escritor',
    'url'         => $seo_base_url . '/',
    'description' => strip_tags($seo_descripcion),
];

$ld_sameas = array_values(array_filter(
    array_map('strval', $cliente['redes'] ?? []),
    static function ($url) {
        return $url !== '';
    }
));
if ($ld_sameas) {
    $ld_autor['sameAs'] = $ld_sameas;
}
if (asset_existe((string) ($cliente['autor_foto'] ?? ''))) {
    $ld_autor['image'] = $seo_base_url . $cliente['autor_foto'];
}

$ld_graph = [$ld_autor];

if ($libro_actual) {
    // Ficha de libro: solo el Book de esta página + migas de pan.
    $book_url = $canonical_url;
    $book = [
        '@type'      => 'Book',
        '@id'        => $book_url . '#libro',
        'name'       => $libro_actual['titulo'],
        'author'     => ['@id' => $seo_base_url . '/#autor'],
        'inLanguage' => 'es-AR',
        'url'        => $book_url,
    ];

    if (!empty($libro_actual['paginas'])) {
        $book['numberOfPages'] = (int) $libro_actual['paginas'];
    }
    if (!empty($libro_actual['anio'])) {
        $book['datePublished'] = (string) $libro_actual['anio'];
    }
    if (!empty($libro_actual['descripcion'])) {
        $book['description'] = $libro_actual['descripcion'];
    }
    if (asset_existe((string) ($libro_actual['portada'] ?? ''))) {
        $book['image'] = $seo_base_url . $libro_actual['portada'];
    }

    $ld_graph[] = $book;

    $ld_graph[] = [
        '@type'           => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type'    => 'ListItem',
                'position' => 1,
                'name'     => 'Inicio',
                'item'     => $seo_base_url . '/',
            ],
            [
                '@type'    => 'ListItem',
                'position' => 2,
                'name'     => 'Libros',
                'item'     => $seo_base_url . '/#libros',
            ],
            [
                '@type'    => 'ListItem',
                'position' => 3,
                'name'     => $libro_actual['titulo'],
                'item'     => $book_url,
            ],
        ],
    ];
} else {
    foreach ($libros as $libro) {
        $book = [
            '@type'      => 'Book',
            '@id'        => $seo_base_url . '/#libro-' . $libro['slug'],
            'name'       => $libro['titulo'],
            'author'     => ['@id' => $seo_base_url . '/#autor'],
            'inLanguage' => 'es-AR',
            'url'        => $seo_base_url . '/libros/' . $libro['slug'],
        ];

        if (!empty($libro['paginas'])) {
            $book['numberOfPages'] = (int) $libro['paginas'];
        }
        if (!empty($libro['anio'])) {
            $book['datePublished'] = (string) $libro['anio'];
        }
        if (!empty($libro['sinopsis'])) {
            $book['description'] = $libro['sinopsis'];
        }
        if (asset_existe((string) ($libro['portada'] ?? ''))) {
            $book['image'] = $seo_base_url . $libro['portada'];
        }

        $ld_graph[] = $book;
    }
}

$ld_graph[] = [
    '@type'       => 'WebSite',
    '@id'         => $seo_base_url . '/#website',
    'url'         => $seo_base_url . '/',
    'name'        => $seo_title,
    'description' => $seo_descripcion,
    'inLanguage'  => 'es-AR',
    'author'      => [
        '@type'       => 'Organization',
        'name'        => 'MiLocalWeb',
        'url'         => 'https://milocalweb.com.ar',
        'telephone'   => '+5493513783473',
        'description' => 'Páginas web para negocios locales — Diseño, SEO y presencia digital',
    ],
];

$ld_json = [
    '@context' => 'https://schema.org',
    '@graph'   => $ld_graph,
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- ─── Metadatos básicos ──────────────────────────────────── -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="<?= e($page_robots ?? 'index, follow') ?>">
    <meta name="author" content="milocalweb.com.ar">
    <meta name="generator" content="MiLocalWeb">
    <meta name="theme-color" content="<?= e($cs['color-bg'] ?? '#14110F') ?>">
    <meta name="mobile-web-app-capable" content="yes">

    <!-- ─── SEO primario ──────────────────────────────────────── -->
    <title><?= $seo_title ?></title>
    <meta name="description" content="<?= $seo_descripcion ?>">
    <link rel="canonical" href="<?= $canonical_url ?>">

    <!-- ─── Open Graph ────────────────────────────────────────── -->
    <meta property="og:title" content="<?= $seo_title ?>">
    <meta property="og:description" content="<?= $seo_descripcion ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="es_AR">
    <meta property="og:url" content="<?= $canonical_url ?>">
    <meta property="og:site_name" content="<?= e($cliente['nombre']) ?>">
    <?php if ($og_image_url): ?>
    <meta property="og:image" content="<?= e($og_image_url) ?>">
    <?php if (empty($page_og_image)): ?>
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <?php endif; ?>
    <meta property="og:image:alt" content="<?= e($cliente['nombre'] . ' — ' . $seo_keywords) ?>">
    <?php endif; ?>

    <!-- ─── Twitter Card ──────────────────────────────────────── -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $seo_title ?>">
    <meta name="twitter:description" content="<?= $seo_descripcion ?>">
    <?php if ($og_image_url): ?>
    <meta name="twitter:image" content="<?= e($og_image_url) ?>">
    <?php endif; ?>

    <?php if ($favicon): ?>
    <link rel="icon" type="image/x-icon" href="<?= e($favicon) ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?= e($favicon) ?>">
    <?php endif; ?>

    <!-- JSON-LD Structured Data for AI / Search Engines -->
    <script type="application/ld+json">
    <?= json_encode($ld_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
    </script>

    <!-- Critical CSS inline: navbar + hero + variables base (above-the-fold) -->
    <style>
    <?= file_get_contents(__DIR__ . '/../assets/css/critical.css') ?>
    </style>

    <!-- CSS no crítico cargado de forma asíncrona (no bloquea render) -->
    <link rel="preload" href="<?= $css ?>styles.css?v=<?= $stylesVersion ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?= $css ?>styles.css?v=<?= $stylesVersion ?>"></noscript>

    <!-- Colores del cliente -->
    <?php if (!empty($cs)): ?>
    <style>
        :root {
            <?php foreach ($cs as $var => $val): ?>
            --<?= $var ?>: <?= $val ?>;
            <?php endforeach; ?>
        }
    </style>
    <?php endif; ?>

    <!-- Tipografía -->
    <style>
        :root {
            --font-heading: <?= $cliente['tipografia_titulos'] ?? "Georgia, serif" ?>;
            --font-body: <?= $cliente['tipografia_texto'] ?? "system-ui, sans-serif" ?>;
        }
    </style>
</head>
<body>
    <a href="#contenido" class="skip-link">Saltar al contenido</a>

    <header class="site-header">
        <nav class="navbar" role="navigation" aria-label="Navegación principal">
            <div class="navbar-brand<?= asset_existe((string) ($cliente['logo_img'] ?? '')) ? ' has-logo' : '' ?>">
                <a href="<?= $nav_base ?>#inicio" class="brand-link">
                    <?php if (asset_existe((string) ($cliente['logo_img'] ?? ''))): ?>
                    <img src="<?= e($cliente['logo_img']) ?>"
                         alt="Logo <?= e($cliente['nombre']) ?>"
                         class="brand-logo"
                         loading="eager"
                         decoding="async"
                         width="300" height="300">
                    <?php endif; ?>
                    <span class="brand-name"><?= e($cliente['nombre']) ?></span>
                </a>
            </div>

            <button class="navbar-toggle" aria-label="Abrir menú" aria-expanded="false" aria-controls="menu-principal">
                <span class="toggle-bar"></span>
                <span class="toggle-bar"></span>
                <span class="toggle-bar"></span>
            </button>

            <ul class="navbar-menu" id="menu-principal">
                <li><a href="<?= $nav_base ?>#inicio">Inicio</a></li>
                <li><a href="<?= $nav_base ?>#libros">Libros</a></li>
                <li><a href="<?= $nav_base ?>#proyectos">Proyectos</a></li>
                <li><a href="<?= $nav_base ?>#autor">El autor</a></li>
                <li><a href="<?= $nav_base ?>#contacto">Contacto</a></li>
                <li>
                    <a href="<?= e(wa_link($cliente)) ?>"
                       class="nav-cta"
                       <?= wa_disponible($cliente) ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                        Escribinos
                        <?php include $svg . 'whatsapp.svg'; ?>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="navbar-overlay" aria-hidden="true"></div>
    </header>
    <main class="site-main" id="contenido">
