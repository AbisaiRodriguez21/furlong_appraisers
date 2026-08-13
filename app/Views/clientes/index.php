<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<script type="text/javascript">
function seleccion(a)
{
    var ventana = document.getElementById('Ventana').value;
    var fila = document.getElementById('tab').getElementsByTagName('tr')[a.id];
    var texto = fila.getElementsByTagName('td')[0].innerHTML;
    var numC = document.getElementById('numC' + a.id).value;
    var rfc  = document.getElementById('rfc' + a.id).value;
    var dir  = document.getElementById('dir' + a.id).value;

    window.close();
    if (ventana == 2) {
        window.opener.CopiarDatos(numC, texto, rfc, dir);
    } else {
        window.opener.CopiarDatos(numC, texto, document.getElementById('Tipo').value);
    }
}

function filtra(txt) {
    buscarEnTabla(txt, 'dato', 'tab', 0);
}

function nuevoAjax() {
    return new XMLHttpRequest();
}

function cargaContenido(idSelectOrigen) {
    var divResultado = document.getElementById('Documentos');
    var select = document.getElementById(idSelectOrigen);
    var opcion = select.options[select.selectedIndex].value;
    var ajax = nuevoAjax();
    ajax.open('GET', '<?= site_url('clientes/filtro') ?>?opcion=' + opcion);
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
<p class="titulo">CLIENTES</p>

<?php if (session('mensaje')): ?>
<script>alert('<?= esc(session('mensaje'), 'js') ?>')</script>
<?php endif; ?>

<form id="form1" name="form1" method="post" action="">
<?php if ($t): ?><input name="Tipo" id="Tipo" type="hidden" value="<?= esc($t) ?>" /><?php endif; ?>
<input name="Ventana" id="Ventana" type="hidden" value="<?= esc($ventana ?? '0') ?>" />
<p>
<table align="left" cellspacing="2" class="tablaB">
<tr>
  <td><label for="dato">Buscar: </label>
  <input name="dato" type="text" id="dato" size="50" onkeyup="filtra(this.value)" maxlength="60"/>
  <select name="status" size="1" id="status" onchange="cargaContenido(this.id)">
    <option value="1">Activos</option>
    <option value="0">Inactivos</option>
  </select></td>
<td align="right"><a href="<?= site_url('clientes/nuevo') . ($t ? '?t=' . esc($t, 'url') : '') ?>"><img src="<?= base_url('assets/img/agregarB.png') ?>" width="122" height="43" border="0"/></a></td>
</tr>
</table>
</p>
<fieldset id="Documentos" style="border:0" class="m10x">
<table align="left" cellspacing="2" class="tabla">
  <tr>
    <td width="460" class="tituloTabla">NOMBRE</td>
    <td width="120" class="tituloTabla">TELÉFONO</td>
    <td width="120" class="tituloTabla">CELULAR</td>
    <td width="170" class="tituloTabla">NOMBRE CLAVE</td>
    <td width="70" class="tituloTabla">&nbsp;</td>
  </tr>
</table>
<div id="scroll">
<table align="left" cellspacing="2" class="tabla" id="tab">
<?php foreach ($clientes as $i => $c): ?>
  <tr id="<?= $i ?>" ondblclick="seleccion(this)" <?= $i % 2 === 1 ? 'bgcolor="#DAE6F3"' : '' ?>>
    <td width="580" class="celdasel"><?= esc($c['Nombre']) ?></td>
    <td width="150" class="celdasel" align="center">
      <?= esc($c['Telefono']) ?>
      <input type="hidden" id="numC<?= $i ?>" value="<?= esc($c['IdCliente']) ?>" />
      <input type="hidden" id="rfc<?= $i ?>" value="<?= esc($c['RFC']) ?>" />
      <input type="hidden" id="dir<?= $i ?>" value="<?= esc($c['Direccion']) ?>" />
    </td>
    <td width="150" class="celdasel" align="center"><?= esc($c['Celular']) ?></td>
    <td width="200" class="celdasel"><?= esc($c['NombreClave']) ?></td>
    <td width="10" class="celdasel"><a href="<?= site_url('clientes/' . $c['IdCliente']) . ($t ? '?t=' . esc($t, 'url') : '') ?>"><img src="<?= base_url('assets/img/document-preview.png') ?>" width="24" height="24" border="0" /></a></td>
    <td width="10" class="celdasel"><a href="<?= site_url('clientes/' . $c['IdCliente'] . '/editar') . ($t ? '?t=' . esc($t, 'url') : '') ?>"><img src="<?= base_url('assets/img/edit.png') ?>" width="22" height="22" border="0" /></a></td>
    <td width="10" class="celdasel"><a href="<?= site_url('clientes/' . $c['IdCliente'] . '/eliminar') . ($t ? '?t=' . esc($t, 'url') : '') ?>"><img src="<?= base_url('assets/img/document-close-3.png') ?>" width="24" height="24" border="0" /></a></td>
  </tr>
<?php endforeach; ?>
</table>
</div>
</fieldset>
</form>
<?= $this->endSection() ?>
