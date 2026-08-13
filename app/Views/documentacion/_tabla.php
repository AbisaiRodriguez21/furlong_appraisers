<link href="<?= base_url('assets/css/Estilo.css') ?>" rel="stylesheet" type="text/css" />
<table class="tabla" align="left">
<tr class="tituloTabla">
  <td width="920" class="tituloTabla"><b>NOMBRE</b></td>
  <td width="40" class="tituloTabla"><b></b></td>
</tr>
</table>
<div id="scroll">
<table id="tab" class="tabla" align="left">
<?php foreach ($documentos as $i => $d): ?>
  <tr <?= $i % 2 === 0 ? 'bgcolor="#DAE6F3"' : '' ?>>
    <td width="900"><?= esc($d['Nombre']) ?></td>
    <td width="24"><a href="<?= site_url('documentacion/' . $d['IdDocumento'] . '/editar') ?>"><img src="<?= base_url('assets/img/edit.png') ?>" width="22" height="22" border="0" /></a></td>
    <td width="24"><a href="<?= site_url('documentacion/' . $d['IdDocumento'] . '/eliminar') ?>"><img src="<?= base_url('assets/img/document-close-3.png') ?>" width="24" height="24" border="0" /></a></td>
  </tr>
<?php endforeach; ?>
</table>
</div>
