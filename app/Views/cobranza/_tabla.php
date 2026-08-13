<link href="<?= base_url('assets/css/Estilo.css') ?>" rel="stylesheet" type="text/css" />
<table align="left" cellspacing="2" class="tabla">
  <tr>
    <td width="50" class="tituloTabla">FOLIO</td>
    <td class="tituloTabla" width="150">TRABAJO</td>
    <td class="tituloTabla" width="200">CLIENTE</td>
    <td class="tituloTabla" width="215">DIRECCION</td>
    <td class="tituloTabla" width="150">COLONIA</td>
    <td class="tituloTabla" width="75">HONORARIOS</td>
    <td class="tituloTabla" width="55">ADEUDO</td>
    <td class="tituloTabla" width="65">&nbsp;</td>
  </tr>
</table>
<div id="scroll">
<table align="left" cellspacing="2" class="tabla" id="tab">
<?php foreach ($filas as $i => $f): ?>
<?php $color = (int) $f['Tipo'] === 1 ? '#FF9933' : ($i % 2 === 1 ? '#DAE6F3' : ''); ?>
  <tr <?= $color ? 'bgcolor="' . $color . '"' : '' ?> id="<?= $i + 1 ?>">
    <td width="70" class="celdasel"><?= esc($f['Folio']) ?></td>
    <td width="140" class="celdasel"><?= esc($f['Trabajo']) ?></td>
    <td width="180" class="celdasel"><?= esc($f['Cliente']) ?></td>
    <td width="200" class="celdasel"><?= esc($f['Direccion']) ?></td>
    <td width="140" class="celdasel"><?= esc($f['Colonia']) ?></td>
    <td width="125" class="celdasel"><?= number_format((float) $f['Honorarios'], 2) ?></td>
    <td width="80" class="celdasel"><?= number_format((float) $f['Saldo'], 2) ?></td>
    <td width="10" class="celdasel"><a href="<?= site_url('cobranza/' . $f['Folio'] . '/ver') ?>"><img src="<?= base_url('assets/img/document-preview.png') ?>" width="24" height="24" border="0" /></a></td>
    <td width="10" class="celdasel"><a href="<?= site_url('cobranza/' . $f['Folio'] . '/pagar') ?>"><img src="<?= base_url('assets/img/dinero1.gif') ?>" width="29" height="27" border="0" /></a></td>
  </tr>
<?php endforeach; ?>
</table>
</div>
