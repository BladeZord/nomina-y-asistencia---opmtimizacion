<?php
session_start();
if (isset($_SESSION['admin'])) {
    header('Location: home.php');
    exit;
}
require_once __DIR__ . '/../config/init.php';
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition login-page">
<div class="login-page-body">
  <div class="login-box">
    <div class="login-logo">
      <b>Panel de administración</b>
    </div>

    <div class="login-box-body">
      <p class="login-box-msg">Ingresa con tu cuenta para gestionar el sistema</p>

      <form action="login.php" method="POST">
        <?php csrf_field(); ?>
        <div class="form-group has-feedback">
          <input type="text" class="form-control" name="username" placeholder="Usuario" required autofocus>
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block btn-flat" name="login">
            <i class="fa fa-sign-in"></i> Ingresar
          </button>
        </div>
      </form>

      <?php if (isset($_SESSION['error'])) { ?>
        <div class="callout callout-danger text-center mt20">
          <p><?php echo h($_SESSION['error']); ?></p>
        </div>
      <?php unset($_SESSION['error']); } ?>
    </div>
  </div>
</div>

<?php include 'includes/scripts.php' ?>
</body>
</html>