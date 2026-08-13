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
    ajax.open('GET', '<?= site_url('usuarios/filtro') ?>?opcion=' + opcion);
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
<p class="titulo">USUARIOS</p>

<?php if (session('mensaje')): ?>
<script>alert('<?= esc(session('mensaje'), 'js') ?>')</script>
<?php endif; ?>

<form id="form1" name="form1" method="post" action="">
<p>
<table align="left" cellspacing="2" class="tablaB">
<tr>
  <td><label for="dato">Buscar: </label>
  <input name="dato" type="text" id="dato" size="50" onkeyup="filtra(this.value)" maxlength="60"/>
  <select name="status" size="1" id="status" onchange="cargaContenido(this.id)">
    <option value="1">Activos</option>
    <option value="0">Inactivos</option>
  </select></td>
<td align="right"><a href="<?= site_url('usuarios/nuevo') ?>"><img src="<?= base_url('assets/img/agregarB.png') ?>" width="122" height="43" border="0"/></a></td>
</tr>
</table>
</p>
<fieldset id="Documentos" style="border:0" class="m10x">
<?= view('usuarios/_tabla', ['usuarios' => $usuarios]) ?>
</fieldset>
</form>
<?= $this->endSection() ?>
