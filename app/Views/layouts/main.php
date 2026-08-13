<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">
<title><?= $titulo ?? 'Furlong Appraisers' ?></title>
<link href="<?= base_url('assets/css/Estilo.css') ?>" rel="stylesheet" type="text/css" />
<?= $this->renderSection('head') ?>
</head>
<body marginheight="0" topmargin="0">
<div id="logo"></div>
<div id="contenedor">
<ul id="menu">
    <li><a href="<?= site_url('usuarios') ?>">Usuarios</a></li>
    <li><a href="<?= site_url('clientes') ?>">Clientes</a></li>
    <li><a href="<?= site_url('trabajos') ?>">Trabajos a Realizar</a></li>
    <li><a href="<?= site_url('avaluos') ?>">Avalúos</a></li>
    <li><a href="<?= site_url('cobranza') ?>">Cobranza</a></li>
    <li><a href="<?= site_url('documentacion') ?>">Documentación</a></li>
    <li><a href="<?= site_url('ayuda') ?>">Ayuda</a></li>
</ul>
<br />
<br />
<p class="cerrarsesion"><a href="<?= site_url('auth/logout') ?>"><img src="<?= base_url('assets/img/cerrar_sesion.png') ?>" width="38" height="38" border="0"/></a></p>

<?= $this->renderSection('contenido') ?>

</div>
</body>
</html>
