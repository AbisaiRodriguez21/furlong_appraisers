<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo">ELIMINAR DOCUMENTACIÓN</p>
<form id="form1" name="form1" method="post" action="<?= site_url('documentacion/' . $documento['IdDocumento'] . '/eliminar') ?>">
<label for="nombre">Nombre: </label>
<input type="text" disabled="disabled" id="nombre" size="70" maxlength="60" value="<?= esc($documento['Nombre']) ?>" />
<p align="center">
  <input type="submit" name="button" value="Eliminar" />
  <input type="button" value="Cancelar" onclick="location.href='<?= site_url('documentacion') ?>'" />
</p>
</form>
<?= $this->endSection() ?>
