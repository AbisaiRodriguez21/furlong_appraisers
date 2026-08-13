<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<script type="text/javascript">
function validar() {
    var ano = document.getElementById('Anio2').value;
    if (ano == 0) { alert('Seleccione Año'); return; }
    if (document.getElementById('RStatus').checked) {
        location.href = '<?= site_url('avaluos/reportes/general') ?>/' + ano;
    } else if (document.getElementById('RTrabajo').checked) {
        location.href = '<?= site_url('avaluos/reportes/general-trabajo') ?>/' + ano;
    } else {
        alert('Seleccione Tipo de Reporte');
    }
}
</script>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
<p class="titulo">REPORTE DE AVALUOS GENERAL</p>
<table align="center">
  <tr>
    <td>
      <select id="Anio2" onchange="validar()">
        <option value="0" selected="selected">Seleccione Año...</option>
        <?php for ($y = date('Y') + 1; $y >= 2011; $y--): ?>
        <option value="<?= $y ?>"><?= $y ?></option>
        <?php endfor; ?>
      </select>
      <input type="radio" name="Tipo" id="RStatus" value="Tipo" onclick="validar()" />
      <label for="RStatus">Reporte General por Status</label>
      &nbsp;&nbsp;&nbsp;
      <input type="radio" name="Tipo" id="RTrabajo" value="Tipo2" onclick="validar()" />
      <label for="RTrabajo">Reporte General por Trabajo Realizado</label>
    </td>
  </tr>
</table>
<p align="center"><input type="button" value="Regresar" onclick="location.href='<?= site_url('avaluos/reportes') ?>'" /></p>
<?= $this->endSection() ?>
