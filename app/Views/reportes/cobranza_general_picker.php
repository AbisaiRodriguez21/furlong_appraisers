<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<script type="text/javascript">
function habilitar(a) {
    var v = a.value;
    document.form1.semanas.disabled = (v != 1);
    document.getElementById('GS').disabled = (v != 1);
    document.form1.Mes.disabled = (v != 2);
    document.form1.Anio.disabled = (v != 2);
    document.form1.Anio2.disabled = (v != 3);
}
function generarSemana() {
    var ajax = new XMLHttpRequest();
    ajax.open('GET', '<?= site_url('semanas/generar') ?>');
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4) {
            document.getElementById('semanas').innerHTML = ajax.responseText;
        }
    };
    ajax.send(null);
}
function validar() {
    if (document.getElementById('radio1').checked) {
        if (document.getElementById('semanas').value == -1) { alert('Seleccione Semana'); return; }
    } else if (document.getElementById('radio2').checked) {
        if (document.getElementById('Mes').value == 0) { alert('Seleccione Mes'); return; }
        if (document.getElementById('Anio').value == 0) { alert('Seleccione Año'); return; }
    } else {
        if (document.getElementById('Anio2').value == 0) { alert('Seleccione Año'); return; }
    }
    document.form1.submit();
}
</script>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
<p class="titulo"><?= esc(strtoupper($titulo)) ?></p>
<form id="form1" name="form1" method="post" action="<?= site_url('cobranza/reportes/general/lista') ?>">
<input type="hidden" name="Tipo" value="<?= $tipo ?>" />
<p><table align="center" cellspacing="2" class="tablaB">
<tr>
  <td width="114"><input type="radio" name="radio" id="radio1" value="1" checked="checked" onclick="habilitar(this)" /> Por Semana</td>
  <td align="left">
    <select name="semanas" id="semanas">
      <?= view('reportes/_semanas_opciones', ['semanas' => $semanas]) ?>
    </select>
    <input type="button" id="GS" value="Generar Semana" onclick="generarSemana()"/>
  </td>
</tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr>
  <td><input type="radio" name="radio" id="radio2" value="2" onclick="habilitar(this)" /> Por Mes y Año</td>
  <td align="left">
    <select name="Mes" id="Mes" disabled="disabled">
      <option value="0">Seleccione Mes..</option>
      <?php foreach (['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $i => $m): ?>
      <option value="<?= $i + 1 ?>"><?= $m ?></option>
      <?php endforeach; ?>
    </select>
    <select name="Anio" id="Anio" disabled="disabled">
      <option value="0" selected="selected">Seleccione Año...</option>
      <?php for ($y = date('Y') + 1; $y >= 2011; $y--): ?>
      <option value="<?= $y ?>"><?= $y ?></option>
      <?php endfor; ?>
    </select>
  </td>
</tr>
<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr>
  <td><input type="radio" name="radio" id="radio3" value="3" onclick="habilitar(this)" /> Año</td>
  <td align="left">
    <select name="Anio2" id="Anio2" disabled="disabled">
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
</p>
</form>
<?= $this->endSection() ?>
