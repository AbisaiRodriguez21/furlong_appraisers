<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<script type="text/javascript">
function cancelado() {
    alert('No se Puede Modificar un recibo Cancelado');
}
function cancelado2() {
    alert('Este recibo ya ha sido Cancelado');
}
function filtra(txt) {
    buscarEnTablaMultiColumna(txt, 'dato', 'tab', [0, 1, 2, 3]);
}
</script>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
<p class="titulo">RECIBOS DE HONORARIOS</p>

<?php if (session('mensaje')): ?>
<script>alert('<?= esc(session('mensaje'), 'js') ?>')</script>
<?php endif; ?>
<?php if (session('error')): ?>
<script>alert('<?= esc(session('error'), 'js') ?>')</script>
<?php endif; ?>

<form id="form1" name="form1" method="post" action="">
<p>
<table align="left" cellspacing="2" class="tablaB">
<tr>
  <td><label for="dato">Buscar: </label><input name="dato" type="text" id="dato" size="50" onkeyup="filtra(this.value)"/></td>
  <td align="right"><a href="<?= site_url('recibos/nuevo') ?>"><img src="<?= base_url('assets/img/agregarB.png') ?>" width="122" height="43" border="0"/></a></td>
</tr>
</table>
</p>
<fieldset id="Documentos" style="border:0" class="m10x">
<table align="left" cellspacing="2" class="tabla">
  <tr>
    <td width="45" class="tituloTabla">FOLIO</td>
    <td width="154" class="tituloTabla">FECHA</td>
    <td class="tituloTabla" width="310">CLIENTE</td>
    <td class="tituloTabla" width="300">CONCEPTO</td>
    <td class="tituloTabla" width="80">CANTIDAD</td>
    <td class="tituloTabla" width="80">&nbsp;</td>
  </tr>
</table>
<div id="scroll">
<table align="left" cellspacing="2" class="tabla" id="tab">
<?php foreach ($recibos as $i => $r): ?>
<?php $cancelado = (int) $r['Status'] === 1; ?>
  <tr <?= $cancelado ? 'bgcolor="#FF2828"' : ($i % 2 === 1 ? 'bgcolor="#DAE6F3"' : '') ?>>
    <td width="65" class="celdasel" valign="top"><?= esc($r['NoFolio']) ?></td>
    <td width="165" class="celdasel" valign="top"><?= esc($r['Fecha']) ?></td>
    <td width="310" class="celdasel" valign="top"><?= esc($r['Cliente']) ?></td>
    <td width="300" class="celdasel" valign="top"><?= esc($r['Concepto']) ?></td>
    <td width="100" class="celdasel" align="right"><?= number_format((float) $r['Cantidad'], 2) ?></td>
    <td width="24" class="celdasel" valign="top"><a href="<?= site_url('recibos/' . $r['NoFolio']) ?>"><img src="<?= base_url('assets/img/document-preview.png') ?>" width="24" height="24" border="0" /></a></td>
    <td width="22" class="celdasel" valign="top">
      <?php if ($cancelado): ?>
      <img src="<?= base_url('assets/img/edit.png') ?>" width="22" height="22" border="0" onclick="cancelado()" />
      <?php else: ?>
      <a href="<?= site_url('recibos/' . $r['NoFolio'] . '/editar') ?>"><img src="<?= base_url('assets/img/edit.png') ?>" width="22" height="22" border="0" /></a>
      <?php endif; ?>
    </td>
    <td width="22" class="celdasel" valign="top">
      <?php if ($cancelado): ?>
      <img src="<?= base_url('assets/img/document-close-3.png') ?>" width="24" height="24" border="0" onclick="cancelado2()" />
      <?php else: ?>
      <a href="<?= site_url('recibos/' . $r['NoFolio'] . '/cancelar') ?>"><img src="<?= base_url('assets/img/document-close-3.png') ?>" width="24" height="24" border="0" /></a>
      <?php endif; ?>
    </td>
  </tr>
<?php endforeach; ?>
</table>
</div>
</fieldset>
</form>
<?= $this->endSection() ?>
