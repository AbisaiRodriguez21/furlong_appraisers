<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo">CONSULTAR TRABAJO</p>
<form id="form1" name="form1" method="post" action="<?= site_url('trabajos') ?>">
  <p>
    <label for="nombre" class="m22">Nombre:</label>
    <input type="text" disabled="disabled" id="nombre" size="70" maxlength="60" value="<?= esc($trabajo['Nombre']) ?>" />
  </p>
  <p>
    <label for="descripcion">Descripción:</label>
    <textarea disabled="disabled" id="descripcion" cols="110" rows="3"><?= esc($trabajo['Descripcion']) ?></textarea>
  </p>
  <p>Documentos actuales del trabajo:</p>
  <div id="scroll2"><table class="tab" align="left">
  <tr class="tituloTabla">
    <td class="tituloTabla" width="300"><b>DOCUMENTOS</b></td>
    <td class="tituloTabla" width="50"><b>OBLIG.</b></td>
  </tr>
  <?php foreach ($documentosActuales as $i => $doc): ?>
  <tr <?= $i % 2 === 0 ? 'bgcolor="#DAE6F3"' : '' ?>>
    <td><?= esc($doc['Nombre']) ?></td>
    <td align="center"><input type="checkbox" disabled="disabled" <?= (int) $doc['Tipo'] === 1 ? 'checked="checked"' : '' ?>></td>
  </tr>
  <?php endforeach; ?>
  </table></div>
  <br>
  <p align="center"><input type="submit" name="button" value="Regresar" /></p>
</form>
<?= $this->endSection() ?>
