<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo">REPORTE DE AVALUOS POR TRABAJO</p>
<form id="form1" name="form1" method="post" action="<?= site_url('avaluos/reportes/general-trabajo/pdf') ?>" target="_blank">
<fieldset id="Documentos" style="border:0">
<table align="center" cellspacing="2" width="650" style="border:double; border-color:#1A3557;">
<tr>
  <td width="450" class="tituloTabla"></td>
  <td width="100" class="tituloTabla">Avaluos Solicitados</td>
  <td width="100" class="tituloTabla">Avaluos Terminados</td>
  <td width="100" class="tituloTabla">Avaluos Entregados</td>
  <td width="100" class="tituloTabla">Avaluos Cancelados</td>
</tr>
<?php foreach ($filas as $i => $f): ?>
<tr <?= $i % 2 === 1 ? 'bgcolor="#DAE6F3"' : '' ?>>
  <td width="100"><?= esc($f['Nombre']) ?></td>
  <td width="50" align="center"><?= $f['solicitados'] ?></td>
  <td width="50" align="center"><?= $f['terminados'] ?></td>
  <td width="50" align="center"><?= $f['entregados'] ?></td>
  <td width="50" align="center"><?= $f['cancelados'] ?></td>
</tr>
<?php endforeach; ?>
</table>
<input type="hidden" name="a" value="<?= $ano ?>" />
<p align="center">
  <input type="submit" value="Imprimir"/>
  <input type="button" value="Regresar" onclick="location.href='<?= site_url('avaluos/reportes/picker/4') ?>'" />
</p>
</fieldset>
</form>
<?= $this->endSection() ?>
