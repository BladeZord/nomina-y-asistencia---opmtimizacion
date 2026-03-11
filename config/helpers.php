<?php
/**
 * Funciones auxiliares globales: escape XSS, CSRF, validaciones.
 */

/**
 * Escapa salida para HTML (protección XSS).
 * @param string|null $text
 * @return string
 */
function h($text) {
    return htmlspecialchars((string) $text, ENT_QUOTES, APP_CHARSET);
}

/**
 * Genera o devuelve el token CSRF de la sesión.
 * @return string
 */
function csrf_token() {
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

/**
 * Imprime un input hidden con el token CSRF para formularios.
 */
function csrf_field() {
    echo '<input type="hidden" name="_csrf_token" value="' . h(csrf_token()) . '">';
}

/**
 * Valida el token CSRF enviado por POST.
 * @return bool
 */
function validate_csrf() {
    $token = $_POST['_csrf_token'] ?? '';
    return $token !== '' && hash_equals(csrf_token(), $token);
}

/**
 * Extensiones permitidas para subida de fotos de empleados.
 */
define('ALLOWED_PHOTO_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);

/**
 * Valida y guarda una foto subida; devuelve el nombre de archivo seguro o null.
 * @param array $file elemento $_FILES['photo']
 * @param string $uploadDir directorio destino (ej: __DIR__ . '/../images')
 * @return string|null nombre del archivo guardado o null si no hay archivo o es inválido
 */
function handle_photo_upload($file, $uploadDir) {
    if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_PHOTO_EXTENSIONS, true)) {
        return null;
    }
    $filename = 'emp_' . bin2hex(random_bytes(8)) . '.' . $ext;
    $path = rtrim($uploadDir, '/') . '/' . $filename;
    if (move_uploaded_file($file['tmp_name'], $path)) {
        return $filename;
    }
    return null;
}
