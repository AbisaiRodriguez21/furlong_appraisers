<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo">MODIFICAR USUARIOS</p>
<p class="subtitulo">Ingrese los siguientes datos:</p>

<?php if (session('error')): ?>
<script>alert('<?= esc(session('error'), 'js') ?>')</script>
<?php endif; ?>
<?php if (session('mensaje')): ?>
<script>alert('<?= esc(session('mensaje'), 'js') ?>')</script>
<?php endif; ?>

<form id="form1" name="form1" method="post" action="<?= site_url('usuarios/' . $usuario['IdUsuario'] . '/actualizar') ?>">
  <p>
    <label class="m3">Nombre:</label>
    <input name="nombre" type="text" size="60" maxlength="60" value="<?= esc(old('nombre', $usuario['Nombre'])) ?>" onkeyup="this.value=this.value.toUpperCase()"/>
  </p>
  <p>
    <label>Teléfono:</label>
    <input name="tel" type="text" size="10" maxlength="10" value="<?= esc(old('tel', $usuario['Telefono'])) ?>" />
    <label class="m50">Celular:</label>
    <input name="cel" type="text" size="13" maxlength="13" value="<?= esc(old('cel', $usuario['Celular'])) ?>" />
  </p>
  <p>
    <label class="m15">E-mail:</label>
    <input name="email" type="text" size="40" maxlength="40" value="<?= esc(old('email', $usuario['Email'])) ?>"/>
  </p>
  <p>
    <label class="m18">Login:</label>
    <input name="login" type="text" size="12" maxlength="12" value="<?= esc(old('login', $usuario['Login'])) ?>" onkeyup="this.value=this.value.toUpperCase()"/>
    <label class="m20">Password:</label>
    <input name="password" type="password" size="15" maxlength="15" placeholder="********" autocomplete="new-password"/>
    <span class="m4">(déjalo en blanco para no cambiarla)</span>
  </p>
  <p>
    <label class="m24">Tipo:</label>
    <select name="tipo">
      <option value="-1">Seleccione Tipo</option>
      <option value="2" <?= (int) $usuario['Tipo'] === 2 ? 'selected' : '' ?>>GERENTE GENERAL</option>
      <option value="1" <?= (int) $usuario['Tipo'] === 1 ? 'selected' : '' ?>>ADMINISTRADOR</option>
      <option value="0" <?= (int) $usuario['Tipo'] === 0 ? 'selected' : '' ?>>USUARIO</option>
    </select>
  </p>
  <p align="center">
    <input type="submit" name="guardar" value="Guardar"/>
    <input type="submit" name="cancelar" value="Cancelar"/>
  </p>
</form>
<?= $this->endSection() ?>
