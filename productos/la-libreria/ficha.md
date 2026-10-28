# Ficha de Producto — La Librería

> Documento interno del libro. El texto de venta vive en `descripcion.txt`, en esta misma
> carpeta, y la web lo lee desde ahí. Acá van los datos comerciales y las decisiones
> editoriales; no se publica tal cual.

---

## Datos

| Campo | Valor |
|-------|-------|
| **Título** | La Librería |
| **Subtítulo** | — |
| **Autor** | Tinte Yañez, Alejandro |
| **Género** | Realismo fantástico / relato |
| **Formato** | Versión Kindle |
| **Páginas** | 17 (PDF del sitio) · pendiente edición impresa |
| **Medidas** | pendiente |
| **ISBN** | pendiente |
| **Edición** | pendiente |
| **Imprenta** | pendiente |
| **Precio** | pendiente |
| **Valoración** | pendiente |
| **Amazon (libro)** | https://www.amazon.es/dp/B0HF5569B5 |
| **Amazon (todos los libros)** | https://www.amazon.es/stores/author/B0HBS1BVK9/allbooks |
| **Portada** | `portada.webp` / `portada-600.webp` |
| **PDF** | `la-libreria.pdf` (no público) |
| **Estado** | Publicado en Kindle |

---

## Sinopsis para la tarjeta

La tarjeta usa el primer párrafo de `descripcion.txt` (el campo `sinopsis` de `config.php`
está vacío a propósito). Funciona solo como resumen:

> La Librería es un relato de realismo fantástico sobre la pérdida, la fe y la esperanza.

---

## Descripción larga

Archivo: `descripcion.txt` — 4 párrafos.

---

## Notas Editoriales

- **Género**: realismo fantástico, no terror ni policial. Clasificarlo así en la web y en el
  JSON-LD.
- **Tema central**: la fe como respuesta a la pérdida. Es el título más luminoso del catálogo;
  contrasta con *Sergio, el Marcado*.
- El texto original traía un error de tipeo: "cambiarlo para siempre.Emotiva" (sin espacio y
  sin salto de párrafo). Se corrigió al transcribir.
- **Spoiler**: la descripción no revela la revelación final, y hace bien. No agregar detalles
  del desenlace.
- La sinopsis usa la segunda persona implícita ("Pablo camina...") y cierra con una frase
  editorial. Mantener ese registro si se reescribe.

---

## Pendientes

- [ ] ISBN, precio, medidas, edición y año.
- [ ] Confirmar páginas de la edición impresa (17 es el PDF).
- [ ] Link corto/limpio de Amazon: verificar que `amazon.es/dp/B0HF5569B5` resuelva bien.
