<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo">MODIFICAR TRABAJO</p>

<?php if (session('error')): ?>
<script>alert('<?= esc(session('error'), 'js') ?>')</script>
<?php endif; ?>

<form id="form1" name="form1" method="post" action="<?= site_url('trabajos/' . $trabajo['IdTrabajo'] . '/actualizar') ?>">
  <p>
    <label class="m22" for="nombre">Nombre:</label>
    <input name="nombre" type="text" id="nombre" size="70" maxlength="60"
        value="<?= esc(old('nombre', $trabajo['Nombre'])) ?>" onkeyup="this.value=this.value.toUpperCase()" />
  </p>
  <p>
    <label for="descripcion">Descripción:</label>
    <textarea name="descripcion" id="descripcion" cols="110" rows="3"
        onkeyup="this.value=this.value.toUpperCase()"><?= esc(old('descripcion', $trabajo['Descripcion'])) ?></textarea>
  </p>
  <table width="800" border="0">
    <tr>
      <td>
        <p>Documentos actuales del trabajo:</p>
        <div id="scroll2"><table class="tab" align="left">
        <tr class="tituloTabla">
          <td class="tituloTabla" width="300"><b>DOCUMENTOS</b></td>
          <td class="tituloTabla" width="50"><b>OBLIG.</b></td>
        </tr>
        <?php foreach ($documentosActuales as $i => $doc): ?>
        <tr <?= $i % 2 === 0 ? 'bgcolor="#DAE6F3"' : '' ?>>
          <td><input name="documentos[<?= $doc['IdDocumento'] ?>]" type="checkbox" value="1" checked="checked"><?= esc($doc['Nombre']) ?></td>
          <td align="center"><input name="oblig[<?= $doc['IdDocumento'] ?>]" type="checkbox" value="1" <?= (int) $doc['Tipo'] === 1 ? 'checked="checked"' : '' ?>></td>
        </tr>
        <?php endforeach; ?>
        </table></div>
      </td>
      <td>
        <p>Seleccione los documentos que desea agregar:</p>
        <div id="scroll2"><table class="tab" align="left">
        <tr class="tituloTabla">
          <td class="tituloTabla" width="300"><b>DOCUMENTOS</b></td>
          <td class="tituloTabla" width="50"><b>OBLIG.</b></td>
        </tr>
        <?php foreach ($documentosNuevos as $i => $doc): ?>
        <tr <?= $i % 2 === 0 ? 'bgcolor="#DAE6F3"' : '' ?>>
          <td><input name="documentos[<?= $doc['IdDocumento'] ?>]" type="checkbox" value="1"><?= esc($doc['Nombre']) ?></td>
          <td align="center"><input name="oblig[<?= $doc['IdDocumento'] ?>]" type="checkbox" value="1"></td>
        </tr>
        <?php endforeach; ?>
        </table></div>
      </td>
    </tr>
  </table>
  <br>
  <p align="center">
    <input type="submit" name="guardar" value="Guardar" />
    <input type="submit" name="cancelar" value="Cancelar" />
  </p>
</form>
<?= $this->endSection() ?>
