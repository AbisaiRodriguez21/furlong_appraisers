<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Acceso denegado</title>
<link href="<?= base_url('assets/css/Estilo.css') ?>" rel="stylesheet" type="text/css" />
</head>
<body marginheight="0" topmargin="0">
<div style="max-width:480px;margin:120px auto;text-align:center;font-family:Arial, Helvetica, sans-serif;">
<h2>Acceso denegado</h2>
<p>Tu usuario no tiene permiso para acceder a esta sección del sistema.</p>
<p><a href="<?= site_url('/') ?>">Volver al sistema</a> &nbsp;|&nbsp; <a href="<?= site_url('auth/logout') ?>">Cerrar sesión</a></p>
</div>
</body>
</html>
