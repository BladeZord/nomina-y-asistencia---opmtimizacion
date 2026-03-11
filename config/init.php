<?php
/**
 * Inicialización central: carga configuración de aplicación y base de datos.
 * Incluir este archivo una sola vez al inicio de cada script que necesite $conn o timezone.
 */
require_once __DIR__ . '/app.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/helpers.php';
