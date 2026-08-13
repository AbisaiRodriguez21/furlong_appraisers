<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo">MODIFICAR CLIENTES</p>
<form id="form1" name="form1" method="post" action="<?= site_url('clientes/' . $cliente['IdCliente'] . '/actualizar') ?>">
<?php if ($t): ?><input type="hidden" name="t" value="<?= esc($t) ?>" /><?php endif; ?>
<p class="subtitulo">Ingrese los siguientes datos:</p>

<?php if (session('error')): ?>
<p style="color:#a00;font-weight:bold;"><?= esc(session('error')) ?></p>
<?php endif; ?>

  <p>
    <label for="nombre" class=" m26">Nombre:</label>
    <input name="nombre" type="text" id="nombre" size="60" maxlength="60" required
        value="<?= esc(old('nombre', $cliente['Nombre'])) ?>" onkeyup="this.value=this.value.toUpperCase()"/>
    <label for="nombreC" class=" m24">Nombre Clave:</label>
    <input name="nombreC" type="text" id="nombreC" size="20" maxlength="20" required
        value="<?= esc(old('nombreC', $cliente['NombreClave'])) ?>" onkeyup="this.value=this.value.toUpperCase()"/>
  </p>
  <p>
    <label for="tel" class=" m22">Teléfono:</label>
    <input name="tel" type="text" id="tel" size="10" maxlength="10"
        value="<?= esc(old('tel', $cliente['Telefono'])) ?>" onkeyup="this.value=this.value.toUpperCase()" />
    <label for="cel" class="m50">Celular:</label>
    <input name="cel" type="text" id="cel" size="13" maxlength="13"
        value="<?= esc(old('cel', $cliente['Celular'])) ?>" onkeyup="this.value=this.value.toUpperCase()"/>
  </p>
  <p>
    <label for="email" class=" m39">E-mail:</label>
    <input name="email" type="text" id="email" size="40" maxlength="40" value="<?= esc(old('email', $cliente['Email'])) ?>"/>
  </p>
  <p>
    <label for="direc" class="m19">Dirección:</label>
    <input name="direc" type="text" id="direc" size="105" maxlength="100"
        value="<?= esc(old('direc', $cliente['Direccion'])) ?>" onkeyup="this.value=this.value.toUpperCase()"/>
  </p>
  <p>
    <label for="rfc" class="m50">RFC:</label>
    <input name="rfc" type="text" id="rfc" size="13" maxlength="13" required
        value="<?= esc(old('rfc', $cliente['RFC'])) ?>" onkeyup="this.value=this.value.toUpperCase()"/>
  </p>
  <p>
    <label for="comen">Comentarios:</label>
    <br />
    <textarea name="comen" id="comen" cols="45" rows="5" class="m85"
        onkeyup="this.value=this.value.toUpperCase()"><?= esc(old('comen', $cliente['Comentario'])) ?></textarea>
  </p>
  <p align="center">
    <input type="submit" name="guardar" value="Guardar"/>
    <input type="submit" name="cancelar" value="Cancelar"/>
  </p>
</form>
<?= $this->endSection() ?>
