<?php
/**
 * Helpers compartidos por la landing.
 *
 * Funciones puras de apoyo para: escape de salida, links de WhatsApp,
 * lectura de assets/descripciones y armado de resúmenes. No dependen de
 * sesión ni de estado global; reciben `$cliente` o rutas por parámetro.
 *
 * @package MiLocalWeb\Clientes
 */

/**
 * Escapa un valor para imprimirlo dentro de HTML.
 *
 * @param mixed $value Valor a escapar (se castea a string).
 * @return string Texto seguro para atributos y contenido HTML.
 */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/**
 * Número de WhatsApp del cliente, solo dígitos.
 *
 * @param array $cliente Configuración del cliente.
 * @return string Número listo para `wa.me` (sin +, espacios ni guiones).
 */
function wa_number(array $cliente): string
{
    return preg_replace('/\D+/', '', (string) ($cliente['whatsapp'] ?? ''));
}

/**
 * Indica si el cliente ya tiene un número de WhatsApp cargado.
 *
 * Mientras `whatsapp` esté vacío en config.php los CTAs apuntan a #contacto
 * en lugar de generar links rotos a wa.me.
 *
 * @param array $cliente Configuración del cliente.
 * @return bool True si hay un número cargado.
 */
function wa_disponible(array $cliente): bool
{
    return wa_number($cliente) !== '';
}

/**
 * Link de WhatsApp del cliente con mensaje pre-cargado.
 *
 * @param array       $cliente Configuración del cliente.
 * @param string|null $mensaje Texto alternativo; si es null usa el de config.
 * @return string URL de wa.me o `#contacto` si no hay número disponible.
 */
function wa_link(array $cliente, ?string $mensaje = null): string
{
    if (!wa_disponible($cliente)) {
        return '#contacto';
    }

    $texto = $mensaje ?? (string) ($cliente['whatsapp_mensaje'] ?? 'Hola! Vi tu web y quisiera más info');

    return 'https://wa.me/' . wa_number($cliente) . '?text=' . urlencode($texto);
}

/**
 * Link de WhatsApp de MiLocalWeb (badge y aside publicitario).
 *
 * @param string $mensaje Mensaje pre-cargado para el equipo de MiLocalWeb.
 * @return string URL de wa.me.
 */
function mlw_wa_link(string $mensaje): string
{
    return 'https://wa.me/5493513783473?text=' . urlencode($mensaje);
}

/**
 * Devuelve el markup de un icono SVG de `assets/img/svg/` (sin extensión).
 *
 * Sirve para imprimir iconos inline con `currentColor` sin depender de la
 * variable $svg del scope. Devuelve '' si el archivo no existe.
 *
 * El nombre se usa para armar una ruta fija dentro de assets/img/svg/; no
 * admite rutas externas. El cache estático evita releer el mismo archivo
 * varias veces en una misma request.
 *
 * @param string $nombre Nombre del icono sin extensión (ej. "whatsapp").
 * @return string Markup SVG o cadena vacía.
 */
function svg_icon(string $nombre): string
{
    static $cache = [];

    if (!array_key_exists($nombre, $cache)) {
        $ruta = __DIR__ . '/../assets/img/svg/' . $nombre . '.svg';
        $cache[$nombre] = is_file($ruta) ? trim((string) file_get_contents($ruta)) : '';
    }

    return $cache[$nombre];
}

/**
 * Redes oficiales del cliente (cuentas profesionales del escritor).
 *
 * Si `redes_oficiales` está vacío, cae a `redes` para que el sitio siga
 * mostrando los enlaces disponibles mientras se cargan las cuentas nuevas.
 *
 * @param array $cliente Configuración del cliente.
 * @return array<string, string> Red -> URL, sin valores vacíos.
 */
function redes_oficiales(array $cliente): array
{
    $limpiar = static function (array $redes): array {
        return array_filter($redes, static function ($url) {
            return trim((string) $url) !== '';
        });
    };

    $oficiales = $limpiar($cliente['redes_oficiales'] ?? []);

    return $oficiales !== [] ? $oficiales : $limpiar($cliente['redes'] ?? []);
}

/**
 * Todas las redes del autor: primero las oficiales y después las personales.
 *
 * Devuelve una lista plana de URLs sin duplicados, pensada para el JSON-LD
 * (`sameAs`). El orden importa: las cuentas oficiales van primero.
 *
 * @param array $cliente Configuración del cliente.
 * @return string[] URLs únicas en orden de prioridad.
 */
function redes_todas(array $cliente): array
{
    $urls = [];

    foreach ([redes_oficiales($cliente), $cliente['redes'] ?? []] as $grupo) {
        foreach ($grupo as $url) {
            $url = trim((string) $url);

            if ($url !== '' && !in_array($url, $urls, true)) {
                $urls[] = $url;
            }
        }
    }

    return $urls;
}

/**
 * Icono SVG de WhatsApp.
 */
function wa_icon(): string
{
    return svg_icon('whatsapp');
}

/**
 * Logo de Amazon para el botón de compra.
 */
function amazon_icon(): string
{
    return svg_icon('amazon');
}

/**
 * Logo de Google Play para el botón de compra.
 */
function google_play_icon(): string
{
    return svg_icon('google-play');
}

/**
 * Icono de Mercado Pago para el botón de donación.
 */
function mercadopago_icon(): string
{
    return svg_icon('mercadopago');
}

/**
 * Verifica que un asset declarado en config.php exista en disco.
 * Las rutas de config.php son relativas a la raíz del sitio.
 *
 * @param string $ruta Ruta pública (ej. "/assets/img/foo.webp").
 * @return bool True si el archivo existe dentro del proyecto.
 */
function asset_existe(string $ruta): bool
{
    return $ruta !== '' && is_file(__DIR__ . '/..' . $ruta);
}

/**
 * Lee la descripción larga de un libro desde `productos/{slug}/descripcion.txt`.
 *
 * El texto se mantiene en un archivo por libro para no inflar config.php con
 * sinopsis extensas. Devuelve '' si el archivo no existe o el slug no es válido.
 *
 * El slug se valida con una allowlist (`[a-z0-9-]`) antes de armar la ruta:
 * así un valor manipulado no puede escapar de productos/ con "../".
 *
 * @param array $libro Datos del libro (se usa `slug`).
 * @return string Texto completo normalizado a saltos de línea LF.
 */
function libro_descripcion(array $libro): string
{
    static $cache = [];

    $slug = (string) ($libro['slug'] ?? '');
    if ($slug === '' || !preg_match('/^[a-z0-9-]+$/', $slug)) {
        return '';
    }

    if (array_key_exists($slug, $cache)) {
        return $cache[$slug];
    }

    $ruta = __DIR__ . '/../productos/' . $slug . '/descripcion.txt';
    $texto = is_file($ruta) ? (string) file_get_contents($ruta) : '';

    $texto = trim(str_replace(["\r\n", "\r"], "\n", $texto));

    return $cache[$slug] = $texto;
}

/**
 * Divide un texto en párrafos, descartando líneas vacías.
 *
 * @param string $texto Texto plano con párrafos separados por línea en blanco.
 * @return string[] Lista de párrafos ya recortados.
 */
function texto_parrafos(string $texto): array
{
    $partes = preg_split('/\n{2,}/', trim($texto)) ?: [];
    $partes = array_map('trim', $partes);

    return array_values(array_filter($partes, static function ($p) {
        return $p !== '';
    }));
}

/**
 * Resumen de un libro para la tarjeta de la grilla.
 *
 * Prioriza `sinopsis` de config.php. Si está vacío, usa el primer párrafo de
 * `descripcion.txt` y lo recorta en un límite de palabra.
 *
 * El recorte busca el último espacio antes del límite para no cortar palabras
 * y limpia puntuación final antes de agregar la elipsis.
 *
 * @param array $libro Datos del libro.
 * @param int   $max   Cantidad máxima de caracteres (por defecto 260).
 * @return string Resumen listo para imprimir.
 */
function libro_resumen(array $libro, int $max = 260): string
{
    $resumen = trim((string) ($libro['sinopsis'] ?? ''));

    if ($resumen === '') {
        $parrafos = texto_parrafos(libro_descripcion($libro));
        $resumen  = $parrafos[0] ?? '';
    }

    if ($resumen === '' || mb_strlen($resumen) <= $max) {
        return $resumen;
    }

    $corte = mb_substr($resumen, 0, $max);
    $ultimoEspacio = mb_strrpos($corte, ' ');

    if ($ultimoEspacio !== false) {
        $corte = mb_substr($corte, 0, $ultimoEspacio);
    }

    return rtrim($corte, " \t\n\r\0\x0B.,;:") . '…';
}
