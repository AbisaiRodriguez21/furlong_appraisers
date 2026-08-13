<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">
<title>Inicio de Sesión</title>
<style type="text/css">
body {
    background-color: #152C49;
    color: #FFF;
    font-size:16px;
    font-family:Arial, Helvetica, sans-serif;
}
.titulo { font-size:18px; }
</style>
</head>
<body marginheight="0" topmargin="0">
<br />
<br />
<form action="<?= site_url('auth/attempt') ?>" method="post">
<?php if (($error ?? null) === '1'): ?>
    Datos incorrectos
<?php else: ?>
    Ingrese sus Datos
<?php endif; ?>
<table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><center><img src="<?= base_url('assets/img/logoIncio.jpg') ?>" width="272" height="93" /></center></td>
  </tr>
</table>
<br />
<table width="600" border="0" align="center" cellpadding="0" cellspacing="5">
  <tr>
    <td colspan="4" align="center" class="titulo">Ingrese sus datos<br /><br /></td>
  </tr>
  <tr>
    <td width="110">&nbsp;</td>
    <td width="150" align="right">Usuario:</td>
    <td width="150">
      <input name="login" type="text" id="login" maxlength="12" size="20" onkeyup="this.value=this.value.toUpperCase()"/></td>
    <td width="190">&nbsp;</td>
  </tr>
  <tr>
    <td width="110">&nbsp;</td>
    <td width="150" align="right">Contraseña:</td>
    <td width="150"><input type="password" name="password" id="password" maxlength="15" size="20"/></td>
    <td width="190">&nbsp;</td>
  </tr>
  <tr>
    <td width="110">&nbsp;</td>
    <td colspan="2" align="center"><br /><input type="submit" name="button" id="button" value="Entrar" /></td>
    <td width="190">&nbsp;</td>
  </tr>
</table>
</form>
</body>
</html>
