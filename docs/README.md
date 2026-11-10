# Documentación — Alejandro Tinte

Landing de autor de Alejandro Tinte (single-page más fichas por libro), construida en PHP plano sobre `config.php`; esta carpeta reúne el estado, la arquitectura y el mapa del sitio verificados el 2026-11-09.

## Orden de lectura

1. [Estado actual](estado-actual.md) — veredicto **NO LISTO**, cumplimiento 55,5/79 y SEO implementado.
2. [Arquitectura](arquitectura.md) — archivos, flujos, build de CSS y diagramas.
3. [Mapa del sitio](mapa-sitio.md) — rutas, anclas y sitemap.
4. [Resumen de documentación](resumen-documentacion.md) — qué se hizo en la sesión y cómo verificarlo.

## Documentos existentes

`_local/` está en `.gitignore`: estos enlaces funcionan en la copia de trabajo, no en un clon limpio del repositorio.

- [Ficha del cliente](../_local/docs/ficha-cliente.md) — datos canónicos del autor, catálogo, dominios y pendientes (documento privado, no versionado).
- [Perfil del autor](../_local/docs/perfil-autor.md) — biografía y hechos aportados por el autor.
- [Brief de redes](../_local/docs/brief-redes.md) — cuentas oficiales y personales, criterio de publicación.
- [Pendiente 301 de dominios alias](../_local/docs/pendiente-301-dominios-alias.md) — detalle del P0 de indexación.
- [Esqueleto de producto](../_local/docs/esqueleto-producto.md) — anatomía de una ficha de libro y checklist para sumar títulos.
- [Contexto del ecosistema del autor](../_local/docs/contexto-ecosistema-autor.md) — material de AGH Argentina, fuera de la landing.
- [AGENTS.md](../AGENTS.md) — guía técnica canónica del proyecto.
- [Auditoría 2026-11-09](../../../_audit/alejandroTinte.md) — informe completo con evidencia, puntaje y observaciones priorizadas.

## Para una IA

Leer primero `AGENTS.md` de la raíz, luego `estado-actual.md` y `arquitectura.md`.
No editar a mano `assets/css/styles.css` (lo genera `tools/build-css.php`) ni `sitemap.xml` (lo genera `tools/build-sitemap.php`).
No copiar `_local/` al docroot ni al deploy: contiene datos personales y hoy no está bloqueado por `.htaccess`.
