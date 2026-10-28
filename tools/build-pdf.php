<?php
/**
 * Genera PDFs operativos a partir de los .md de docs/.
 *
 * Uso:
 *   php tools/build-pdf.php            → genera todos
 *   php tools/build-pdf.php cliente    → solo el perfil del cliente
 *   php tools/build-pdf.php redes      → solo el brief de redes
 *
 * Requiere Chrome o Edge instalado (modo headless).
 *
 * @package MiLocalWeb\Clientes
 */

declare(strict_types=1);

// ─── Documentos a generar ───────────────────────────────────────
$builds = [
    'cliente' => [
        'salida' => 'perfil-cliente-alejandro-tinte',
        'docs'   => ['ficha-cliente.md', 'perfil-autor.md'],
    ],
    'redes' => [
        'salida' => 'brief-redes-alejandro-tinte',
        'docs'   => ['brief-redes.md'],
    ],
];

$pedido = $argv[1] ?? '';
if ($pedido !== '') {
    if (!isset($builds[$pedido])) {
        fwrite(STDERR, "Documento desconocido: {$pedido}\n");
        fwrite(STDERR, "Opciones: " . implode(', ', array_keys($builds)) . "\n");
        exit(1);
    }
    $builds = [$pedido => $builds[$pedido]];
}

// ─── Conversor Markdown mínimo ──────────────────────────────────

function md_inline(string $texto): string
{
    $t = htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');

    $t = preg_replace('/`([^`]+)`/', '<code>$1</code>', $t);

    $t = preg_replace_callback('/\[([^\]]+)\]\(([^)]+)\)/', static function ($m) {
        return '<a href="' . $m[2] . '">' . $m[1] . '</a>';
    }, $t);

    $t = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $t);
    $t = preg_replace('/(?<!\*)\*([^*\n]+)\*(?!\*)/', '<em>$1</em>', $t);

    return $t;
}

/**
 * Convierte un bloque de Markdown al HTML que usan los PDFs.
 */
function md_a_html(string $md): string
{
    $lineas  = preg_split('/\r\n|\r|\n/', $md) ?: [];
    $total   = count($lineas);
    $html    = '';
    $enLista = false;
    $tipoLista = 'ul';
    $i       = 0;

    $cerrarLista = static function () use (&$enLista, &$html, &$tipoLista): void {
        if ($enLista) {
            $html .= '</' . $tipoLista . ">\n";
            $enLista = false;
        }
    };

    while ($i < $total) {
        $linea = rtrim($lineas[$i]);

        if (trim($linea) === '') {
            $cerrarLista();
            $i++;
            continue;
        }

        if (preg_match('/^\s*---+\s*$/', $linea)) {
            $cerrarLista();
            $html .= "<hr>\n";
            $i++;
            continue;
        }

        if (preg_match('/^(#{1,4})\s+(.*)$/', $linea, $m)) {
            $cerrarLista();
            $nivel = strlen($m[1]);
            $html .= '<h' . $nivel . '>' . md_inline(trim($m[2])) . '</h' . $nivel . ">\n";
            $i++;
            continue;
        }

        // Tablas
        if (str_starts_with(trim($linea), '|')) {
            $cerrarLista();
            $filas = [];
            while ($i < $total && str_starts_with(trim(rtrim($lineas[$i])), '|')) {
                $filas[] = trim(rtrim($lineas[$i]));
                $i++;
            }

            $html .= "<table>\n";
            $encabezado = true;
            foreach ($filas as $fila) {
                if (preg_match('/^\|[\s:\-|]+\|$/', $fila)) {
                    continue;
                }

                $celdas = array_map('trim', explode('|', trim($fila, '|')));
                $tag    = $encabezado ? 'th' : 'td';
                $html  .= '<tr>';
                foreach ($celdas as $celda) {
                    $html .= '<' . $tag . '>' . md_inline($celda) . '</' . $tag . '>';
                }
                $html .= "</tr>\n";
                $encabezado = false;
            }
            $html .= "</table>\n";
            continue;
        }

        // Citas
        if (preg_match('/^>\s?(.*)$/', $linea, $m)) {
            $cerrarLista();
            $parrafos = [];
            while ($i < $total && preg_match('/^>\s?(.*)$/', rtrim($lineas[$i]), $mm)) {
                $texto = trim($mm[1]);
                if ($texto === '') {
                    $parrafos[] = '';
                } elseif ($parrafos && end($parrafos) !== '') {
                    $parrafos[count($parrafos) - 1] .= ' ' . $texto;
                } else {
                    $parrafos[] = $texto;
                }
                $i++;
            }

            $html .= "<blockquote>\n";
            foreach ($parrafos as $p) {
                if ($p !== '') {
                    $html .= '<p>' . md_inline($p) . "</p>\n";
                }
            }
            $html .= "</blockquote>\n";
            continue;
        }

        // Listas (con o sin checkbox)
        if (preg_match('/^\s*([-*]|\d+\.)\s+(.*)$/', $linea, $m)) {
            $ordenada  = (bool) preg_match('/^\d+\.$/', $m[1]);
            $tagLista  = $ordenada ? 'ol' : 'ul';
            $contenido = $m[2];
            $i++;

            // Líneas de continuación: indentadas 2+ espacios y que no abren otro ítem.
            while ($i < $total) {
                $sig = $lineas[$i];
                if (trim($sig) === '' || !preg_match('/^\s{2,}\S/', $sig)) {
                    break;
                }
                if (preg_match('/^\s*([-*]|\d+\.)\s+/', $sig)) {
                    break;
                }
                $contenido .= ' ' . trim($sig);
                $i++;
            }

            if ($enLista && $tipoLista !== $tagLista) {
                $cerrarLista();
            }

            if (!$enLista) {
                $html .= '<' . $tagLista . ">\n";
                $enLista   = true;
                $tipoLista = $tagLista;
            }

            if (preg_match('/^\[( |x|X)\]\s+(.*)$/', $contenido, $chk)) {
                $marca = strtolower($chk[1]) === 'x' ? '☑' : '☐';
                $html .= '<li class="task"><span class="checkbox">' . $marca . '</span> ' . md_inline($chk[2]) . "</li>\n";
            } else {
                $html .= '<li>' . md_inline($contenido) . "</li>\n";
            }
            continue;
        }

        // Párrafo
        $cerrarLista();
        $parrafo = [$linea];
        $i++;
        while ($i < $total) {
            $siguiente = rtrim($lineas[$i]);
            if (trim($siguiente) === ''
                || preg_match('/^(#{1,4})\s+/', $siguiente)
                || str_starts_with(trim($siguiente), '|')
                || preg_match('/^>\s?/', $siguiente)
                || preg_match('/^\s*([-*]|\d+\.)\s+/', $siguiente)
                || preg_match('/^\s*---+\s*$/', $siguiente)) {
                break;
            }
            $parrafo[] = $siguiente;
            $i++;
        }
        $html .= '<p>' . md_inline(implode(' ', $parrafo)) . "</p>\n";
    }

    $cerrarLista();
    return $html;
}

// ─── Estilos de impresión ──────────────────────────────────────

$css = <<<'CSS'
@page { size: A4; margin: 16mm 15mm 17mm; }

* { box-sizing: border-box; }

body {
  margin: 0;
  font-family: "Segoe UI", system-ui, -apple-system, Arial, sans-serif;
  font-size: 10pt;
  line-height: 1.5;
  color: #1c1c1c;
  background: #fff;
}

a { color: #8a6a12; text-decoration: none; word-break: break-word; }

h1, h2, h3, h4 {
  font-family: Georgia, "Times New Roman", serif;
  font-weight: 400;
  line-height: 1.2;
  color: #111;
  break-after: avoid;
}

h1 {
  font-size: 21pt;
  margin: 0 0 0.35em;
  padding-bottom: 0.25em;
  border-bottom: 2px solid #c9a227;
}

h2 {
  font-size: 14.5pt;
  margin: 1.5em 0 0.4em;
  padding-bottom: 0.15em;
  border-bottom: 1px solid #e2dccb;
}

h3 { font-size: 11.5pt; margin: 1.2em 0 0.35em; }
h4 { font-size: 10.5pt; margin: 1em 0 0.3em; }

p { margin: 0 0 0.6em; }

ul { margin: 0 0 0.8em; padding-left: 1.3em; }
li { margin-bottom: 0.25em; }
li.task { list-style: none; margin-left: -1.1em; }
.checkbox { color: #8a6a12; font-weight: bold; }

blockquote {
  margin: 0.9em 0;
  padding: 0.6em 0.9em;
  background: #faf7ef;
  border-left: 3px solid #c9a227;
  break-inside: avoid;
}
blockquote p { margin: 0 0 0.35em; }
blockquote p:last-child { margin-bottom: 0; }

code {
  font-family: "Cascadia Mono", Consolas, monospace;
  font-size: 8.7pt;
  background: #f2f0ea;
  padding: 1px 4px;
  border-radius: 2px;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin: 0.5em 0 1em;
  font-size: 8.8pt;
}
th, td {
  border: 1px solid #ddd8cb;
  padding: 3px 6px;
  text-align: left;
  vertical-align: top;
}
th { background: #f5f1e6; font-weight: 600; }
tr { break-inside: avoid; }

hr { border: 0; border-top: 1px solid #e6e2d6; margin: 1.5em 0; }

.runhead {
  font-size: 7.5pt;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: #8a8377;
  border-bottom: 1px solid #e6e2d6;
  padding-bottom: 0.5rem;
  margin-bottom: 1.4rem;
}

.doc + .doc { break-before: page; }
CSS;

// ─── Render ────────────────────────────────────────────────────

$raiz  = dirname(__DIR__);
$fecha = date('d/m/Y');

// Los documentos del cliente viven en _local/ (fuera del repo). Si todavía
// están en docs/, también se leen de ahí.
$docsDir = is_dir($raiz . '/_local/docs') ? $raiz . '/_local/docs' : $raiz . '/docs';

$navegadores = [
    'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
    'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
    'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe',
    'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
];

$navegador = null;
foreach ($navegadores as $candidato) {
    if (is_file($candidato)) {
        $navegador = $candidato;
        break;
    }
}

if ($navegador === null) {
    fwrite(STDERR, "No se encontró Chrome ni Edge para imprimir el PDF.\n");
    exit(1);
}

foreach ($builds as $clave => $build) {
    $secciones = '';

    foreach ($build['docs'] as $archivo) {
        $ruta = $docsDir . '/' . $archivo;
        if (!is_file($ruta)) {
            fwrite(STDERR, "Falta el archivo: {$archivo}\n");
            continue 2;
        }

        $secciones .= '<section class="doc">' . "\n";
        $secciones .= md_a_html((string) file_get_contents($ruta));
        $secciones .= "</section>\n";
    }

    $html = '<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Alejandro Tinte</title>
<style>
' . $css . '
</style>
</head>
<body>
<div class="runhead">MiLocalWeb · Documento interno · ' . $fecha . '</div>
' . $secciones . '
</body>
</html>';

    $htmlPath = $docsDir . '/' . $build['salida'] . '.html';
    $pdfPath  = $docsDir . '/' . $build['salida'] . '.pdf';

    file_put_contents($htmlPath, $html);

    $url     = 'file:///' . str_replace('\\', '/', $htmlPath);
    $comando = sprintf(
        '"%s" --headless --disable-gpu --no-pdf-header-footer --print-to-pdf="%s" "%s" 2>&1',
        $navegador,
        str_replace('/', '\\', $pdfPath),
        $url
    );

    exec($comando, $salida, $codigo);

    if (!is_file($pdfPath)) {
        fwrite(STDERR, "No se pudo generar {$build['salida']}.pdf\n" . implode("\n", $salida) . "\n");
        continue;
    }

    $kb = (int) round(filesize($pdfPath) / 1024);
    echo "✓ docs/{$build['salida']}.pdf ({$kb} KB)\n";
}
