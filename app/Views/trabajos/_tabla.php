<link href="<?= base_url('assets/css/Estilo.css') ?>" rel="stylesheet" type="text/css" />
<table class="tabla" align="left">
<tr class="tituloTabla">
  <td width="300" class="tituloTabla"><b>NOMBRE</b></td>
  <td width="600" class="tituloTabla"><b>DESCRIPCION</b></td>
  <td width="70" class="tituloTabla"><b></b></td>
</tr>
</table>
<div id="scroll">
<table id="tab" class="tabla" align="left">
<?php foreach ($trabajos as $i => $t): ?>
  <tr <?= $i % 2 === 0 ? 'bgcolor="#DAE6F3"' : '' ?>>
    <td width="300"><?= esc($t['Nombre']) ?></td>
    <td width="600"><?= esc($t['Descripcion']) ?></td>
    <td width="10"><a href="<?= site_url('trabajos/' . $t['IdTrabajo']) ?>"><img src="<?= base_url('assets/img/document-preview.png') ?>" width="24" height="24" border="0" /></a></td>
    <td width="10"><a href="<?= site_url('trabajos/' . $t['IdTrabajo'] . '/editar') ?>"><img src="<?= base_url('assets/img/edit.png') ?>" width="22" height="22" border="0" /></a></td>
    <td width="10"><a href="<?= site_url('trabajos/' . $t['IdTrabajo'] . '/eliminar') ?>"><img src="<?= base_url('assets/img/document-close-3.png') ?>" width="24" height="24" border="0" /></a></td>
  </tr>
<?php endforeach; ?>
</table>
</div>
