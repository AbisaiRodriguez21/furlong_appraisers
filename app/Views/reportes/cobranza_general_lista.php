<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo"><?= esc(strtoupper($titulo)) ?></p>
<form id="form1" name="form1" method="post" action="<?= site_url('cobranza/reportes/general/pdf') ?>" target="_blank">
<input type="hidden" name="Tipo" value="<?= $tipo ?>" />
<input type="hidden" name="Opc" value="<?= $opcion ?>" />
<?php if ($opcion === 1): ?>
<input type="hidden" name="semanas" value="<?= $semanaId ?>" />
<?php elseif ($opcion === 2): ?>
<input type="hidden" name="Anio" value="<?= esc($ano) ?>" />
<input type="hidden" name="Mes" value="<?= esc($mes) ?>" />
<?php elseif ($opcion === 3): ?>
<input type="hidden" name="Anio2" value="<?= esc($ano) ?>" />
<?php endif; ?>
<fieldset id="Documentos" style="border:0">
<table align="left" cellspacing="2" class="tabla">
  <tr>
    <td width="100" class="tituloTabla">FOLIO</td>
    <td class="tituloTabla" width="480">TRABAJO</td>
    <td class="tituloTabla" width="480">CLIENTE</td>
    <td class="tituloTabla" width="100"><?= $tipo === 0 ? 'MONTO' : 'ADEUDO' ?></td>
  </tr>
</table>
<div id="scroll">
<table align="left" cellspacing="2" class="tabla" id="tab">
<?php $total = 0; ?>
<?php foreach ($filas as $i => $f): ?>
<?php $total += $f['Cantidad']; ?>
<?php $color = (int) $f['Tipo'] === 1 ? '#FF9933' : ($i % 2 === 1 ? '#DAE6F3' : ''); ?>
  <tr <?= $color ? 'bgcolor="' . $color . '"' : '' ?>>
    <td width="118" align="center" class="celdasel"><?= esc($f['Folio']) ?></td>
    <td width="460" class="celdasel"><?= esc($f['Trabajo']) ?></td>
    <td width="465" class="celdasel"><?= esc($f['Cliente']) ?></td>
    <td width="117" align="right" class="celdasel"><?= number_format($f['Cantidad'], 2) ?></td>
  </tr>
<?php endforeach; ?>
</table>
</div>
</fieldset>
<br />
<p align="right"><label class="subtitulo"><?= $tipo === 0 ? 'Monto Total de Pagos: $' : 'Monto Total de Adeudos: $' ?><?= number_format($total, 2) ?></label></p>
<p align="center">
  <input type="submit" value="Imprimir"/>
  <input type="button" value="Regresar" onclick="location.href='<?= site_url('cobranza/reportes/general/' . $tipo) ?>'" />
</p>
</form>
<?= $this->endSection() ?>
