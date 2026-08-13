<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo">REPORTES DE COBRANZA</p>
<p align="center">
  <a href="<?= site_url('cobranza/reportes/dia') ?>"><input type="button" value="Pagos" /></a>
  <a href="<?= site_url('cobranza/reportes/cliente/0') ?>"><input type="button" value="Pagos por Cliente" /></a>
  <a href="<?= site_url('cobranza/reportes/general/0') ?>"><input type="button" value="Pagos General" /></a>
  <a href="<?= site_url('cobranza/reportes/trabajo') ?>"><input type="button" value="Pagos Trabajo" /></a>
  <a href="<?= site_url('cobranza/reportes/general/1') ?>"><input type="button" value="Adeudos" /></a>
  <a href="<?= site_url('cobranza/reportes/cliente/1') ?>"><input type="button" value="Adeudos por Cliente" /></a>
</p>
<p align="center"><a href="<?= site_url('cobranza') ?>">Regresar</a></p>
<?= $this->endSection() ?>
