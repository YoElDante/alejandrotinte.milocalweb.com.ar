# Alejandro Tinte — Landing de autor

Landing page del escritor Alejandro Tinte en `alejandrotinte.milocalweb.com.ar`: single-page con hero, obra destacada, catálogo de libros, proyectos en curso, biografía y contacto, más fichas individuales en `/libros/{slug}`.

Proyecto de [MiLocalWeb](https://milocalweb.com.ar) — páginas web para negocios locales.

## Stack

- PHP plano sin framework (compatible PHP 7.4+), sin Node.js ni Composer.
- CSS modular con build propio (`tools/build-css.php`) y critical CSS inline.
- JS vanilla por funcionalidad, cargado con `defer`.
- Apache + `.htaccess` (HTTPS forzado, CSP, caché, rewrites y bloqueos).

## Estructura

```
├── index.php            ← Home (single-page)
├── libro.php            ← Ficha de libro (/libros/{slug})
├── config.php           ← Única fuente de datos del sitio
├── .htaccess            ← Seguridad, caché, 301 de alias, bloqueos
├── assets/              ← css/ js/ fonts/ img/ svg/ vid/
├── includes/            ← header, footer, bootstrap y secciones
├── productos/           ← Portadas y PDFs por libro (PDFs bloqueados)
├── tools/               ← build-css.php, build-sitemap.php
└── docs/                ← Documentación del proyecto
```

## Desarrollo local

```bash
php -S localhost:8000 router.php    # servidor embebido
php tools/build-css.php             # regenera assets/css/styles.css
php tools/build-sitemap.php         # regenera sitemap.xml
```

`config.php` es la fuente única de verdad: todo el contenido visible sale de ese array. Si un dato no está en `config.php` (o está vacío), no aparece en la página.

## Reglas del repo

- **No editar a mano** `assets/css/styles.css` ni `sitemap.xml`: son artefactos generados; se editan los archivos fuente de `assets/css/` y se corre el build.
- Critical CSS va en `assets/css/critical.css` (se inyecta inline); mantener sincronizado con `navbar.css` / `hero.css` / `base.css`.
- Colores siempre vía variables `--color-*` (definidas en `config.php['colors']` + `base.css`).
- Assets en minúsculas con guiones, sin espacios ni acentos.
- `_local/` es documentación privada (contiene datos personales): no se versiona, no se copia al deploy y está bloqueado por `.htaccess`.

## Documentación

- [docs/README.md](docs/README.md) — índice de documentación y orden de lectura.
- [docs/estado-actual.md](docs/estado-actual.md) — estado, fixes aplicados y deuda pendiente.
- [docs/arquitectura.md](docs/arquitectura.md) — flujos, build y diagramas.
- [AGENTS.md](AGENTS.md) — guía técnica canónica para asistentes de código.

## Deploy

El deploy a ResellHost es manual (FTP). Después de cada push, subir los archivos del repo al docroot **sin** copiar `_local/`, `docs/_local`, `tools/`, `secrets.php` ni los PDFs si la bandera `libros_pdf_publico` es `false` (`.htaccess` los bloquea a nivel servidor).
