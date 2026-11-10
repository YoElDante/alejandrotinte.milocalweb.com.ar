# Alejandro Tinte — Sitio oficial

Sitio del escritor Alejandro Tinte: home con obra destacada, libros, proyectos, autor y contacto, más fichas individuales por libro (`/libros/{slug}`).

## Stack

- PHP plano, sin framework.
- CSS modular con build (`tools/build-css.php`).
- JS vanilla.
- `config.php` es la fuente única de datos.

## Estructura rápida

- `index.php` — home.
- `libro.php` — ficha individual.
- `router.php` — router del servidor embebido (desarrollo).
- `config.php` — libros, proyectos, SEO, dominio y aliases.
- `includes/` — bootstrap, header, footer, helpers y secciones.
- `_local/docs/` — documentación interna con datos personales (no publicar).
- `docs/` — estado, arquitectura, mapa del sitio y resumen.

## Comandos

```bash
php tools/build-css.php
php -S 127.0.0.1:8000 -t . router.php
```

## Documentación

Empezá por [`docs/README.md`](docs/README.md) y [`docs/estado-actual.md`](docs/estado-actual.md).
