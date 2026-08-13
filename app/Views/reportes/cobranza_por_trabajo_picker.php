<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<script type="text/javascript">
function validar() {
    if (document.getElementById('Trabajo').value == 0) { alert('Seleccione Trabajo'); return; }
    if (document.getElementById('Anio').value == 0) { alert('Seleccione Año'); return; }
    document.form1.submit();
}
</script>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
<p class="titulo">REPORTE DE COBRANZA POR TRABAJO</p>
<form id="form1" name="form1" method="post" action="<?= site_url('cobranza/reportes/trabajo/lista') ?>">
<table>
  <tr>
    <td align="right">Seleccione Trabajo:</td>
    <td align="left">
      <select name="Trabajo" id="Trabajo">
        <option value="0">Tipo de Trabajo</option>
        <?php foreach ($trabajos as $t): ?>
        <option value="<?= $t['IdTrabajo'] ?>"><?= esc($t['Nombre']) ?></option>
        <?php endforeach; ?>
      </select>
    </td>
  </tr>
  <tr>
    <td align="right">Seleccione Año:</td>
    <td align="left">
      <select name="Anio" id="Anio">
        <option value="0" selected="selected">Seleccione Año...</option>
        <?php for ($y = date('Y') + 1; $y >= 2011; $y--): ?>
        <option value="<?= $y ?>"><?= $y ?></option>
        <?php endfor; ?>
      </select>
    </td>
  </tr>
</table>
<p align="center">
  <input type="button" value="Generar Reporte" onclick="validar()" />
  <input type="button" value="Regresar" onclick="location.href='<?= site_url('cobranza/reportes') ?>'" />
</p>
</form>
<?= $this->endSection() ?>
