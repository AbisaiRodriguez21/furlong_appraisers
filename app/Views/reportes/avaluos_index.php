<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo">REPORTES DE AVALÚOS</p>
<p align="center">
  <a href="<?= site_url('avaluos/reportes/picker/6') ?>"><input type="button" value="Avaluos Entregados" /></a>
  &nbsp;&nbsp;
  <a href="<?= site_url('avaluos/reportes/picker/5') ?>"><input type="button" value="Avaluos Solicitados" /></a>
  &nbsp;&nbsp;
  <a href="<?= site_url('avaluos/reportes/picker/2') ?>"><input type="button" value="Avaluos Vencidos" /></a>
  &nbsp;&nbsp;
  <a href="<?= site_url('avaluos/reportes/picker/3') ?>"><input type="button" value="Avaluos Terminados" /></a>
  &nbsp;&nbsp;
  <a href="<?= site_url('avaluos/reportes/picker/4') ?>"><input type="button" value="Avaluos General" /></a>
</p>
<p align="center"><a href="<?= site_url('avaluos') ?>">Regresar</a></p>
<?= $this->endSection() ?>
