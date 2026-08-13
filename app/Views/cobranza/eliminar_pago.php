<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo">ELIMINAR PAGO</p>
<form id="form1" name="form1" method="post" action="<?= site_url('cobranza/pago/' . $pago['IdPago'] . '/eliminar') ?>">
¿Esta Seguro que Desea Eliminar el Pago?
<p align="center">
  <input type="submit" name="opc" value="1" />
  <input type="button" value="Cancelar" onclick="location.href='<?= site_url('cobranza/' . $pago['Folio'] . '/ver') ?>'" />
</p>
</form>
<?= $this->endSection() ?>
