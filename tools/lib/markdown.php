<?php
/**
 * Conversor Markdown mínimo para la generación de PDFs.
 *
 * Extraído de build-pdf.php para mantener el entrypoint corto.
 * No usa dependencias externas: solo expresiones regulares de PHP.
 *
 * @package MiLocalWeb\Clientes
 */

declare(strict_types=1);

/**
 * Aplica el formato inline de Markdown (código, links, negrita y énfasis)
 * sobre un texto ya escapado.
 *
 * @param string $texto Línea o celda de Markdown sin bloques.
 * @return string HTML inline seguro para insertar en el documento.
 */
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
 *
 * Soporta encabezados (h1-h4), párrafos, listas ordenadas y sin orden,
 * checkboxes, tablas, citas, código inline y separadores. No es un parser
 * completo: alcanza para los documentos internos de docs/.
 *
 * @param string $md Contenido Markdown completo.
 * @return string HTML con bloques ya cerrados.
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
// CSS embebido en el HTML temporal: define A4, saltos de página y tipografía
// de los PDFs. Es independiente de assets/css/ (esos estilos son para la web).

