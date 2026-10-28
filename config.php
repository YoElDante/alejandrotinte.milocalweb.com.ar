<?php
/**
 * Configuración del cliente — Landing Page.
 *
 * Este archivo es la ÚNICA fuente de datos del sitio.
 * Todo lo marcado con TODO queda pendiente del relevamiento con el cliente.
 *
 * @package MiLocalWeb\Clientes
 */

return [

    // ─── Datos básicos ───────────────────────────────────────────────

    'nombre'           => 'Alejandro Tinte',
    'slogan'           => 'Historias que dejan marca',
    'rubro'            => 'Escritor',
    'whatsapp'         => '5491170601270',
    'email'            => '', // TODO: email de contacto
    'whatsapp_mensaje' => 'Hola Alejandro! Vi tu web y quisiera consultar por tus libros',

    // ─── Hero Section ────────────────────────────────────────────────

    'hero_kicker'      => 'Sitio oficial',
    'hero_descripcion' => 'Novelas, relatos y personajes que se quedan con vos. Conocé la obra completa y escribile directamente al autor.',
    'hero_boton_ppal'  => 'Ver las obras',
    'hero_boton_sec'   => 'Contactar al autor',
    'hero_imagen'        => '/assets/img/cliente/escritor/cafe-de-otono-y-reflexion-en-bsas.webp',
    'hero_imagen_mobile' => '/assets/img/cliente/escritor/momento-contemplativo-en-el-cafe.webp',

    // ─── Identidad Visual ────────────────────────────────────────────
    // Paleta neutra provisoria: reemplazar cuando exista el informe de estilo.
    // Se inyecta como variables CSS desde header.php.

    'colors' => [
        'color-primary'       => '#C9A227',
        'color-primary-hover' => '#E0BC4A',
        'color-primary-soft'  => 'rgba(201, 162, 39, 0.10)',
        'color-primary-glow'  => 'rgba(201, 162, 39, 0.30)',
        'color-accent'        => '#B4552D',
        'color-accent-hover'  => '#D06D42',
        'color-text'          => '#F5F0E6',
        'color-muted'         => '#B3A99A',
        'color-bg'            => '#14110F',
        'color-bg-alt'        => '#1B1714',
        'color-card-bg'       => '#1F1A16',
        'color-line'          => 'rgba(245, 240, 230, 0.10)',
        'color-hero-bg-start' => '#14110F',
        'color-hero-bg-end'   => '#241C15',
    ],
    'tipografia_titulos' => "Georgia, 'Times New Roman', serif",
    'tipografia_texto'   => "system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif",

    // ─── Rutas de imágenes ───────────────────────────────────────────
    // Dejar vacío mientras el cliente no tenga logo/favicon propios.

    'logo_img' => '',
    'favicon'  => '',

    // ─── Libros ──────────────────────────────────────────────────────
    // `pdf` y `portada` son rutas relativas a la raíz del sitio.
    // `sinopsis` y `anio` quedan vacíos hasta el relevamiento.

    'libros_intro' => 'Siete títulos más. Elegí por dónde seguir.',

    'libros' => [
        [
            'titulo'      => 'A Fuego y Fierro',
            'subtitulo'   => 'Una reescritura ética del Martín Fierro, del campo a la ciudad',
            'slug'        => 'a-fuego-y-fierro',
            'anio'        => '',
            'paginas'     => 39,
            'sinopsis'    => 'Una relectura contemporánea del Martín Fierro: la voz del gaucho en el lenguaje de hoy, con las mismas preguntas sobre la injusticia, el poder y el desarraigo.',
            'portada'     => '/productos/a-fuego-y-fierro/portada.webp',
            'portada_600' => '/productos/a-fuego-y-fierro/portada-600.webp',
            'pdf'         => '/productos/a-fuego-y-fierro/a-fuego-y-fierro.pdf',
        ],
        [
            'titulo'      => 'Cuestión de Fe',
            'slug'        => 'cuestion-de-fe',
            'anio'        => '',
            'paginas'     => 16,
            'sinopsis'    => '',
            'portada'     => '/productos/cuestion-de-fe/portada.webp',
            'portada_600' => '/productos/cuestion-de-fe/portada-600.webp',
            'pdf'         => '/productos/cuestion-de-fe/cuestion-de-fe.pdf',
        ],
        [
            'titulo'      => '¿Dónde carajos está el atizador?',
            'slug'        => 'donde-carajos-esta-el-atizador',
            'anio'        => '',
            'paginas'     => 20,
            'sinopsis'    => '',
            'portada'     => '/productos/donde-carajos-esta-el-atizador/portada.webp',
            'portada_600' => '/productos/donde-carajos-esta-el-atizador/portada-600.webp',
            'pdf'         => '/productos/donde-carajos-esta-el-atizador/donde-carajos-esta-el-atizador.pdf',
        ],
        [
            'titulo'      => 'El Juguete',
            'slug'        => 'el-juguete',
            'anio'        => '',
            'paginas'     => 12,
            'sinopsis'    => '',
            'portada'     => '/productos/el-juguete/portada.webp',
            'portada_600' => '/productos/el-juguete/portada-600.webp',
            'pdf'         => '/productos/el-juguete/el-juguete.pdf',
        ],
        [
            'titulo'      => 'La Librería',
            'slug'        => 'la-libreria',
            'anio'        => '',
            'paginas'     => 17,
            'sinopsis'    => '',
            'portada'     => '/productos/la-libreria/portada.webp',
            'portada_600' => '/productos/la-libreria/portada-600.webp',
            'pdf'         => '/productos/la-libreria/la-libreria.pdf',
        ],
        [
            'titulo'      => 'La Perla del Irupé',
            'slug'        => 'la-perla-del-irupe',
            'anio'        => '',
            'paginas'     => 17,
            'sinopsis'    => '',
            'portada'     => '/productos/la-perla-del-irupe/portada.webp',
            'portada_600' => '/productos/la-perla-del-irupe/portada-600.webp',
            'pdf'         => '/productos/la-perla-del-irupe/la-perla-del-irupe.pdf',
        ],
        [
            'titulo'      => 'Perder el Miedo',
            'slug'        => 'perder-el-miedo',
            'anio'        => '',
            'paginas'     => 8,
            'sinopsis'    => '',
            'portada'     => '/productos/perder-el-miedo/portada.webp',
            'portada_600' => '/productos/perder-el-miedo/portada-600.webp',
            'pdf'         => '/productos/perder-el-miedo/perder-el-miedo.pdf',
        ],
        [
            'titulo'      => 'Sergio, el Marcado',
            'slug'        => 'sergio-el-marcado',
            'anio'        => '',
            'paginas'     => 508,
            'sinopsis'    => '',
            'portada'     => '/productos/sergio-el-marcado/portada.webp',
            'portada_600' => '/productos/sergio-el-marcado/portada-600.webp',
            'pdf'         => '/productos/sergio-el-marcado/sergio-el-marcado.pdf',
        ],
    ],

    // Mientras sea false, los libros no exponen el PDF completo: el CTA
    // principal consulta por WhatsApp. Poner en true solo si el autor
    // autoriza la descarga libre.
    'libros_pdf_publico' => false,

    // ─── Obra destacada ─────────────────────────────────────────────
    // Slug del libro que ocupa la sección destacada (100vh) y se
    // excluye de la grilla. Dejar vacío para no destacar ninguno.

    'libro_destacado' => 'a-fuego-y-fierro',

    // ─── Amazon ──────────────────────────────────────────────────────
    // Links de venta, indexados por slug. Todavía no se usan en la UI:
    // están listos para el CTA "Comprar en Amazon".

    'amazon_autor' => 'https://www.amazon.es/stores/author/B0HBS1BVK9/allbooks',
    'amazon_perfil' => 'https://www.amazon.es/stores/author/B0HBS1BVK9',

    'amazon' => [
        'a-fuego-y-fierro'               => 'https://www.amazon.es/dp/B0HBRDFZB3',
        'sergio-el-marcado'              => 'https://www.amazon.es/dp/B0HHLNPG4W',
        'cuestion-de-fe'                 => 'https://www.amazon.es/dp/B0HBSVFQJG',
        'el-juguete'                     => 'https://www.amazon.es/dp/B0HBXM93P8',
        'la-libreria'                    => 'https://www.amazon.es/dp/B0HF5569B5',
        'la-perla-del-irupe'             => 'https://www.amazon.es/dp/B0HBRJ9JWJ',
        'perder-el-miedo'                => 'https://www.amazon.es/dp/B0HF3BHWYX',
        'donde-carajos-esta-el-atizador' => 'https://www.amazon.es/dp/B0HCSV4G62',
    ],

    // ─── Sobre el autor ──────────────────────────────────────────────

    'autor_foto' => '/assets/img/cliente/escritor/retrato-sereno-junto-al-mar.webp',
    'autor_imagen' => '/assets/img/cliente/escritor/lectura-en-el-cafe-a-fuego-y-fierro.webp',
    'autor_cta_texto' => 'Te invito a conocer mi mayor obra',

    'autor_bio' => [
        'Alejandro Tinte nació el viernes 13 de abril de 1962 en Buenos Aires, en la Maternidad Pardo del viejo Hospital de Clínicas. Empezó a trabajar temprano: fue cadete en la Editorial Estrada y, antes de cumplir los treinta, llegó a inspector de cambios en el Banco Central.',
        'Pero los fierros podían más. Aprendió a conducir, se quedó con el oficio y manejó un taxi durante más de veinte años. En 2003, después del corralito, la familia se radicó en una chacra de Paysandú, Uruguay, para empezar de nuevo. Puso el lugar en condiciones, volvió a Buenos Aires y siguió al volante hasta 2008, cuando se instaló definitivamente en el campo.',
        'Fue allí donde empezó todo: al convivir con otro léxico, corrigió su manera de hablar hasta hacerse entender y, con la misma paciencia, empezó a escribir. Volvió a su Buenos Aires querido a fines de 2023, dejó la app de viajes a los seis meses y se dedicó a ordenar las innumerables páginas impresas e inconexas que había acumulado. A Fuego y Fierro abre el camino a más de una década de escritos inéditos, entre ellos La Perla del Irupé, Cuestión de Fe, La Librería y Sergio, el Marcado.',
    ],

    // ─── Proyectos en curso ─────────────────────────────────────────
    // Sección "Proyectos en curso": libros en producción y HacemeGancho.
    // La donación cae a WhatsApp si `proyectos_donacion_url` está vacío.

    'proyectos_visible' => true,

    'proyectos' => [
        'intro' => 'Además de los libros publicados, hay trabajo en camino.',
        'libros' => [
            [
                'titulo'      => 'El Candidato',
                'descripcion' => 'Una novela sobre el poder y quien lo busca: liderazgo, gobernanza y la maquinaria que decide quién ocupa un lugar.',
            ],
            [
                'titulo'      => 'Raza Sin Dios',
                'descripcion' => 'Ciencia ficción: una hipótesis sobre el origen y la evolución de la humanidad, contada desde el borde de lo posible.',
            ],
        ],
        'hacemegancho' => [
            'nombre'      => 'HacemeGancho',
            'descripcion' => 'Un sistema de interacción humana que ordena los vínculos: cada proceso queda documentado, con roles, etapas y trazabilidad. En desarrollo activo.',
            'imagen'      => '/assets/img/cliente/proyectos/hacemegancho-logo-900x600.webp',
            'url'         => 'https://hacemegancho.com/',
        ],
    ],

    'proyectos_whatsapp_mensaje'  => 'Hola! Quiero sumarme al desarrollo de HacemeGancho o aportar ideas',
    'proyectos_donacion_url'      => '', // TODO: link de Cafecito / MercadoPago / similar
    'proyectos_donacion_mensaje'  => 'Hola! Quiero hacer un aporte para el desarrollo de HacemeGancho',
    'proyectos_donacion_texto'    => 'Tu nombre queda plasmado en los créditos de la aplicación.',

    // ─── Contacto ────────────────────────────────────────────────────

    'contacto_texto' => 'Para consultas por ejemplares, presentaciones o derechos, escribile directamente al autor.',

    // ─── Redes Sociales ──────────────────────────────────────────────

    'redes' => [
        'instagram' => 'https://www.instagram.com/aletinte0/',
        'facebook'  => 'https://www.facebook.com/alejandro.tinte.7',
        'linkedin'  => 'https://ar.linkedin.com/in/alejandro-sergio-tinte-y%C3%A1%C3%B1ez-256552291',
        'tiktok'    => '',
        'web'       => '',
    ],

    // ─── Datos para SEO ─────────────────────────────────────────────

    'seo_keywords_primarias' => 'Libros y relatos',
    'seo_descripcion'        => 'Sitio oficial del escritor Alejandro Tinte. Conocé sus libros, sus historias y contactalo directamente.',
    'seo_og_image'           => '', // TODO: imagen 1200x630 para compartir
    'dominio'                => 'alejandrotinte.milocalweb.com.ar',

    // ─── Dominios ────────────────────────────────────────────────────
    // `dominio` (arriba) es el canónico: lo usan el sitemap y las URLs
    // absolutas. Los alias apuntan al mismo public_html y deben
    // redirigir 301 al canónico desde .htaccess / hPanel.

    'dominios_alias' => [
        'aletinte.autor.milocalweb.com.ar',
        'aletinte.escritor.milocalweb.com.ar',
        'escritoralejandrotinte.milocalweb.com.ar',
        'escritortinte.milocalweb.com.ar',
        'tinteautor.milocalweb.com.ar',
    ],

    // ─── Aside Publicitario ──────────────────────────────────────────

    'aside_visible' => true,

    // ─── Otros Clientes ──────────────────────────────────────────────
    // Activar cuando estén los logos de otros clientes del portfolio.

    'mostrar_clientes'    => false,
    'clientes_encabezado' => 'Otros negocios que confían en MiLocalWeb',
    'clientes'            => [],

];
