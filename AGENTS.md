# AGENTS.md — MiLocalWeb Landing Page

> Instrucciones para asistentes de código. Leer esto ANTES de cualquier acción.
> Este archivo es la guía canónica para todo proyecto de landing page.
>
> **Skills disponibles**: `milocalweb-landing` — convenciones técnicas, assets, mapeo config.php, checklist.
>
> **Proyecto de referencia**: `eznutrifit.milocalweb.com.ar` (auditado 12/08/2026, score 83%).

---

## 1. Contexto del Negocio

MiLocalWeb (milocalweb.com.ar) es un servicio de marketing digital para negocios locales.
Una landing page optimizada para SEO es **uno de los productos del paquete**.

**Modelo de negocio:**
- Cada cliente recibe una landing page en un subdominio (`cliente.milocalweb.com.ar`)
- La landing funciona como hub central: indexa en Google, linkea redes sociales,
  Google Business Profile, y canaliza consultas a WhatsApp
- La página NO es e-commerce — es vitrina, captación de leads y presencia digital

**Monetización para MiLocalWeb:**
- La landing incluye elementos publicitarios propios (footer, aside) que promocionan
  el servicio de MiLocalWeb y generan tráfico hacia milocalweb.com.ar
- Se puede incluir una sección discreta de "Otros Clientes" como vidriera del portfolio

---

## 2. Arquitectura del Template

```
{cliente}.milocalweb.com.ar/
├── index.php                  ← Entry point. Arma la página completa.
├── config.php                 ← ÚNICA fuente de datos del sitio.
├── .htaccess                  ← Seguridad, caché, HTTPS forzado.
├── robots.txt                 ← Indexación y sitemap.
├── sitemap.xml                ← Sitemap con imágenes y geo.
├── README.md                  ← Para humanos.
├── assets/
│   ├── css/                   ← Estilos divididos por componente.
│   ├── js/                    ← JS vanilla por funcionalidad.
│   ├── fonts/                 ← Fuentes auto-hosteadas (woff2).
│   ├── img/
│   │   ├── cliente/           ← Imágenes del negocio (logos, productos, fotos).
│   │   │   ├── iconos/        ← favicon.ico y otros iconos.
│   │   │   ├── identidad/     ← Hero bg, texturas, fotos de identidad.
│   │   │   ├── impacto/       ← Producto estrella y complementos.
│   │   │   ├── logos/         ← Logo en varias resoluciones + OG image.
│   │   │   └── productos/     ← Fotos de productos destacados.
│   │   ├── milocalweb/        ← Assets de MiLocalWeb (NO modificar).
│   │   ├── svg/               ← Iconos SVG inline.
│   │   └── terceros/          ← Logos de puntos de venta.
│   └── vid/
│       └── reels/             ← Videos educativos (mp4 + posters webp).
├── includes/
│   ├── header.php             ← <head> completo + navbar + inicio <main>.
│   ├── footer.php             ← Footer estándar MiLocalWeb + WhatsApp float.
│   ├── secrets.php            ← API keys (NO versionado).
│   └── sections/
│       ├── hero.php           ← Hero section (4 layouts configurables).
│       ├── productos.php      ← Hasta 3 productos destacados.
│       ├── estrella.php       ← Producto estrella showcase (hardcodeado).
│       ├── ubicacion.php      ← Mapa, dirección, horarios, estrellas.
│       ├── reels.php          ← Videos educativos (hardcodeado).
│       ├── nosotros.php       ← Quiénes somos, galería, redes, CTA final.
│       ├── aside.php          ← Aside publicitario MiLocalWeb.
│       └── clientes.php       ← Sección "Otros Clientes".
├── docs/
│   ├── guia-relevamiento.md   ← Template que completa la persona en campo.
│   ├── ficha-cliente.md       ← Documento canónico del negocio (generado por IA).
│   ├── informe_estilo_{cliente}.md ← Identidad visual (colores, tipografías).
│   └── {cliente}.cliente.md   ← Datos crudos del relevamiento (ARCHIVADO).
├── tools/
│   └── build-css.php          ← Build script: combina CSS modulares.
└── skills/
    └── milocalweb-landing/    ← Skill: convenciones, assets, mapeo, checklist.
```

**Principio fundamental:** `config.php` es la fuente única de verdad.
Toda la landing se genera a partir de ese array. Si algo no está en `config.php`,
no aparece en la página.

---

## 3. Flujo de Trabajo para un Nuevo Cliente

### Fase 1 — Relevamiento (persona en campo)

1. Copiar la estructura base del template `_template2/` al proyecto del cliente
2. Completar `docs/guia-relevamiento.md` con datos del cliente
3. Si el cliente tiene redes sociales activas, generar `docs/informe_estilo_{cliente}.md`
   usando IA para analizar su identidad visual
4. Recolectar y guardar imágenes en `assets/img/cliente/`
5. Recolectar videos en `assets/vid/reels/` (si aplica)
6. Renombrar assets a la convención (minúsculas, guiones, prefijos)
7. Eliminar archivos `.txt` de carpetas de assets

### Fase 2 — Generación (IA)

> **Cargar skill `milocalweb-landing`** para el procedimiento detallado.

1. Leer `docs/guia-relevamiento.md` + `docs/informe_estilo_{cliente}.md`
2. Generar `docs/ficha-cliente.md`
3. Llenar `config.php` con el mapeo del skill
4. Editar `estrella.php` y/o `reels.php` si el cliente tiene esos contenidos
5. Marcar `{cliente}.cliente.md` como ARCHIVADO
6. Pasar la checklist del skill

### Datos que NO van en config.php (hardcodeados en PHP)
- **Producto estrella** → `includes/sections/estrella.php`
- **Videos / Reels** → `includes/sections/reels.php`

### Datos que SÍ van en config.php (lección de auditoría)
- **`founder_name`** — nombre del dueño para JSON-LD (antes hardcodeado)
- **`founder_title`** — cargo del dueño

---

## 4. Estrategia SEO

Elementos clave:
- **Title tag:** `{nombre} — {keywords} en {localidad}`
- **Meta description:** nombres reales de productos + ubicación (NO usar mb_strtolower)
- **H1:** keywords + localidad (SIN slogan)
- **H2:** títulos de sección naturales (no keyword stuffing)
- **H3:** subtítulos
- **Open Graph:** title, description, type=website, locale=es_AR, image 1200x630
- **robots:** index, follow
- **Alt text:** `{nombre} — {descripción} en {localidad}` en todas las imágenes
- **Geo tags:** region con código ISO provincial correcto, placename, position, ICBM
- **HTTPS forzado** vía .htaccess
- **JSON-LD Structured Data:** Store, Products, Offers, BreadcrumbList, Website, marcas

### Reglas de JSON-LD (lecciones de auditoría)
- **INCLUIR todas las categorías** en `seo_categorias` como Products, incluso sin imagen (usar fallback)
- **NO usar `continue`** por falta de imagen — se pierden keywords para IA/Google
- **Fundador desde config.php**, nunca hardcodeado
- **`inLanguage`: `es-AR`**, no `es` genérico
- **Consistencia con HTML visible:** todo lo declarado en JSON-LD debe aparecer en la página

### Google Maps
- Zoom estandarizado: `!1d1500` en todos los iframes
- Preferir link `https://www.google.com/maps?q=LAT,LNG`
- Agregar `title` al iframe si no lo tiene

---

## 5. Elementos Estándar (Obligatorios en Toda Landing)

### Footer MiLocalWeb
- Logo de MiLocalWeb con link a `https://milocalweb.com.ar#contacto`
- Badge: "Hecho con ❤️ por MiLocalWeb.com.ar"
- CTA: "¿Te gustó esta web? Pedí la tuya sin cargo" → WhatsApp de MiLocalWeb
- Copyright del cliente + botón "volver arriba"

### Aside Publicitario
- Espacio reservado para publicidad de terceros o autopromoción de MiLocalWeb
- Debe ser discreto, no invasivo. Desktop: sidebar lateral; mobile: banner horizontal.
- Contenido configurable vía `config.php['aside_visible']`

### WhatsApp Float
- Botón flotante `#25D366` siempre visible (abajo a la derecha)
- Link directo a `wa.me/{numero}?text={mensaje}`

### Sección "Nuestros Clientes"
- Muestra 3-4 logos de otros clientes de MiLocalWeb
- Super discreta: solo logos en escala de grises, sin textos llamativos
- Configurable vía `config.php['mostrar_clientes']` y `config.php['clientes_mostrar']`

---

## 6. Reglas Estrictas

### Lo que NUNCA se debe hacer:
- Eliminar el footer estándar de MiLocalWeb
- Eliminar el aside publicitario
- Eliminar el WhatsApp float
- Cambiar la estructura de `config.php` (agregar claves sí, quitar las obligatorias no)
- Inventar un estilo visual sin basarse en el informe de identidad del cliente
- Usar lorem ipsum o placeholders — si no hay dato real, dejar vacío
- Hardcodear colores en los templates PHP (usar siempre variables CSS desde config.php)
- Hardcodear el nombre del dueño en header.php (usar `config['founder_name']`)
- Agregar dependencias externas innecesarias (JS frameworks, icon fonts, trackers sin consentimiento)
- Usar espacios o mayúsculas en nombres de archivos de assets
- Dejar archivos .txt de trabajo en carpetas de imágenes o videos
- Usar `mb_strtolower` en meta description (degrada nombres propios de producto)
- Saltar categorías SEO en JSON-LD por falta de imagen
- Olvidar incluir `nosotros.php` en `index.php`

### Lo que SIEMPRE se debe hacer:
- Mantener `config.php` como fuente única de verdad
- Usar variables CSS (`--color-*`) para todos los colores
- Agregar variables para colores derivados: `--color-primary-glow`, `--color-section-dark`
- Usar `htmlspecialchars()` en todo output de datos del cliente
- Respetar la estructura de secciones existente
- Documentar cualquier sección nueva en `docs/ficha-cliente.md`
- Mantener compatibilidad mobile-first
- Incluir `nosotros.php` en `index.php` (es la sección que más se olvida)

---

## 7. Convenciones Técnicas

- **PHP:** Sin framework. PHP plano con includes. Compatible con PHP 7.4+.
- **CSS:** Variables CSS custom properties. Sin preprocesadores. Mobile-first.
- **JS:** Vanilla JS. Sin frameworks. Mínimo y solo para interacción esencial.
- **Imágenes:** Formato WebP prioritario. Lazy loading por defecto.
- **Tipografía:** `system-ui` como fallback. Fuentes auto-hosteadas en `assets/fonts/` (woff2).
- **Íconos:** SVG inline (evitar icon fonts, evitan requests extra).
- **Rendimiento:** Sin jQuery, sin Bootstrap, sin Tailwind. CSS hecho a medida.

### Entrega de CSS (Performance 85+)

**Critical CSS inline + bundle asíncrono en build time.**

> **Regla de oro:** el CSS se escribe SIEMPRE en los archivos fuente. `assets/css/styles.css`
> es un **artefacto generado** y **JAMÁS se edita a mano**.

#### Dónde va cada tipo de estilo

1. **Estilos modulares por componente** → `assets/css/*.css`
   (base, navbar, hero, sections, aside, clientes, footer, responsive).
2. **Estilos above-the-fold (críticos)** → `assets/css/critical.css`
   (variables `:root`, reset, navbar y hero con breakpoints esenciales).
   Se inyecta inline en `header.php`. **No duplicar breakpoints que ya están en responsive.css.**
3. **Fuentes locales** → `assets/fonts/fonts.css` (auto-hosteadas, carga async).

#### Regenerar el bundle (build)

```bash
php tools/build-css.php
```

Obligatorio después de modificar cualquier archivo modular de `assets/css/`.

### Accesibilidad (obligatorio)

- **Skip-link** al contenido principal (en `header.php` después de `<body>`)
- **`:focus-visible`** global con outline visible (en `base.css`)
- **`aria-expanded`** en menú hamburguesa
- **`aria-hidden="true"`** en overlays decorativos
- **`title`** en iframes de Google Maps
- **Alt text descriptivo** en todas las imágenes
- **Jerarquía de headings:** h1 → h2 → h3 (no saltar niveles)

### CSP (Content Security Policy)
```
default-src 'self';
script-src 'self' 'unsafe-inline';
style-src 'self' 'unsafe-inline';
font-src 'self';
img-src 'self' data: https://maps.googleapis.com;
frame-src https://www.google.com;
frame-ancestors 'self';
media-src 'self';
connect-src 'self';
base-uri 'self';
form-action 'self';
```

---

> **Convención de nombres de assets, mapeo config.php, y checklist**: ver skill `milocalweb-landing`.
