<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<script type="text/javascript" src="<?= base_url('assets/js/ReciboHonorarios.js') ?>"></script>
<link href="<?= base_url('assets/css/impresion.css') ?>" rel="stylesheet" type="text/css" media="print"/>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
<?= view('recibos/_documento', ['recibo' => $recibo, 'letraTexto' => null]) ?>
<table width="804" border="0" cellspacing="4" cellpadding="0" align="center">
<tr><td align="center">COPIA</td></tr>
</table>
<p id="oculto" align="center">
  <input type="button" value="Imprimir" onclick="window.print()"/>
  <input type="button" value="Regresar" onclick="location.href='<?= site_url('recibos') ?>'"/>
</p>
<?= $this->endSection() ?>
