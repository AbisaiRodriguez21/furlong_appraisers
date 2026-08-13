<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo">MODIFICAR DOCUMENTACIÓN</p>

<?php if (session('error')): ?>
<script>alert('<?= esc(session('error'), 'js') ?>')</script>
<?php endif; ?>

<form id="form1" name="form1" method="post" action="<?= site_url('documentacion/' . $documento['IdDocumento'] . '/actualizar') ?>">
<p class="subtitulo">Ingrese los siguientes datos:</p>
<label for="nombre">Nombre: </label>
<input name="nombre" type="text" id="nombre" size="70" maxlength="60"
    value="<?= esc(old('nombre', $documento['Nombre'])) ?>" onkeyup="this.value=this.value.toUpperCase()" />
<p align="center">
  <input type="submit" name="guardar" value="Guardar" />
  <input type="submit" name="cancelar" value="Cancelar" />
</p>
</form>
<?= $this->endSection() ?>
