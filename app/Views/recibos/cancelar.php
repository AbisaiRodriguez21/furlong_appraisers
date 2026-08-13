<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<script type="text/javascript" src="<?= base_url('assets/js/ReciboHonorarios.js') ?>"></script>
<link href="<?= base_url('assets/css/impresion.css') ?>" rel="stylesheet" type="text/css" media="print"/>
<script type="text/javascript">
function validacion(Opcion) {
    if (Opcion == 1 && document.form1.motivoC.value.length == 0) {
        alert('Ingrese motivo de cancelación');
        document.form1.motivoC.focus();
        return;
    }
    document.form1.opc.value = Opcion;
    document.form1.submit();
}
</script>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
<?php if (session('error')): ?>
<script>alert('<?= esc(session('error'), 'js') ?>')</script>
<?php endif; ?>

<?= view('recibos/_documento', ['recibo' => $recibo, 'letraTexto' => null]) ?>
<table width="804" border="0" cellspacing="4" cellpadding="0" align="center">
<tr><td align="center">COPIA</td></tr>
</table>
<form id="form1" name="form1" method="post" action="<?= site_url('recibos/' . $recibo['NoFolio'] . '/cancelar') ?>" style="text-align:center">
<table width="800" border="0" cellspacing="0" cellpadding="0" align="center" id="oculto">
  <tr>
    <td valign="top" width="145">Motivo de cancelación:</td>
    <td width="665"><textarea name="motivoC" id="motivoC" cols="45" rows="5" onkeyup="this.value=this.value.toUpperCase()"></textarea></td>
  </tr>
</table>
<p id="oculto" align="center">
  <input type="button" value="Cancelar Recibo" onclick="validacion(1)"/>
  <input type="button" value="Regresar" onclick="validacion(2)"/>
  <input type="hidden" name="opc" id="opc" />
</p>
</form>
<?= $this->endSection() ?>
