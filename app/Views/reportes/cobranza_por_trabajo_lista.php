<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<form id="form1" name="form1" method="post" action="<?= site_url('cobranza/reportes/trabajo/pdf') ?>" target="_blank">
<input type="hidden" name="IdTrabajo" value="<?= $idTrabajo ?>" />
<input type="hidden" name="Anio" value="<?= $ano ?>" />
<p class="titulo">REPORTE DE COBRANZA POR TRABAJO</p>
<fieldset id="Documentos" style="border:0">
<table align="left" cellspacing="2" class="tabla">
  <tr><td class="tituloTabla" colspan="3"><?= esc($nombreTrab) ?></td></tr>
  <tr>
    <td width="88" class="tituloTabla">FOLIO</td>
    <td class="tituloTabla" width="785">CLIENTE</td>
    <td class="tituloTabla" width="87">MONTO</td>
  </tr>
</table>
<div id="scroll">
<table align="left" cellspacing="2" class="tabla" id="tab">
<?php $total = 0; ?>
<?php foreach ($filas as $i => $f): ?>
<?php $total += (float) $f['Importe']; ?>
  <tr <?= $i % 2 === 1 ? 'bgcolor="#DAE6F3"' : '' ?>>
    <td width="100" align="center" class="celdasel"><?= esc($f['Folio']) ?></td>
    <td width="760" class="celdasel"><?= esc($f['Cliente']) ?></td>
    <td width="100" align="right" class="celdasel"><?= number_format((float) $f['Importe'], 2) ?></td>
  </tr>
<?php endforeach; ?>
</table>
</div>
</fieldset>
<br />
<p align="right"><label class="subtitulo">Monto Total de Pagos: $<?= number_format($total, 2) ?></label></p>
<p align="center">
  <input type="submit" value="Imprimir"/>
  <input type="button" value="Regresar" onclick="location.href='<?= site_url('cobranza/reportes/trabajo') ?>'" />
</p>
</form>
<?= $this->endSection() ?>
