<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<style type="text/css">
.enlace{cursor: pointer;}
.enlace2{cursor: pointer; text-decoration:underline; color: #666;}
</style>
<script type="text/javascript">
function filtra(txt) {
    buscarEnTablaMultiColumna(txt, 'dato', 'tab', [0, 1, 2, 3, 4]);
}

function recargar() {
    var divResultado = document.getElementById('Documentos');
    var ajax = new XMLHttpRequest();
    var ano = document.getElementById('ano').value;
    var trabajo = document.getElementById('trabajos').value;
    var status = document.getElementById('status').value;
    ajax.open('GET', '<?= site_url('avaluos/filtro') ?>?ano=' + ano + '&trabajo=' + trabajo + '&status=' + status);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4) {
            divResultado.innerHTML = ajax.responseText;
        }
    };
    ajax.send(null);
}

function prueba(opcion) {
    var etiquetas = {0: 's7_todos', 1: 's1', 2: 's2', 7: 's3', 5: 's4', 3: 's5', 6: 's6'};
    for (var key in etiquetas) {
        var el = document.getElementById(etiquetas[key]);
        if (el) {
            el.className = (parseInt(key) === opcion) ? 'enlace2' : 'enlace';
        }
    }
    document.getElementById('status').value = opcion;
    recargar();
}

function pregunta(folio, statusTexto) {
    if (statusTexto === 'Cancelado') {
        alert('Este Avaluo ya Esta Cancelado');
        return;
    }
    if (!confirm('Desea Cancelar el Avaluo?')) {
        return;
    }
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '<?= site_url('avaluos') ?>/' + folio + '/cancelar');
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4) {
            recargar();
        }
    };
    ajax.send(null);
}
</script>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
<p class="titulo">AVALÚOS</p>

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
  <select name="trabajos" size="1" id="trabajos" onchange="recargar()">
    <option value="0">Todos</option>
    <?php foreach ($trabajos as $t): ?>
    <option value="<?= $t['IdTrabajo'] ?>"><?= esc($t['Nombre']) ?></option>
    <?php endforeach; ?>
  </select></td>
<td align="right"><a href="<?= site_url('avaluos/nuevo') ?>"><img src="<?= base_url('assets/img/agregarB.png') ?>" width="122" height="43" border="0"/></a></td>
</tr>
</table>
</p>
<p>
<table align="left" cellspacing="2" class="tablaB2">
  <tr>
  <td width="120"><span class="enlace2" onclick="prueba(0)" id="s7_todos">Todos</span>
      <input name="status" type="hidden" id="status" value="0" /></td>
    <td width="40" bgcolor="#FF2828" onclick="prueba(1)" class="enlace">&nbsp;</td>
    <td width="120"><span class="enlace" onclick="prueba(1)" id="s1">Cancelado</span></td>
    <td width="40" bgcolor="#BC7D3F" onclick="prueba(2)" class="enlace">&nbsp;</td>
    <td width="120"><span class="enlace" onclick="prueba(2)" id="s2">Vencido</span></td>
    <td width="40" bgcolor="#F9FD64" onclick="prueba(7)" class="enlace">&nbsp;</td>
    <td width="120"><span class="enlace" onclick="prueba(7)" id="s3">Pendiente</span></td>
    <td width="40" bgcolor="#8DDA87" onclick="prueba(5)" class="enlace">&nbsp;</td>
    <td width="120"><span class="enlace" onclick="prueba(5)" id="s4">En Proceso</span></td>
    <td width="40" bgcolor="#4D9999" onclick="prueba(3)" class="enlace">&nbsp;</td>
    <td width="120"><span class="enlace" onclick="prueba(3)" id="s5">Terminado</span></td>
    <td width="40" bgcolor="#BAE1FE" onclick="prueba(6)" class="enlace">&nbsp;</td>
    <td width="120"><span class="enlace" onclick="prueba(6)" id="s6">Entregado</span></td>
  </tr>
</table>
</p>
<fieldset id="Documentos" style="border:0" class="m10x">
<?= view('avaluos/_tabla', ['avaluos' => $avaluos, 'estatus' => $estatus]) ?>
</fieldset>

<?php if ((int) session('tipo') === 2): ?>
<p align="center">
  <input type="button" value="Avaluos Entregados" onclick="location.href='<?= site_url('avaluos/reportes') ?>'" />
  &nbsp;&nbsp;
  <input type="button" value="Avaluos Solicitados" onclick="location.href='<?= site_url('avaluos/reportes') ?>'" />
  &nbsp;&nbsp;
  <input type="button" value="Avaluos Vencidos" onclick="location.href='<?= site_url('avaluos/reportes') ?>'" />
  &nbsp;&nbsp;
  <input type="button" value="Avaluos Terminados" onclick="location.href='<?= site_url('avaluos/reportes') ?>'" />
  &nbsp;&nbsp;
  <input type="button" value="Avaluos General" onclick="location.href='<?= site_url('avaluos/reportes') ?>'" />
</p>
<?php endif; ?>
</form>
<?= $this->endSection() ?>
