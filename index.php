<?php
session_start();
require_once __DIR__ . '/config/init.php';
?>
<?php include 'header.php'; ?>
<body class="hold-transition login-page">
<div class="attendance-page">
  <div class="app-brand">
    <span class="brand-name"><?php echo defined('APP_NAME') ? h(APP_NAME) : 'Control de Asistencia'; ?></span>
  </div>

  <div class="card card-clock">
    <p id="date"></p>
    <p id="time" class="bold"></p>
  </div>

  <div class="card card-form">
    <h2 class="card-title">Registro de asistencia</h2>
    <form id="attendance">
      <div class="form-group">
        <select class="form-control" name="status" aria-label="Tipo de registro">
          <option value="in">Hora de entrada</option>
          <option value="out">Hora de salida</option>
        </select>
      </div>
      <div class="form-group has-feedback">
        <input type="text" class="form-control input-lg" id="employee" name="employee" placeholder="ID de empleado" required autocomplete="off">
        <span class="glyphicon glyphicon-user form-control-feedback" aria-hidden="true"></span>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary btn-block btn-attend" name="signin">
          <i class="fa fa-sign-in"></i> Registrar
        </button>
      </div>
    </form>

    <div class="alert alert-success alert-dismissible text-center" style="display:none;" role="status">
      <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">&times;</button>
      <span class="result"><i class="icon fa fa-check"></i> <span class="message"></span></span>
    </div>
    <div class="alert alert-danger alert-dismissible text-center" style="display:none;" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">&times;</button>
      <span class="result"><i class="icon fa fa-warning"></i> <span class="message"></span></span>
    </div>
  </div>
</div>

<?php include 'scripts.php' ?>
<script type="text/javascript">
$(function() {
  function updateClock() {
    var m = moment();
    $('#date').text(m.format('dddd').substring(0, 3).toUpperCase() + ' · ' + m.format('D [de] MMMM, YYYY'));
    $('#time').text(m.format('HH:mm:ss'));
  }
  updateClock();
  setInterval(updateClock, 1000);

  $('#attendance').on('submit', function(e) {
    e.preventDefault();
    var $form = $(this);
    var $btn = $form.find('button[type="submit"]');
    var data = $form.serialize();
    $btn.prop('disabled', true);
    $('.alert').hide();
    $.ajax({
      type: 'POST',
      url: 'attendance.php',
      data: data,
      dataType: 'json',
      success: function(response) {
        if (response.error) {
          $('.alert-danger .message').text(response.message);
          $('.alert-danger').show();
        } else {
          $('.alert-success .message').text(response.message);
          $('.alert-success').show();
          $('#employee').val('').focus();
        }
      },
      complete: function() {
        $btn.prop('disabled', false);
      }
    });
  });
});
</script>
</body>
</html>
