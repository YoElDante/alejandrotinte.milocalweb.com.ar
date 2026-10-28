<?php
/**
 * Landing Page — Alejandro Tinte.
 *
 * Single page: Hero, Obra destacada, Libros, Aside, Proyectos, Autor, Contacto
 * y Clientes. Para personalizar, editá config.php con los datos del cliente.
 *
 * @package MiLocalWeb\Clientes
 */

require_once __DIR__ . '/includes/bootstrap.php';

require_once __DIR__ . '/includes/header.php';
?>

<?php require_once __DIR__ . '/includes/sections/hero.php'; ?>
<?php require_once __DIR__ . '/includes/sections/destacado.php'; ?>
<?php require_once __DIR__ . '/includes/sections/libros.php'; ?>
<?php require_once __DIR__ . '/includes/sections/aside.php'; ?>
<?php require_once __DIR__ . '/includes/sections/proyectos.php'; ?>
<?php require_once __DIR__ . '/includes/sections/autor.php'; ?>
<?php require_once __DIR__ . '/includes/sections/contacto.php'; ?>
<?php require_once __DIR__ . '/includes/sections/clientes.php'; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
