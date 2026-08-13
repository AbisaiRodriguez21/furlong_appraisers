<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo">CONSULTAR USUARIOS</p>
<form id="form1" name="form1" method="get" action="<?= site_url('usuarios') ?>">
  <p>
    <label class="m3">Nombre:</label>
    <input type="text" disabled="disabled" value="<?= esc($usuario['Nombre']) ?>" size="60" maxlength="60"/>
  </p>
  <p>
    <label>Teléfono:</label>
    <input type="text" disabled="disabled" value="<?= esc($usuario['Telefono']) ?>" size="10" maxlength="10" />
    <label class="m50">Celular:</label>
    <input type="text" disabled="disabled" value="<?= esc($usuario['Celular']) ?>" size="13" maxlength="13"/>
  </p>
  <p>
    <label class="m15">E-mail:</label>
    <input type="text" disabled="disabled" size="40" value="<?= esc($usuario['Email']) ?>"/>
  </p>
  <p>
    <label class="m18">Login:</label>
    <input type="text" disabled="disabled" size="12" maxlength="12" value="<?= esc($usuario['Login']) ?>"/>
    <label class="m20">Password:</label>
    <input type="password" disabled="disabled" value="********" size="15" maxlength="15"/>
  </p>
  <p>
    <label class="m24">Tipo:</label>
    <?php
    $tipoTexto = match ((int) $usuario['Tipo']) {
        2 => 'GERENTE GENERAL',
        1 => 'ADMINISTRADOR',
        default => 'USUARIO',
    };
    ?>
    <input type="text" disabled="disabled" value="<?= esc($tipoTexto) ?>"/>
  </p>
  <p align="center"><input type="submit" value="Regresar"/></p>
</form>
<?= $this->endSection() ?>
