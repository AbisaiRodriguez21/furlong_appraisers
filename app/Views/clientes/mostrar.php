<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo">CONSULTAR CLIENTES</p>
<form id="form1" name="form1" method="get" action="<?= site_url('clientes') . ($t ? '?t=' . esc($t, 'url') : '') ?>">
  <p>
    <label for="nombre" class=" m26">Nombre:</label>
    <input type="text" disabled="disabled" id="nombre" value="<?= esc($cliente['Nombre']) ?>" size="60" maxlength="60"/>
    <label for="nombreC" class=" m24">Nombre Clave:</label>
    <input type="text" disabled="disabled" id="nombreC" value="<?= esc($cliente['NombreClave']) ?>" size="20" maxlength="20"/>
  </p>
  <p>
    <label for="tel" class=" m22">Teléfono:</label>
    <input type="text" disabled="disabled" id="tel" value="<?= esc($cliente['Telefono']) ?>" size="10" maxlength="10"/>
    <label for="cel" class="m50">Celular:</label>
    <input type="text" disabled="disabled" id="cel" value="<?= esc($cliente['Celular']) ?>" size="13" maxlength="13"/>
  </p>
  <p>
    <label for="email" class=" m39">E-mail:</label>
    <input type="text" disabled="disabled" id="email" value="<?= esc($cliente['Email']) ?>" size="40" maxlength="40"/>
  </p>
  <p>
    <label for="direc" class="m19">Dirección:</label>
    <input type="text" disabled="disabled" id="direc" value="<?= esc($cliente['Direccion']) ?>" size="105" maxlength="100"/>
  </p>
  <p>
    <label for="rfc" class="m50">RFC:</label>
    <input type="text" disabled="disabled" id="rfc" value="<?= esc($cliente['RFC']) ?>" size="13" maxlength="13"/>
  </p>
  <p>
    <label for="comen">Comentarios:</label>
    <br />
    <textarea disabled="disabled" cols="45" rows="5" class="m85" id="comen"><?= esc($cliente['Comentario']) ?></textarea>
  </p>
  <p align="center">
    <input type="submit" value="Regresar"/>
  </p>
</form>
<?= $this->endSection() ?>
