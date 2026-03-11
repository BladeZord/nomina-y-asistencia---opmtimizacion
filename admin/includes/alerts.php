<?php
/**
 * Partial: alertas de sesión (éxito / error). Incluir dentro de section.content.
 * Usa variable $flavor para tipo: 'success' o 'danger'. Mensaje en $_SESSION['success'] o $_SESSION['error'].
 */
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible">';
    echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
    echo '<h4><i class="icon fa fa-warning"></i> Error</h4>';
    echo h($_SESSION['error']);
    echo '</div>';
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible">';
    echo '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
    echo '<h4><i class="icon fa fa-check"></i> Proceso exitoso</h4>';
    echo h($_SESSION['success']);
    echo '</div>';
    unset($_SESSION['success']);
}
