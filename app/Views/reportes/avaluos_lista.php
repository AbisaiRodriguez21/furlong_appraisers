<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo"><?= esc(strtoupper($titulo)) ?></p>
<p class="subtitulo">&nbsp;&nbsp;Total de Avaluos: <?= count($filas) ?></p>

<?php if ($filas === []): ?>
<p align="center">No hay información para el reporte</p>
<p align="center"><a href="<?= site_url('avaluos/reportes/picker/' . $tipo) ?>">Regresar</a></p>
<?php else: ?>
<form id="form1" name="form1" method="post" action="<?= site_url('avaluos/reportes/pdf') ?>" target="_blank">
<fieldset id="Documentos" style="border:0">
<div id="scroll">
<table align="center" cellspacing="2" class="tabla" id="tab">
  <tr>
    <td width="50" class="tituloTabla">FOLIO</td>
    <td class="tituloTabla" width="150">TRABAJO REALIZADO</td>
    <td class="tituloTabla" width="230">CLIENTE</td>
    <td class="tituloTabla" width="200">DIRECCION</td>
    <td class="tituloTabla" width="200">COLONIA</td>
  </tr>
<?php foreach ($filas as $i => $f): ?>
  <tr <?= $i % 2 === 1 ? 'bgcolor="#DAE6F3"' : '' ?>>
    <td width="50" class="celdasel"><?= esc($f['Folio']) ?></td>
    <td width="150" class="celdasel"><?= esc($f['Trabajo']) ?></td>
    <td width="230" class="celdasel"><?= esc($f['Cliente']) ?></td>
    <td width="200" class="celdasel"><?= esc($f['Direccion']) ?></td>
    <td width="200" class="celdasel"><?= esc($f['Colonia']) ?></td>
  </tr>
<?php endforeach; ?>
</table>
</div>
<input type="hidden" name="id" value="<?= $tipo ?>" />
<input type="hidden" name="Opc" value="<?= $opcion ?>" />
<?php if ($opcion === 1): ?>
<input type="hidden" name="Fecha1" value="<?= esc($f1) ?>" />
<input type="hidden" name="Fecha2" value="<?= esc($f2) ?>" />
<?php elseif ($opcion === 2): ?>
<input type="hidden" name="Anio" value="<?= esc($ano) ?>" />
<input type="hidden" name="Mes" value="<?= esc($mes) ?>" />
<?php elseif ($opcion === 3): ?>
<input type="hidden" name="Anio2" value="<?= esc($ano) ?>" />
<?php endif; ?>
<p align="center">
  <input type="submit" value="Imprimir"/>
  <?php if ($tipo !== 2 && $tipo !== 3): ?>
  <input type="button" value="Regresar" onclick="location.href='<?= site_url('avaluos/reportes/picker/' . $tipo) ?>'" />
  <?php else: ?>
  <input type="button" value="Regresar" onclick="location.href='<?= site_url('avaluos/reportes') ?>'" />
  <?php endif; ?>
</p>
</fieldset>
</form>
<?php endif; ?>
<?= $this->endSection() ?>
