<?php
/**
 * Build del sitemap.
 *
 * Genera `sitemap.xml` con la home y una URL por libro, incluyendo la portada
 * como imagen cuando el archivo existe. Se corre manualmente al publicar:
 *
 *   php tools/build-sitemap.php
 *
 * @package MiLocalWeb\Clientes
 */

$baseDir = __DIR__ . '/..';
$cliente = require $baseDir . '/config.php';

$dominio = rtrim((string) ($cliente['dominio'] ?? 'alejandrotinte.milocalweb.com.ar'), '/');
$base    = 'https://' . $dominio;
$hoy     = date('Y-m-d');

$urls = [
    [
        'loc'        => $base . '/',
        'priority'   => '1.0',
        'changefreq' => 'monthly',
    ],
];

foreach (($cliente['libros'] ?? []) as $libro) {
    $entrada = [
        'loc'        => $base . '/libros/' . $libro['slug'],
        'priority'   => '0.8',
        'changefreq' => 'monthly',
    ];

    $portada = (string) ($libro['portada_600'] ?? '');
    if ($portada !== '' && is_file($baseDir . $portada)) {
        $entrada['imagen'] = $base . $portada;
        $entrada['imagen_titulo'] = $libro['titulo'];
    }

    $urls[] = $entrada;
}

$xml  = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . PHP_EOL;
$xml .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . PHP_EOL;

foreach ($urls as $url) {
    $xml .= '    <url>' . PHP_EOL;
    $xml .= '        <loc>' . htmlspecialchars($url['loc'], ENT_XML1) . '</loc>' . PHP_EOL;
    $xml .= '        <lastmod>' . $hoy . '</lastmod>' . PHP_EOL;
    $xml .= '        <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
    $xml .= '        <priority>' . $url['priority'] . '</priority>' . PHP_EOL;

    if (!empty($url['imagen'])) {
        $xml .= '        <image:image>' . PHP_EOL;
        $xml .= '            <image:loc>' . htmlspecialchars($url['imagen'], ENT_XML1) . '</image:loc>' . PHP_EOL;
        $xml .= '            <image:title>' . htmlspecialchars((string) $url['imagen_titulo'], ENT_XML1) . '</image:title>' . PHP_EOL;
        $xml .= '        </image:image>' . PHP_EOL;
    }

    $xml .= '    </url>' . PHP_EOL;
}

$xml .= '</urlset>' . PHP_EOL;

$destino = $baseDir . '/sitemap.xml';
if (file_put_contents($destino, $xml) === false) {
    fwrite(STDERR, "Error: no se pudo escribir {$destino}\n");
    exit(1);
}

echo '✓ sitemap.xml generado con ' . count($urls) . " URLs (" . number_format(strlen($xml)) . " bytes)\n";
