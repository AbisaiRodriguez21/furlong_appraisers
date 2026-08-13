<link href="<?= base_url('assets/css/Estilo.css') ?>" rel="stylesheet" type="text/css" />
<table align="left" cellspacing="2" class="tabla">
  <tr>
    <td width="55" class="tituloTabla">FOLIO</td>
    <td class="tituloTabla" width="155">TRABAJO</td>
    <td class="tituloTabla" width="235">CLIENTE</td>
    <td class="tituloTabla" width="270">DIRECCION</td>
    <td class="tituloTabla" width="160">COLONIA</td>
    <td class="tituloTabla" width="75"></td>
  </tr>
</table>
<div id="scroll">
<table align="left" cellspacing="2" class="tabla" id="tab">
<?php foreach ($avaluos as $i => $a): ?>
<?php $color = $estatus[(int) $a['Status']]['color'] ?? '#FFFFFF'; ?>
<?php $nombreStatus = $estatus[(int) $a['Status']]['nombre'] ?? ''; ?>
  <tr bgcolor="<?= $color ?>" id="<?= $i + 1 ?>">
    <td width="70" class="celdasel"><?= esc($a['Folio']) ?></td>
    <td width="160" class="celdasel"><?= esc($a['Trabajo']) ?></td>
    <td width="230" class="celdasel"><?= esc($a['Cliente']) ?></td>
    <td width="270" class="celdasel"><?= esc($a['Direccion']) ?></td>
    <td width="160" class="celdasel"><?= esc($a['Colonia']) ?></td>
    <td width="22" class="celdasel"><a href="<?= site_url('avaluos/' . $a['Folio']) ?>"><img src="<?= base_url('assets/img/document-preview.png') ?>" width="24" height="24" border="0" /></a></td>
    <td width="22" class="celdasel"><a href="<?= site_url('avaluos/' . $a['Folio'] . '/editar') ?>"><img src="<?= base_url('assets/img/edit.png') ?>" width="22" height="22" border="0"/></a></td>
    <td width="22" class="celdasel"><img src="<?= base_url('assets/img/document-close-3.png') ?>" width="24" height="24" border="0" onclick="pregunta('<?= esc($a['Folio'], 'js') ?>','<?= esc($nombreStatus, 'js') ?>')"/></td>
  </tr>
<?php endforeach; ?>
</table>
</div>
