<link href="<?= base_url('assets/css/Estilo.css') ?>" rel="stylesheet" type="text/css" />
<table align="left" cellspacing="2" class="tabla">
  <tr>
    <td width="600" class="tituloTabla">NOMBRE</td>
    <td width="130" class="tituloTabla">TELÉFONO</td>
    <td width="130" class="tituloTabla">CELULAR</td>
    <td width="70" class="tituloTabla">&nbsp;</td>
  </tr>
</table>
<div id="scroll">
<table align="left" cellspacing="2" class="tabla" id="tab">
<?php foreach ($usuarios as $i => $u): ?>
  <tr <?= $i % 2 === 1 ? 'bgcolor="#DAE6F3"' : '' ?>>
    <td width="630" class="celdasel"><?= esc($u['Nombre']) ?></td>
    <td width="150" class="celdasel" align="center"><?= esc($u['Telefono']) ?></td>
    <td width="150" class="celdasel" align="center"><?= esc($u['Celular']) ?></td>
    <td width="10" class="celdasel"><a href="<?= site_url('usuarios/' . $u['IdUsuario']) ?>"><img src="<?= base_url('assets/img/document-preview.png') ?>" width="24" height="24" border="0" /></a></td>
    <td width="10" class="celdasel"><a href="<?= site_url('usuarios/' . $u['IdUsuario'] . '/editar') ?>"><img src="<?= base_url('assets/img/edit.png') ?>" width="22" height="22" border="0" /></a></td>
    <td width="10" class="celdasel"><a href="<?= site_url('usuarios/' . $u['IdUsuario'] . '/eliminar') ?>"><img src="<?= base_url('assets/img/document-close-3.png') ?>" width="24" height="24" border="0" /></a></td>
  </tr>
<?php endforeach; ?>
</table>
</div>
