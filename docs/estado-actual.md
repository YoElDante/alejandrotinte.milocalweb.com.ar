# Estado actual — Alejandro Tinte

Landing de autor de Alejandro Tinte: single-page con hero, obra destacada, catálogo de once libros, proyectos, biografía y contacto, más fichas individuales en `/libros/{slug}`. Stack PHP plano, CSS modular con build propio y JS vanilla, sin framework, sin Node.js y sin Composer. Auditoría de solo lectura del **2026-11-09**: **NO LISTO**.

> **Nota sobre referencias:** los números de línea son orientativos. La auditoría se hizo antes de la división de archivos y de los comentarios; si un archivo cambió, verificá por selector, meta tag o nombre de función.

## Actualización 2026-11-10 — fixes aplicados

- Se agregó el 301 de los 5 alias al dominio canónico en `.htaccess`.
- Canonical, OG y JSON-LD usan `config['dominio']`, no `HTTP_HOST`.
- `_local/`, `docs/`, `config.php`, `router.php` y los PDFs/`.txt`/`.md`/`.docx` de productos quedaron bloqueados.
- Detalle completo: [Resumen de fixes](../../../docs/resumen-fixes-2026-11-10.md).

## Actualización 2026-11-10 — tanda P2

P2 de código y ordenamiento, en cinco commits (`a661b4e`, `bd2de4f`, `5060640`, `bece207`, `5af0b4d`):

- **Assets fuera de convención (AST-01/02/03):** 19 archivos sin uso (espacios, mayúsculas, acentos, portadas originales JPG/PNG y DOCX) quitados del repo; copias en `_audit/archivos-fuera-de-repos/alejandroTinte/`. Assets 100 % en convención.
- **404 de ficha (P2 SEO):** `libro.php` define `$page_robots = 'noindex, follow'` y canonical propio de la URL solicitada; antes heredaba `index, follow` + canonical de la home.
- **Contraste (A11Y-04):** texto verde muy oscuro `#0B3D2C` (6,2:1, AA) sobre el verde de WhatsApp en `.btn--whatsapp`, `.book-btn--whatsapp`, `.footer-wa-link`, `.whatsapp-float` y `.nav-cta`; el blanco daba 1,98:1.
- **Jerarquía de headings (A11Y-06):** los 3 `<h4>` del footer son ahora `<p class="footer-heading">`; en las fichas no había h3 previo y rompían la jerarquía.
- **Touch targets (MOB-05):** hamburguesa 36→44 px, `.social-link` 42→44 px; el grupo `--minor` conserva el ícono chico con caja transparente de 44 px.
- **Variables de color (CSS-07/08):** `#14110F` → `var(--color-bg)` en skip-link, botones primarios, social hover, back-to-top y lightbox; nuevas `--color-footer-bg` y `--color-section-dark` en `config.php['colors']` + `base.css` + `critical.css`; `critical.css` sincronizado con `navbar.css`.
- **Código muerto:** eliminado `$cssSourceFiles` de `includes/bootstrap.php`; la lista única de fuentes CSS vive en `tools/build-css.php`.
- **CTA de donación:** sin `proyectos_donacion_url` el botón cae a WhatsApp mostrando icono de WhatsApp y label "Apoyar con un aporte" (antes prometía Mercado Pago).
- **README raíz:** creado (`DOC-02`); `docs/` ya estaba versionado.

Verificado con el harness de render local: home + 11 fichas + 404 renderizan OK, JSON-LD parsea (13 nodos) y el 404 emite `noindex, follow` con canonical propio. `styles.css` regenerado del bundle.

**Pendiente P2:** compresión de imágenes (sin GD/ImageMagick en local), Lighthouse móvil, versión pública de la ficha del cliente.

> El veredicto y el puntaje de abajo son el corte de la auditoría 2026-11-09; no se recalcularon.

## Veredicto

| Campo | Valor |
|---|---|
| Estado | **NO LISTO** |
| Cumplimiento | 55,5/79 ítems aplicables (70,3 %) |
| Fecha | 2026-11-09 |
| Alcance | Auditoría de solo lectura + documentación. No se corrigió producto. |

- **P0 — indexación:** los cinco alias definidos en `config.php:352-358` no redirigen con 301 al dominio canónico; el canonical se arma con `HTTP_HOST` (`includes/header.php:19-21,180`; `libro.php:97`), por lo que cada alias se autodeclara canónico. El propio proyecto lo documenta como bloqueante en `_local/docs/pendiente-301-dominios-alias.md:3-5,24`.
- **P0 — seguridad condicional al deploy:** `_local/` no está bloqueado por `.htaccess`; `/_local/docs/ficha-cliente.md` devolvió 200 en el servidor local y contiene el DNI del autor (`_local/docs/ficha-cliente.md:14`). Si el deploy copia `_local/` al docroot, hay exposición de datos personales.
- **P1 — propiedad intelectual:** los PDF completos, las fichas internas `ficha.md` y los `descripcion.txt` son accesibles por URL directa aunque `libros_pdf_publico` sea `false` (`config.php:187`; `robots.txt:9`). `robots.txt` solo desindexa; no bloquea la descarga.
- **Fortalezas verificables:** 20/20 archivos PHP sin errores de sintaxis; rutas principales 200/301/404 correctas; 31 URLs internas de la home y 5 de una ficha sin enlaces ni assets rotos; `styles.css` es byte a byte idéntico a una regeneración desde fuentes (`SHA-256 E398D736…`); `sitemap.xml` es idéntico a una regeneración; JSON-LD sintácticamente válido.
- **Documentación y orden vs. producto:** en la sesión se dividió el CSS en parciales, se separó el conversor de PDF y se agregaron comentarios; esta tarea creó los cinco documentos de `docs/`. Nada de eso corrigió los P0/P1: el sitio sigue **NO LISTO** hasta resolverlos.

## Ficha técnica

| Campo | Valor |
|---|---|
| Ruta | `C:\workspace\MiLocalWeb-WorkSpace\ProyectosWebs\landingpages.clientes\alejandroTinte.milocalweb.com.ar` |
| Dominio previsto | `alejandrotinte.milocalweb.com.ar` (`config.php:345`; `sitemap.xml:5`) |
| Stack | PHP 8.4 en local / PHP 8.3 en hosting (`_local/docs/ficha-cliente.md:236`); HTML, CSS y JS vanilla; Apache + `.htaccess`; sin Node.js, Composer ni framework |
| Entry point | `index.php` (home), `libro.php` (fichas `/libros/{slug}`), `404.php`; `router.php` solo para el servidor embebido |
| Fuente de datos | `config.php` (371 líneas, array único `$cliente`) |
| Build CSS | `tools/build-css.php` → `assets/css/styles.css` (39.460 bytes; SHA-256 `E398D736E0071109003843ECBF6E806DE61307F713F2F7D36CC90CA8FDD4C28F`) |
| Build JS | No tiene: seis archivos standalone con `defer` y cache busting por `filemtime()` |
| Git | Rama `main`; HEAD `479e7ea` (2026-11-09, “fix: boton de Google Play en fila propia en las fichas”); working tree con cambios sin commit del reordenamiento de CSS y los comentarios de la sesión |

## Cumplimiento de especificaciones

> Base: `landingpages.clientes/template/docs/checklist-verificacion.md` y `guia-seo-implementacion.md`.
> `PASS` = cumple; `PARCIAL` = cumple con observaciones; `FAIL` = no cumple; `N/A` = no aplica.
> La auditoría se hizo antes del split de CSS de la sesión; el split no cambió reglas, comportamiento ni hash del bundle.
> Quedaron fuera de la tabla 10 ítems N/A (CFG-03, CFG-08, CSS-05, JS-05, SEO-03, SEO-17, SECT-05, PERF-04, FUNC-04, MOB-04) y 2 NO VERIFICABLES (PERF-01, SEC-03).

### Datos y configuración

| ID | Requisito | Estado | Evidencia |
|---|---|---|---|
| CFG-01 | `config.php` completo, sin placeholders | PARCIAL | `config.php:19` (`email` vacío con TODO) y `config.php:314` (`proyectos_donacion_url` vacío con TODO); la UI degrada en `includes/sections/contacto.php:39-41` y `includes/sections/proyectos.php:26-33`. |
| CFG-02 | `nombre`, `slogan`, `rubro`, `whatsapp` correctos | PASS | `config.php:15-18`: Alejandro Tinte / Historias que dejan marca / Escritor / 5491170601270. |
| CFG-04 | WhatsApp sin espacios, con país; mensaje personalizado | PASS | `config.php:18,20`; normalización y URL en `includes/helpers.php:29-68`. |
| CFG-05 | `hero_descripcion` y `nosotros_texto` con productos/marcas/ubicación | PARCIAL | `config.php:25` describe novelas y relatos, sin marcas ni localidad; `config.php:281-285` tiene la bio real. El desvío es razonable para un autor sin local. |
| CFG-06 | `colors[]` completo | PASS | `config.php:34-49` incluye primary, hover, accent, text, muted, bg, bg-alt, card-bg, line y derivados; `assets/css/base.css:5-46` los replica. |
| CFG-07 | Tipografía con fallback a `system-ui` | PASS | `config.php:50-51`; inyección en `includes/header.php:236-242`; body en `assets/css/base.css:80`. Los títulos usan Georgia/serif por identidad. |
| CFG-09 | `seo_og_image` existente y 1200x630 | PASS | `config.php:344`; archivo verificado con `getimagesize`: 1200x630, 119 KB. |

### Assets

| ID | Requisito | Estado | Evidencia |
|---|---|---|---|
| AST-01 | Convención de nombres: minúsculas, guiones, sin espacios | FAIL | Hay archivos con espacios, mayúsculas y acentos: `assets/img/cliente/escritor/Contemplación frente al mar.webp`, `assets/img/cliente/flotantes/Caballeros sentados, sombrero negro y capa roja.webp`, `productos/a-fuego-y-fierro/A fuego y Fierro librito.webp`, `productos/mi-amigo-trotta/Mi amigo Trotta.docx`. |
| AST-02 | Sin `.txt` en `assets/`; sin archivos de trabajo | PARCIAL | `assets/` no tiene `.txt`; `productos/*/descripcion.txt` y `ficha.md` son datos de contenido y notas internas, pero quedan públicos (ver SEC-07). Hay `.docx`, `.jpg` y `.png` de trabajo en `productos/`. |
| AST-03 | Imágenes en WebP salvo favicon/texturas | PARCIAL | El sitio renderiza WebP, pero el repo versiona `productos/a-fuego-y-fierro/portada.jpg` (3,2 MB), `productos/donde-carajos-esta-el-atizador-y-otras-obsesiones/portada_1600px.png` (4,5 MB) y `.../Árbol, gallinas y la Tierra lunar.png` (1,9 MB), no referenciados por `config.php`. |
| AST-04 | Logo transparente, OG 1200x630, favicon | PASS | No hay logo propio (`config.php:56`), decisión documentada en `_local/docs/ficha-cliente.md:303`; OG en `config.php:344`; favicon en `config.php:57`. |
| AST-05 | `hero_img` en `identidad/`, fotos de producto en `productos/`, galería en `identidad/` | PARCIAL | El hero está en `assets/img/cliente/escritor/` (`config.php:28`), no en `identidad/`; las portadas están en `productos/{slug}/`, no en `assets/img/cliente/productos/`. Es una arquitectura adaptada y consistente, pero no la del template. |
| AST-06 | Logos de terceros, videos con poster y assets MiLocalWeb intactos | PASS | No hay terceros ni videos (N/A). Los assets de MiLocalWeb se usan en `includes/footer.php:119` y `includes/sections/aside.php:20`. |

### CSS y build

| ID | Requisito | Estado | Evidencia |
|---|---|---|---|
| CSS-01 | `styles.css` más reciente que las fuentes | PASS | `styles.css` 2026-11-09 21:46; parciales de `sections/` 21:40-21:41; `responsive/` 21:44:53. |
| CSS-02 | `build-css.php` ejecutado después del último cambio | PASS | Rebuild en copia temporal: 39.460 bytes, SHA-256 `E398D736E0071109003843ECBF6E806DE61307F713F2F7D36CC90CA8FDD4C28F`, idéntico al proyecto. |
| CSS-03 | `styles.css` no editado a mano | PASS | Misma prueba de reconstrucción byte a byte; el bundle es reproducible desde `tools/build-css.php:23-54`. |
| CSS-04 | Critical CSS inline sin tocar `styles.css` | PASS | `includes/header.php:216-219` inyecta `assets/css/critical.css`; el bundle se carga aparte en `includes/header.php:221-223`. |
| CSS-06 | `styles.css` async con preload/onload y fallback | PASS | `includes/header.php:221-223` (`preload` + `onload` + `<noscript>`). |
| CSS-07 | Colores por variables, mínimo de hardcodeos | PARCIAL | Uso amplio de `var(--color-*)`, pero quedan hardcodeos: `assets/css/base.css:130,201` (`#14110F`), `assets/css/sections/02-libros.css:171,176` (`#14110F`), `assets/css/footer.css:9` (`#0D0B09`), `assets/css/critical.css:89` (`#25D366`). |
| CSS-08 | Variables derivadas `--color-primary-glow` y `--color-section-dark` | PARCIAL | `--color-primary-glow` existe (`config.php:38`; `assets/css/base.css:13`); `--color-section-dark` no aparece en el proyecto (0 coincidencias; solo se menciona en `AGENTS.md:194`). |

### JavaScript

| ID | Requisito | Estado | Evidencia |
|---|---|---|---|
| JS-01 | Todos los JS con `defer` en footer | PASS | `includes/footer.php:173-175`; 6 archivos listados en `includes/bootstrap.php:54-61`. |
| JS-02 | Cache busting con `filemtime()` | PASS | `includes/bootstrap.php:63-73`; aplicado en `includes/footer.php:174`. |
| JS-03 | Sin dependencias externas | PASS | No hay `package.json`, CDN ni frameworks; los 6 archivos son IIFE vanilla. |
| JS-04 | Navbar mobile, smooth scroll, back to top | PASS | `assets/js/navbar.js:34-63`, `assets/js/smooth-scroll.js:9-32`, `assets/js/back-to-top.js:9-23`. Revisión estática; no se probó en navegador real. |

### SEO técnico

| ID | Requisito | Estado | Evidencia |
|---|---|---|---|
| SEO-01 | Title `{NOMBRE} — {KEYWORDS} en {LOCALIDAD}` | PARCIAL | `includes/header.php:23-25,178`; home: “Alejandro Tinte — Libros y relatos” (36 caracteres), sin localidad; fichas: “{título} — Alejandro Tinte” (30-77 caracteres). |
| SEO-02 | Meta description con productos reales, sin `mb_strtolower` | PARCIAL | `includes/header.php:26-28,179`; home 104 caracteres; fichas 62-451. Casos excesivos: Cuestión de Fe 446, El Juguete 451, La Perla del Irupé 259. No se usa `mb_strtolower`. |
| SEO-04 | Open Graph completo, image 1200x630 | PARCIAL | Home correcta en `includes/header.php:182-196`, imagen 1200x630 verificada. En fichas, `libro.php:98` pasa la portada completa (900x1350) como `og:image`; `og:type` sigue `website` y no se declaran dimensiones en ese caso. |
| SEO-05 | Twitter Card `summary_large_image` | PARCIAL | `includes/header.php:198-204`: card, title, description e image; falta `twitter:image:alt`. |
| SEO-06 | Canonical correcta | FAIL | `includes/header.php:19-21,180` construye `https://{$_SERVER['HTTP_HOST']}/`; `libro.php:97` hace lo mismo para fichas. Con `Host: aletinte.autor.milocalweb.com.ar` el canonical apunta al alias; con `Host: evil.example`, a `https://evil.example/`. No hay 301 de alias en `.htaccess:30-44`. |
| SEO-07 | `lang="es"` en `<html>` | PASS | `includes/header.php:166`. |
| SEO-08 | JSON-LD completo y válido | PARCIAL | `includes/header.php:42-163`; JSON parseado sin error. Home: `Person` + 11 `Book` + `WebSite`; ficha: `Person` + `Book` + `BreadcrumbList` + `WebSite`. Problemas semánticos: `Person.description` toma la sinopsis del libro, `WebSite.name/description` cambian por página y el `@id` del libro difiere entre home y ficha. |
| SEO-09 | Store, Products y Offers vinculados | PARCIAL | No hay `Store` (N/A para autor), pero los 11 libros se declaran como `Book` sin `offers`, `isbn` ni `bookFormat`; hay URLs de Amazon/Google en `config.php:211-251` que no se reflejan en JSON-LD. |
| SEO-10 | BreadcrumbList | PASS | Ficha: `includes/header.php:89-111`, tres niveles correctos. Home es single-page: N/A. |
| SEO-11 | Marcas como Organization; founder desde config; WebSite `inLanguage es-AR` | PARCIAL | `founder` se reemplaza por `Person` desde config (`includes/header.php:42-49`) e `inLanguage` es `es-AR` (`:150`). No hay marcas (N/A). `WebSite.author` es MiLocalWeb (`:151-157`), no el autor, y `WebSite.name` cambia por página (`:148`). |
| SEO-12 | `robots.txt` permite indexar, bloquea internos y declara sitemap | PARCIAL | `robots.txt:1-11`: permite `/`, bloquea `/includes/`, `/tools/` y PDFs, declara sitemap. No bloquea `/_local/`, `productos/*/ficha.md` ni `descripcion.txt`; el bloqueo de PDFs es solo de rastreo, no de acceso. |
| SEO-13 | `sitemap.xml` con imágenes, coordenadas y lastmod | PARCIAL | `sitemap.xml:1-120`: 12 URLs, 11 imágenes y `lastmod` 2026-11-09; todas las URLs devolvieron 200. No incluye imagen de la home ni geo (N/A por no haber local). |
| SEO-14 | `.htaccess` con HTTPS, CSP y cache headers | FAIL | `.htaccess:5-27` define headers/caché y `:30-44` rewrites; no hay 301 de alias al canónico y `^(config\|includes\|tools)/` no bloquea `config.php` (solo un directorio `config/`). CSP (`:9`) no incluye `media-src`. |
| SEO-15 | Alt text en todas las imágenes con `{NOMBRE} en {LOCALIDAD}` | PARCIAL | Portadas y fotos tienen alt descriptivo (`includes/sections/libros.php:68,91`; `libro.php:71,127`); decorativas usan `alt=""` + `aria-hidden="true"` (`hero.php:23`; `destacado.php:119`). No incluyen localidad (N/A) y `autor.php:107` hardcodea el nombre en el alt. |
| SEO-16 | Favicon | PASS | `config.php:57`; `includes/header.php:206-209`; archivo de 22.912 bytes verificado. |

### Secciones obligatorias

| ID | Requisito | Estado | Evidencia |
|---|---|---|---|
| SECT-01 | Hero renderiza correctamente | PASS | `includes/sections/hero.php:19-54`; `index.php:21`; HTTP 200 en `/`. |
| SECT-02 | H1 = keywords + localidad, sin slogan | PARCIAL | `includes/sections/hero.php:34` usa “Alejandro Tinte” (marca) como H1; el slogan va en subtítulo (`hero.php:35`). No hay localidad (N/A), pero tampoco keyword de producto. |
| SECT-03 | Productos: 1-3 con nombre, descripción, imagen y CTA | PARCIAL | `includes/sections/libros.php:45-232` muestra 9 libros en grilla + 1 destacado + 1 antología. Es un catálogo de autor, no una landing de 3 productos; la adaptación es coherente pero excede el requisito. |
| SECT-04 | Producto estrella hardcodeado correctamente | PASS | `config.php:193` (`a-fuego-y-fierro`); `includes/sections/destacado.php:49`; se excluye de la grilla en `libros.php:32-36`. |
| SECT-06 | Nosotros: texto, galería, redes y CTA; `nosotros.php` incluido | PASS | Equivalente `includes/sections/autor.php:26-153`, incluido en `index.php:26`; bio, foto, imagen extra, CTA y redes. No existe `nosotros.php` con ese nombre, pero la sección está. |
| SECT-07 | Aside publicitario y clientes según config | PASS | `index.php:24` incluye `aside.php`; `config.php:362` (`aside_visible=true`); `config.php:367` (`mostrar_clientes=false`); `clientes.php:11-18` retorna sin renderizar. |
| SECT-08 | Footer MiLocalWeb con logo, badge, CTA y WhatsApp float | PASS | `includes/footer.php:41-161`: logo, badge, CTA, copyright y float (`footer.php:155-161`); color `#25D366` en `assets/css/footer.css:259`. |

### Documentación

| ID | Requisito | Estado | Evidencia |
|---|---|---|---|
| DOC-01 | `AGENTS.md` en raíz | PASS | Presente y idéntico al template (SHA-256 comparado). |
| DOC-02 | `README.md` en raíz | FAIL | No existe; el listado de la raíz no lo incluye. |
| DOC-03 | `docs/ficha-cliente.md` completo | PARCIAL | Al momento de la auditoría `docs/` estaba vacío. El contenido existe en `_local/docs/ficha-cliente.md` (334 líneas, completo), pero `_local/` está en `.gitignore:17` y no se versiona. |
| DOC-04 | `docs/informe_estilo_{cliente}.md`, `docs/guia-relevamiento.md`, `{cliente}.cliente.md` archivado | PARCIAL | No existen en `docs/`. `_local/docs/` tiene `perfil-autor.md`, `brief-redes.md`, `esqueleto-producto.md` y la ficha, pero no el informe de estilo ni la guía de relevamiento; `_local/docs/ficha-cliente.md:299` admite que la identidad es provisoria. |
| DOC-05 | Documentación versionada en git | FAIL | `.gitignore:17` ignora `_local/`; `git ls-files` no incluye ningún `docs/*.md`. La documentación canónica vive fuera del repo. |

### Accesibilidad

| ID | Requisito | Estado | Evidencia |
|---|---|---|---|
| A11Y-01 | Skip-link y `:focus-visible` global | PASS | `includes/header.php:245`; `assets/css/base.css:119-122`; `assets/css/critical.css:57`. |
| A11Y-02 | Roles ARIA navigation/contentinfo y labels | PASS | `includes/header.php:250`; `includes/footer.php:41`; `aria-label` en secciones (`destacado.php:49`, `aside.php:17`). |
| A11Y-03 | `aria-expanded`, `aria-hidden` y cierre de menú | PASS | `includes/header.php:265`; `assets/js/navbar.js:34-63`; overlay con `aria-hidden="true"` en `header.php:288`. |
| A11Y-04 | Contraste WCAG AA | FAIL | Blanco sobre WhatsApp `#25D366`: 1,98:1 (`assets/css/base.css:222-225`; `footer.css:139,259`; `navbar.css:71`). El resto cumple: texto/fondo 16,56:1, muted/fondo 8,11:1, Amazon 13,61:1, Google Play 4,53:1. |
| A11Y-05 | Imágenes con `width`/`height` para evitar CLS | PARCIAL | Portadas y fotos de contenido sí (`libros.php:90-94`, `autor.php:30-35`, `proyectos.php:71-75`); hero y flotantes decorativos no (`hero.php:22-26`, `destacado.php:119`). El hero es LCP. |
| A11Y-06 | Sin `h4` en footer; jerarquía h1→h2→h3 | FAIL | `includes/footer.php:60,100` usan `<h4>` (“Redes del autor”, “Redes personales”, “Contacto”). En fichas de libro no hay h2/h3 y se salta de h1 a h4. |

### Performance

| ID | Requisito | Estado | Evidencia |
|---|---|---|---|
| PERF-02 | Preload del hero con `fetchpriority="high"` | PARCIAL | `includes/sections/hero.php:22-26` tiene `loading="eager"` y `fetchpriority="high"`, pero no hay `<link rel="preload">` del hero en `header.php`. |
| PERF-03 | `loading="lazy"` y `decoding="async"` en no críticas | PASS | Portadas (`libros.php:90-93`), autor (`autor.php:30-34`), proyectos (`proyectos.php:71-75`), footer (`footer.php:119-123`). |
| PERF-05 | Sin Google Fonts ni CDNs externos | PASS | No hay `<link>` ni `<script>` externos en `header.php`/`footer.php`; los SVG son locales (`assets/img/svg/`). |
| PERF-06 | CSS no bloqueante | PASS | `includes/header.php:221-223`; critical inline en `:216-219`. |

### Funcionalidad

| ID | Requisito | Estado | Evidencia |
|---|---|---|---|
| FUNC-01 | WhatsApp del cliente con mensaje default | PASS | `config.php:18,20`; `includes/helpers.php:55-68`; URL `wa.me/5491170601270?text=...` en home y fichas. No se puede verificar que la cuenta atienda, solo el formato. |
| FUNC-02 | WhatsApp de MiLocalWeb en footer y aside | PASS | `includes/footer.php:136`; `includes/sections/aside.php:33`; helper `includes/helpers.php:72-75`. |
| FUNC-03 | Links de redes funcionales | PASS | `config.php:325-338`; footer `includes/footer.php:57-97`; contacto `includes/sections/contacto.php:48-62`. |
| FUNC-05 | Menú mobile, smooth scroll, back to top | PASS | `navbar.js:34-63`, `smooth-scroll.js:9-32`, `back-to-top.js:9-23`; revisión estática de código. |
| FUNC-06 | Sin errores PHP | PASS | `php -l` 20/20 sin errores; el log del servidor no mostró warnings. `display_errors` se evalúa en SEC-03. |
| FUNC-07 | Sin enlaces ni imágenes rotas | PASS | Crawl de 31 URLs internas de `/` y 5 de `/libros/mi-amigo-trotta`: 0 con HTTP ≥400. Los 53 assets referenciados por `config.php` existen. |

### Mobile

| ID | Requisito | Estado | Evidencia |
|---|---|---|---|
| MOB-01 | Viewport `width=device-width, initial-scale=1.0` | PASS | `includes/header.php:170`. |
| MOB-02 | Menú hamburguesa funcional | PASS | `navbar.js:34-63`; CSS mobile en `assets/css/responsive/01-tablet.css:248-297`; `aria-expanded` en `header.php:265`. |
| MOB-03 | Hero responsive; productos a columna única | PARCIAL | Hero con breakpoint 900 (`assets/css/responsive/01-tablet.css:11-68`). La grilla usa `minmax(160px, 1fr)` en `assets/css/responsive/02-mobile.css:36`, por lo que a 375 px puede mostrar 2 columnas, no una. |
| MOB-05 | Touch targets ≥ 44x44 | FAIL | Botón hamburguesa 36x36 (`assets/css/navbar.css:92-93`); links sociales 42x42 (`assets/css/footer.css:78-79`). |
| MOB-06 | Sin scroll horizontal | PASS | `html` y `body` con `overflow-x: hidden` (`assets/css/base.css:75,85`); revisión estática. |

### Seguridad

| ID | Requisito | Estado | Evidencia |
|---|---|---|---|
| SEC-01 | `secrets.php` en `.gitignore` y no versionado | PASS | `.gitignore:14`; no existe `includes/secrets.php` en el repo; `git ls-files` no lo lista. |
| SEC-02 | CSP con `frame-ancestors` y `media-src` | PARCIAL | `.htaccess:9` incluye `frame-ancestors 'self'`; falta `media-src 'self'`. No hay media hoy, pero el template lo exige. |
| SEC-04 | Sin información sensible en comentarios HTML | PASS | Los comentarios del HTML son estructurales (“Metadatos básicos”, “Open Graph”, etc.); no hay credenciales ni rutas internas. |
| SEC-05 | `htmlspecialchars()` en todo output de datos del cliente | PARCIAL | `includes/helpers.php:18-21` y 181 usos de `e()`. Excepciones: canonical/`og:url` sin escapar (`header.php:180,187`); valores de `colors`/tipografía inyectados crudos en `<style>` (`header.php:225-242`). |
| SEC-06 | `rel="noopener noreferrer"` en links externos | PARCIAL | La mayoría lo usa (`libro.php:163-166`); los links a MiLocalWeb usan solo `noopener` (`footer.php:118,130`; `aside.php:19`). |
| SEC-07 | Archivos internos y PDFs no públicos | FAIL | `config.php:187` declara `libros_pdf_publico=false`, pero `GET /productos/a-fuego-y-fierro/a-fuego-y-fierro.pdf` devuelve 200 (421.613 bytes); `ficha.md` 200 (2.425 bytes); `descripcion.txt` 200. `robots.txt:9` solo desindexa. |
| SEC-08 | `.htaccess` bloquea `config.php` e `includes/` | FAIL | `.htaccess:33` usa `^(config\|includes\|tools)/`, que bloquea directorios pero no `config.php`. `GET /config.php` devuelve 200 con cuerpo vacío (PHP lo ejecuta); no hay 403. |

**Resumen del checklist:** 42 PASS, 27 PARCIAL, 10 FAIL, 10 N/A y 2 NO VERIFICABLE. Aplicables: 79. Puntaje con PARCIAL = 0,5: **55,5/79 (70,3 %)**. PASS estrictos: 42/79 (53,2 %).

## SEO implementado

| Elemento | Estado | Valor encontrado | Evidencia |
|---|---|---|---|
| Title | PARCIAL | Home: `Alejandro Tinte — Libros y relatos` (36). Fichas: `{título} — Alejandro Tinte` (30-77; la antología llega a 77). | `includes/header.php:178`; `libro.php:95`; longitudes medidas con DOMDocument. |
| Meta description | PARCIAL | Home 104 caracteres, genérica. Fichas: 62-451; Cuestión de Fe 446 y El Juguete 451 se truncarían en Google. | `includes/header.php:179`; `libro.php:96`; `productos/cuestion-de-fe/descripcion.txt:1`; `productos/el-juguete/descripcion.txt:1`. |
| Canonical | FAIL | Home: `https://{HTTP_HOST}/`; ficha: `https://{HTTP_HOST}/libros/{slug}`. Alias y Host arbitrario cambian el canonical. | `includes/header.php:19-21,180`; `libro.php:97`; prueba con `Host: aletinte.autor.milocalweb.com.ar` y `Host: evil.example`. |
| Robots meta | PARCIAL | Home y fichas: `index, follow`. 404 principal: `noindex, follow`. 404 de libro inexistente: `index, follow` (no define `$page_robots`). | `includes/header.php:171`; `404.php:17`; `libro.php:33-39`. |
| `lang` | PASS | `es`. | `includes/header.php:166`. |
| Geo tags | N/A | No hay `geo.region`, `geo.placename`, `geo.position` ni `ICBM`. Autor sin local comercial. | `includes/header.php:168-209`; `_local/docs/ficha-cliente.md:229`. |
| Open Graph | PARCIAL | Home completa: title, description, type=website, locale=es_AR, url, site_name, image 1200x630, width/height/alt. Ficha: `og:image` es la portada (900x1350), sin width/height; `og:type` sigue `website`. | `includes/header.php:182-196`; `libro.php:98`; `getimagesize` de `og-alejandrotinte-1200x630.jpg` = 1200x630. |
| Twitter Card | PARCIAL | `summary_large_image`, title, description e image; falta `twitter:image:alt`. | `includes/header.php:198-204`. |
| JSON-LD | PARCIAL | JSON válido. Home: `Person`, 11 `Book`, `WebSite`. Ficha: `Person`, `Book`, `BreadcrumbList`, `WebSite`. Sin `Store`/`Product`/`Offer`; `Person` y `WebSite` toman datos de la página en fichas; `@id` de libro inconsistente. | `includes/header.php:42-163`; parseo con `json_decode` sin error. |
| Headings | PARCIAL | Home: 1 h1, 5 h2, 14 h3, 3 h4. Ficha: 1 h1, 0 h2, 0 h3, 3 h4. Los h4 son del footer y no hay h3 que los preceda en la ficha. | `includes/footer.php:60,100`; conteo DOMDocument. |
| Alt text | PARCIAL | Portadas: “Portada de {título} — Alejandro Tinte”. Fotos: “Alejandro Tinte — autor”. Decorativas: `alt=""` + `aria-hidden="true"`. No hay localidad (N/A). `autor.php:107` hardcodea el nombre. | `libros.php:68,91`; `libro.php:71,127`; `hero.php:23`; `autor.php:31,107`. |
| `robots.txt` | PARCIAL | `Allow: /`; bloquea `/includes/`, `/tools/`, `/*.pdf$`; declara sitemap. No bloquea `_local/`, `ficha.md` ni `descripcion.txt`. | `robots.txt:1-11`. |
| `sitemap.xml` | PARCIAL | 12 URLs (home + 11 libros), 11 imágenes, `lastmod` 2026-11-09; todas las URLs 200. Sin imagen de home ni geo. | `sitemap.xml:1-120`; verificación HTTP de las 12 URLs. |
| `.htaccess` | FAIL | HTTPS forzado, CSP, cache y rewrites de `/libros`; sin 301 de alias; `config.php` no bloqueado; CSP sin `media-src`. | `.htaccess:5-44`. |
| Enlaces internos | PASS | 31 URLs internas de la home y 5 de una ficha, 0 rotas; las 9 anclas detectadas en la home resuelven. | Crawl con DOMDocument + curl; anclas en `hero.php:19`, `destacado.php:49`, `libros.php:45,169`, `proyectos.php:39`, `autor.php:26`, `contacto.php:25`. |
| Imagen OG | PASS | 1200x630, 119 KB, existe. | `config.php:344`; `getimagesize`. |
| Favicon | PASS | `.ico` de 22.912 bytes. | `config.php:57`; `header.php:206-209`; archivo. |
| `hreflang` | N/A | Sitio monolingüe. | `includes/header.php:166`. |

## Deuda y riesgos

> **No corregido en esta sesión.** El alcance fue documental y de ordenamiento interno. Ordenado por impacto.

| Prioridad | Problema | Impacto | Evidencia | Recomendación |
|---|---|---|---|---|
| P0 | No hay 301 de los cinco alias al canónico; el documento interno confirma que los seis hostnames responden 200 con el mismo contenido. El canonical se arma con `HTTP_HOST`, así que cada alias se autodeclara canónico. | Contenido duplicado en 6 hostnames, canibalización, backlinks y analítica fragmentados; el propio proyecto lo marca como bloqueante de lanzamiento SEO. | `config.php:352-358`; `.htaccess:30-44` (sin regla de host); `includes/header.php:19-21,180`; `libro.php:97`; `_local/docs/pendiente-301-dominios-alias.md:3-5,24`; prueba con `Host: aletinte.autor.milocalweb.com.ar` y `Host: evil.example`. | Insertar el bloque de `RewriteCond %{HTTP_HOST}` + `RewriteRule` 301 antes de la regla HTTPS, excluyendo `localhost`/`127.0.0.1`; fijar el canonical al dominio de `config.php`, no a `HTTP_HOST`; probar los 5 alias, un path profundo y un solo salto. |
| P0 | `_local/` no está bloqueado por `.htaccess` y contiene la ficha con DNI y datos personales. En el servidor local `/_local/docs/ficha-cliente.md` devuelve 200 (18.211 bytes). | Exposición de datos personales si el deploy copia `_local/` al docroot (FTP, backup o copia completa). Es un riesgo alto aunque no se pueda confirmar el método de deploy desde el repo. | `_local/docs/ficha-cliente.md:14` (DNI); `.htaccess:33`; `curl` local 200. | Bloquear `^_local/` en `.htaccess`; excluir `_local/` del deploy; mover los documentos privados fuera del docroot; agregar `Disallow: /_local/` como defensa en profundidad; verificar 403 en producción. |
| P1 | PDFs completos, `ficha.md` internas y `descripcion.txt` son accesibles por URL directa, aunque `libros_pdf_publico` es `false`. | Descarga no autorizada de obras y filtración de notas editoriales; contradice la decisión de negocio documentada. | `config.php:187`; `robots.txt:9` (solo desindexa); `.htaccess` sin bloqueo de PDF, Markdown y TXT bajo `productos/`; HTTP 200 de `/productos/a-fuego-y-fierro/a-fuego-y-fierro.pdf` (421.613 bytes), `ficha.md` y `descripcion.txt`. | Bloquear a nivel servidor `/productos/*.pdf`, `ficha.md` y `descripcion.txt`; si se autoriza la descarga, servir el PDF mediante un endpoint PHP que valide la bandera; mantener públicas solo las portadas. |
| P1 | `.htaccess` no bloquea `config.php`: el patrón `^(config\|includes\|tools)/` solo cubre directorios. | Incumple la guía de seguridad; si PHP se sirve como fuente por un error de configuración, la configuración completa queda expuesta. Hoy `GET /config.php` devuelve 200 con cuerpo vacío porque PHP lo ejecuta. | `.htaccess:33`; `curl /config.php` 200, 0 bytes. | Cambiar a `^(config\.php\|includes/\|tools/\|_local/) - [F,L]`; verificar 403 en Apache. |
| P2 | Meta descriptions demasiado largas en varias fichas y título de la antología de 77 caracteres. | Google truncará la descripción; se pierde el gancho comercial en resultados. | `libro.php:96`; `productos/cuestion-de-fe/descripcion.txt:1` (446 caracteres); `productos/el-juguete/descripcion.txt:1` (451); `productos/la-perla-del-irupe/descripcion.txt:1` (259). | Agregar `seo_descripcion` por libro en `config.php` o usar `libro_resumen($libro, 155)` para el meta description, sin `mb_strtolower`; acortar el título de la antología si se quiere mostrar completo. |
| P2 | JSON-LD semánticamente inconsistente en fichas: `Person.description` toma la sinopsis del libro; `WebSite.name/description` cambian por página; `@id` del libro difiere entre home y ficha; no hay `offers`/`isbn`. | Menor capacidad de entidad para Google/IA; posibles entidades duplicadas y datos pobres para rich results de libros. | `includes/header.php:42-163`; `config.php:211-251`. | Separar `Person` (bio), `WebSite` (nombre/descripción del sitio) y `Book`; usar un `@id` estable por libro; agregar `offers` con las URLs de Amazon/Google e `isbn` cuando exista. |
| P2 | El 404 de libro inexistente responde 404 pero con `meta robots=index, follow` y canonical a la home. | Señal contradictoria; Google podría rastrear una URL 404 como indexable, aunque el estado 404 debería prevalecer. | `libro.php:33-39` no define `$page_robots`; `includes/header.php:171` aplica el default; `header.php:21` canonical a `/`. | Definir `$page_robots = 'noindex, follow'` y canonical propio (o ninguno) en el bloque 404 de `libro.php`; idealmente reutilizar `404.php`. |
| P2 | Documentación canónica fuera del repo: `docs/` estaba vacío, sin `README.md` raíz y con `_local/` gitignored. | Un clon nuevo no tiene ficha, guía ni README; la documentación se pierde si no se copia `_local/`. Esta sesión creó `docs/*.md`, pero falta el commit y una versión pública de la ficha. | `docs/` estaba con 0 entradas; `.gitignore:17`; `git ls-files` sin `docs/*.md`; raíz sin `README.md`. | Publicar un `README.md` mínimo en la raíz y una versión pública de la ficha en `docs/`; mantener los datos personales en `_local/` fuera del docroot. |
| P2 | Contraste insuficiente en botones WhatsApp (blanco sobre `#25D366`, 1,98:1) y jerarquía de headings con `h4` en footer. | Falla WCAG AA en los CTA principales; lectores de pantalla reciben una jerarquía rota. | `assets/css/base.css:222-225`; `footer.css:139,259`; `navbar.css:71`; `includes/footer.php:60,100`. | Usar texto oscuro (`#0B3D2C` o similar) sobre el verde, o oscurecer el verde; reemplazar los `h4` del footer por `h3` o `<p>` con estilo. |
| P2 | Touch targets menores a 44x44: hamburguesa 36x36 y redes 42x42. | Dificulta el uso en mobile. | `assets/css/navbar.css:92-93`; `assets/css/footer.css:78-79`. | Llevar ambos a 44x44 mínimo, sin cambiar el diseño visual (padding transparente). |
| P2 | Nombres de assets con espacios, mayúsculas y acentos; archivos de trabajo pesados versionados. | Riesgo de URLs rotas en servidores/filesystems sensibles; repo más pesado. | `assets/img/cliente/escritor/Contemplación frente al mar.webp`; `assets/img/cliente/flotantes/Caballeros sentados, sombrero negro y capa roja.webp`; `productos/a-fuego-y-fierro/A fuego y Fierro librito.webp`; `productos/mi-amigo-trotta/Mi amigo Trotta.docx`; `productos/a-fuego-y-fierro/portada.jpg`; `productos/donde-carajos-esta-el-atizador-y-otras-obsesiones/portada_1600px.png`. | Renombrar a minúsculas con guiones y actualizar referencias; eliminar del repo los originales no usados; conservar solo WebP optimizados. |
| P2 | Colores hardcodeados y variable derivada faltante; lista de fuentes CSS duplicada. | Dificulta el theming y el mantenimiento; `$cssSourceFiles` en `bootstrap.php` no se usa y puede desincronizarse del build. | `assets/css/base.css:130,201`; `assets/css/sections/02-libros.css:171,176`; `assets/css/footer.css:9`; `assets/css/critical.css:89`; `config.php:38`; `AGENTS.md:194` (pide `--color-section-dark`); `includes/bootstrap.php:32-49`; `tools/build-css.php:23-43`. | Reemplazar hardcodeos por variables; agregar `--color-section-dark`; eliminar `$cssSourceFiles` o hacer que el build y el runtime lean una única lista/manifest. |
| P2 | La home pesa ~2,1 MB en imágenes únicas (20 imágenes) y algunas portadas de 600 px superan 150 KB. | Afecta LCP/TBT en mobile, aunque no se midió Lighthouse. | `getimagesize` + suma de archivos: 20 imágenes, 2.194.900 bytes; `portada-600` de Mi amigo Trotta 153 KB; HacemeGancho 237 KB. | Comprimir portadas a WebP de calidad moderada, limitar el hero a ~150 KB y usar `srcset`/tamaños adecuados; medir Lighthouse después de optimizar. |
| P2 | El botón “Apoyar con Mercado Pago” cae a WhatsApp cuando `proyectos_donacion_url` está vacío. | El texto y el icono prometen Mercado Pago pero el destino es WhatsApp; puede confundir y bajar confianza. | `config.php:314`; `includes/sections/proyectos.php:26-33,111-116`. | Cambiar el label/icono dinámicamente según exista `proyectos_donacion_url`, o pedir el link real antes de publicar. |

## Checklist para considerarlo terminado

- [ ] Todos los P0 resueltos: 301 de los cinco alias y `_local/` bloqueado o fuera del docroot.
- [ ] Todos los P1 resueltos o aceptados por escrito: PDFs/fichas/descripcion bloqueados y `config.php` inaccesible por HTTP.
- [ ] Checklist de `landingpages.clientes/template/docs/checklist-verificacion.md` al 100 %.
- [ ] Sitemap subido a Search Console.
- [ ] Lighthouse móvil: Performance ≥ 85, Accesibilidad ≥ 95, SEO 100.
- [ ] Sin errores PHP ni enlaces/imágenes rotas.
- [ ] `docs/` commiteado y `README.md` raíz publicado.

## Verificación reproducible

```bash
php -l index.php
php -l libro.php
php -l config.php
php -S 127.0.0.1:8101 -t . router.php
curl.exe -s -o NUL -w "%{http_code}" http://127.0.0.1:8101/
curl.exe -s -o NUL -w "%{http_code}" http://127.0.0.1:8101/libros/mi-amigo-trotta
```

Para comparar el bundle sin tocar el proyecto, ejecutar el build en una copia temporal:

```bash
php tools/build-css.php
Get-FileHash assets/css/styles.css -Algorithm SHA256
```

Resultado esperado: `E398D736E0071109003843ECBF6E806DE61307F713F2F7D36CC90CA8FDD4C28F`.
`php tools/build-css.php` y `php tools/build-sitemap.php` regeneran archivos: usarlos en una copia si solo se quiere verificar.

## Documentos relacionados

- [Arquitectura](arquitectura.md)
- [Mapa del sitio](mapa-sitio.md)
- [Resumen de documentación](resumen-documentacion.md)
- [Auditoría completa](../../../_audit/alejandroTinte.md)
- [Ficha del cliente](../_local/docs/ficha-cliente.md)
