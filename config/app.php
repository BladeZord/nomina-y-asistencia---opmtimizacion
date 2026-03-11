<?php
/**
 * Configuración general de la aplicación.
 * Valores sensibles pueden sobreescribirse con variables de entorno.
 */
if (!defined('APP_CONFIG_LOADED')) {
    define('APP_CONFIG_LOADED', true);
}

// Nombre de la aplicación (configurable por entorno)
define('APP_NAME', getenv('APP_NAME') ?: 'Control de Asistencia y Sistema de Nómina');

// Zona horaria (configurable por entorno)
$app_timezone = getenv('APP_TIMEZONE') ?: 'America/Guayaquil';
date_default_timezone_set($app_timezone);

// Charset por defecto
define('APP_CHARSET', 'UTF-8');

// Modo depuración (no activar en producción)
define('APP_DEBUG', filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN) ?: false);
