<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo">REPORTE DE AVALUOS</p>
<form id="form1" name="form1" method="post" action="<?= site_url('avaluos/reportes/general/pdf') ?>" target="_blank">
<fieldset id="Documentos" style="border:0">
<table align="center" cellspacing="2" width="250" style="border:double; border-color:#1A3557;">
  <tr><td width="100" class="tituloTabla">Avaluos Solicitados</td><td width="50" align="center"><?= $resumen['solicitados'] ?></td></tr>
  <tr><td width="100" class="tituloTabla">Avaluos Entregados</td><td width="50" align="center"><?= $resumen['entregados'] ?></td></tr>
  <tr><td width="100" class="tituloTabla">Avaluos Terminados</td><td width="50" align="center"><?= $resumen['terminados'] ?></td></tr>
  <tr><td width="100" class="tituloTabla">Avaluos Vencidos</td><td width="50" align="center"><?= $resumen['vencidos'] ?></td></tr>
</table>
<input type="hidden" name="a" value="<?= $ano ?>" />
<p align="center">
  <input type="submit" value="Imprimir"/>
  <input type="button" value="Regresar" onclick="location.href='<?= site_url('avaluos/reportes/picker/4') ?>'" />
</p>
</fieldset>
</form>
<?= $this->endSection() ?>
