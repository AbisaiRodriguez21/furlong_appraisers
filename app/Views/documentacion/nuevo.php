<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<script type="text/javascript">
var contador = 0;

function agregarDocumento() {
    var campo = document.getElementById('Documento');
    if (campo.value.trim().length === 0) {
        alert('Ingrese Documento');
        campo.focus();
        return;
    }

    contador++;
    var fila = document.createElement('div');
    fila.innerHTML = '<input class="m3" size="70" value="' + campo.value.replace(/"/g, '&quot;') + '" readonly>' +
        '<input type="hidden" name="documento[]" value="' + campo.value.replace(/"/g, '&quot;') + '">';
    document.getElementById('Documentos').appendChild(fila);

    document.getElementById('tcantidad').value = contador;
    campo.value = '';
    campo.focus();
}

function validar() {
    if (document.getElementById('tcantidad').value == 0) {
        alert('Ingrese al menos un Documento');
        document.getElementById('Documento').focus();
        return;
    }
    document.form1.submit();
}

function ir() {
    location.href = '<?= site_url('documentacion') ?>';
}
</script>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
<p class="titulo">AGREGAR DOCUMENTACIÓN</p>

<?php if (session('error')): ?>
<script>alert('<?= esc(session('error'), 'js') ?>')</script>
<?php endif; ?>

<form id="form1" name="form1" method="post" action="<?= site_url('documentacion/guardar') ?>">
<fieldset class="doc">
<table width="600" border="0">
  <tr>
    <th scope="col" class="celda">Documento</th>
    <th scope="col">&nbsp;</th>
  </tr>
  <tr>
    <th scope="col"><input name="Documento" type="text" id="Documento" size="70" maxlength="60" onkeyup="this.value=this.value.toUpperCase()"/></th>
    <th scope="col"><input type="button" value="Agregar Documento" onclick="agregarDocumento()"/></th>
  </tr>
</table>
<br />
</fieldset>
<input name="tcantidad" type="hidden" id="tcantidad" value="0"/>
<fieldset id="Documentos" style="border:none; text-align: left;"></fieldset>
<p align="center">
  <input type="button" value="Guardar" onclick="validar()" />
  <input type="button" value="Cancelar" onclick="ir()" />
</p>
</form>
<?= $this->endSection() ?>
