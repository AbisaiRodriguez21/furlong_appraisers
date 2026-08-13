<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<script type="text/javascript">
function filtra(txt) {
    buscarEnTabla(txt, 'dato', 'tab', 0);
}

function cargaContenido(idSelectOrigen) {
    var divResultado = document.getElementById('Documentos');
    var select = document.getElementById(idSelectOrigen);
    var opcion = select.options[select.selectedIndex].value;
    var ajax = new XMLHttpRequest();
    ajax.open('GET', '<?= site_url('documentacion/filtro') ?>?opcion=' + opcion);
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
<p class="titulo">DOCUMENTACIÓN</p>

<?php if (session('mensaje')): ?>
<script>alert('<?= esc(session('mensaje'), 'js') ?>')</script>
<?php endif; ?>

<form id="form1" name="form1" method="post" action="">
<p>
  <table width="960" border="0" align="left" class="tablaB">
    <tr>
      <td><label for="dato">Buscar: </label>
        <input name="dato" type="text" id="dato" size="50" onkeyup="filtra(this.value)" maxlength="30"/>
        <select name="status" size="1" id="status" onchange="cargaContenido(this.id)">
          <option value="1">Activos</option>
          <option value="0">Inactivos</option>
        </select></td>
      <td><a href="<?= site_url('documentacion/nuevo') ?>"><img src="<?= base_url('assets/img/agregarB.png') ?>" width="122" height="43" border="0" align="right" /></a></td>
    </tr>
  </table>
</p>
<fieldset id="Documentos" style="border:0" class="m10x">
<table class="tabla" align="left">
<tr class="tituloTabla">
  <td width="920" class="tituloTabla"><b>NOMBRE</b></td>
  <td width="40" class="tituloTabla"><b></b></td>
</tr>
</table>
<div id="scroll">
<table id="tab" class="tabla" align="left">
<?php foreach ($documentos as $i => $d): ?>
  <tr <?= $i % 2 === 0 ? 'bgcolor="#DAE6F3"' : '' ?>>
    <td width="900"><?= esc($d['Nombre']) ?></td>
    <td width="24"><a href="<?= site_url('documentacion/' . $d['IdDocumento'] . '/editar') ?>"><img src="<?= base_url('assets/img/edit.png') ?>" width="22" height="22" border="0" /></a></td>
    <td width="24"><a href="<?= site_url('documentacion/' . $d['IdDocumento'] . '/eliminar') ?>"><img src="<?= base_url('assets/img/document-close-3.png') ?>" width="24" height="24" border="0" /></a></td>
  </tr>
<?php endforeach; ?>
</table>
</div>
</fieldset>
</form>
<?= $this->endSection() ?>
