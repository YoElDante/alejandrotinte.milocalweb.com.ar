<?php
/**
 * Helpers compartidos por la landing.
 *
 * @package MiLocalWeb\Clientes
 */

/**
 * Escapa un valor para imprimirlo dentro de HTML.
 */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/**
 * Número de WhatsApp del cliente, solo dígitos.
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
 */
function wa_disponible(array $cliente): bool
{
    return wa_number($cliente) !== '';
}

/**
 * Link de WhatsApp del cliente con mensaje pre-cargado.
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
 * Icono de Mercado Pago para el botón de donación.
 */
function mercadopago_icon(): string
{
    return svg_icon('mercadopago');
}

/**
 * Verifica que un asset declarado en config.php exista en disco.
 * Las rutas de config.php son relativas a la raíz del sitio.
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
 * @return string[]
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
