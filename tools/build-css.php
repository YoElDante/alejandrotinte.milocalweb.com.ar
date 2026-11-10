<?php
/**
 * Build script — CSS bundle.
 *
 * Combina los archivos CSS modulares en un único `assets/css/styles.css`
 * para producción. Se corre manualmente o vía hook de deploy cada vez que
 * se modifique un archivo CSS fuente.
 *
 * Uso:
 *   php tools/build-css.php
 *
 * El bundle resultante se carga de forma asíncrona en el navegador;
 * el CSS crítico above-the-fold va inline en `includes/header.php` desde
 * `assets/css/critical.css`.
 *
 * @package MiLocalWeb\Clientes
 */

$baseDir = __DIR__ . '/..';
$cssDir  = $baseDir . '/assets/css';
$outFile = $cssDir . '/styles.css';

$sourceFiles = [
    'base.css',
    'navbar.css',
    'hero.css',
    // La antigua sección monolítica se divide por bloque temático.
    // El orden refleja el orden original de la cascada.
    'sections/01-destacado.css',
    'sections/02-libros.css',
    'sections/03-lightbox.css',
    'sections/04-proyectos.css',
    'sections/05-autor.css',
    'sections/06-contacto.css',
    'sections/07-libro.css',
    'sections/08-animaciones.css',
    'aside.css',
    'clientes.css',
    'footer.css',
    // Responsive dividido por breakpoint para mantener archivos legibles.
    'responsive/01-tablet.css',
    'responsive/02-mobile.css',
];

$css = [];
foreach ($sourceFiles as $file) {
    $path = $cssDir . '/' . $file;
    if (!is_file($path)) {
        fwrite(STDERR, "Advertencia: no se encontró {$path}\n");
        continue;
    }
    $css[] = "/* === {$file} === */";
    $css[] = file_get_contents($path);
}

$bundle = implode("\n", $css);

// Minificación conservadora: elimina comentarios /* */ y espacios redundantes.
// No toca comentarios dentro de strings/comillas porque en CSS estándar no se usan.
// Consecuencia útil: agregar solo comentarios a los parciales NO cambia el hash
// del bundle; el hash solo se mueve si cambia una regla o un valor.
$bundle = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!s', '', $bundle);
$bundle = preg_replace('/\s+/', ' ', $bundle);
$bundle = trim($bundle);

if (file_put_contents($outFile, $bundle) === false) {
    fwrite(STDERR, "Error: no se pudo escribir {$outFile}\n");
    exit(1);
}

$size = filesize($outFile);
echo "✓ Bundle generado: assets/css/styles.css (" . number_format($size) . " bytes)\n";
echo "  Versión/cache-bust: ?v=" . filemtime($outFile) . "\n";
