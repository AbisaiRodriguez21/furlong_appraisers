<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<script type="text/javascript">
function filtra(txt) {
    buscarEnTablaMultiColumna(txt, 'dato', 'tab', [0, 1, 2, 3, 4]);
}

function recargar() {
    var divResultado = document.getElementById('Documentos');
    var ajax = new XMLHttpRequest();
    var ano = document.getElementById('ano').value;
    var status = document.getElementById('status').value;
    ajax.open('GET', '<?= site_url('cobranza/filtro') ?>?ano=' + ano + '&opcion=' + status);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4) {
            divResultado.innerHTML = ajax.responseText;
        }
    };
    ajax.send(null);
}
</script>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
<p class="titulo">COBRANZA</p>

<?php if (session('mensaje')): ?>
<script>alert('<?= esc(session('mensaje'), 'js') ?>')</script>
<?php endif; ?>

<form id="form1" name="form1" method="post" action="">
<p><table align="left" cellspacing="2" class="tablaB">
<tr>
  <td colspan="2">
    <label class="m18">Año:</label>
    <select name="ano" size="1" id="ano" onchange="recargar()">
    <?php for ($y = date('Y') + 1; $y >= 2011; $y--): ?>
      <option value="<?= $y ?>" <?= $y === $ano ? 'selected' : '' ?>><?= $y ?></option>
    <?php endfor; ?>
    </select>
  </td>
</tr>
<tr>
  <td><label for="dato">Buscar: </label>
  <input name="dato" type="text" id="dato" size="50" onkeyup="filtra(this.value)"/>
  <select name="status" size="1" id="status" onchange="recargar()">
    <option value="0">Todos</option>
    <option value="1" selected="selected">Adeudos</option>
    <option value="2">Pagados</option>
  </select></td>
<td align="right"><a href="<?= site_url('recibos') ?>"><img src="<?= base_url('assets/img/agregarr.png') ?>" width="122" height="43" border="0" /></a></td>
</tr>
</table>
</p>
<fieldset id="Documentos" style="border:0">
<?= view('cobranza/_tabla', ['filas' => $filas]) ?>
</fieldset>

<?php if ((int) session('tipo') === 2): ?>
<p align="center">
  <input type="button" value="Pagos" onclick="location.href='<?= site_url('cobranza/reportes') ?>'" />
  <input type="button" value="Pagos por Cliente" onclick="location.href='<?= site_url('cobranza/reportes') ?>'" />
  <input type="button" value="Pagos General" onclick="location.href='<?= site_url('cobranza/reportes') ?>'" />
  <input type="button" value="Pagos Trabajo" onclick="location.href='<?= site_url('cobranza/reportes') ?>'" />
  <input type="button" value="Adeudos" onclick="location.href='<?= site_url('cobranza/reportes') ?>'" />
  <input type="button" value="Adeudos por Cliente" onclick="location.href='<?= site_url('cobranza/reportes') ?>'" />
</p>
<?php endif; ?>
</form>
<?= $this->endSection() ?>
