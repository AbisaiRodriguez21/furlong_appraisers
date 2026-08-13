<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<script type="text/javascript">
function validaFloat(numero) {
    return /^([0-9])*[.]?[0-9]*$/.test(numero) ? 1 : 0;
}

function validacion(opcion) {
    if (opcion == 2) {
        document.form1.opc.value = 2;
        document.form1.submit();
        return;
    }
    var pago = document.form1.Pago;
    if (pago.value.length == 0) { alert('Ingrese Pago'); pago.focus(); return; }
    if (validaFloat(pago.value) == 0) { alert('Valor Incorrecto para Pago'); pago.focus(); return; }
    if (parseFloat(pago.value) <= 0) { alert('Pago debe ser mayor a 0'); pago.focus(); return; }
    document.form1.opc.value = 1;
    document.form1.submit();
}
</script>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
<p class="titulo">MODIFICAR PAGO</p>

<?php if (session('error')): ?>
<script>alert('<?= esc(session('error'), 'js') ?>')</script>
<?php endif; ?>

<form id="form1" name="form1" method="post" action="<?= site_url('cobranza/pago/' . $pago['IdPago'] . '/actualizar') ?>">
  <p>
    <label class="m80">Folio:</label>
    <input type="text" disabled="disabled" value="<?= esc($avaluo['Folio']) ?>" size="10" readonly="readonly"/>
  </p>
  <p>
    <label>Trabajo Realizado:</label>
    <input type="text" disabled="disabled" value="<?= esc($avaluo['Trabajo']) ?>" size="30" maxlength="30" />
  </p>
  <p>
    <label class="m67">Cliente:</label>
    <input type="text" disabled="disabled" value="<?= esc($avaluo['Cliente']) ?>" size="70" maxlength="60"/>
  </p>
  <p>
    <label class="m41">Honorarios: $</label>
    <input type="text" disabled="disabled" value="<?= number_format((float) $avaluo['Honorarios'], 2) ?>" size="10" maxlength="10" />
  </p>
  <table width="550" class="tablacobranza">
    <tr>
      <td width="150" class="tituloTablaCobranza">Fecha</td>
      <td width="150" class="tituloTablaCobranza">Pago $</td>
    </tr>
    <?php foreach ($pagos as $i => $p): ?>
    <tr <?= $i % 2 === 1 ? 'bgcolor="#DAE6F3"' : '' ?>>
      <td width="150"><?= esc($p['Fecha']) ?></td>
      <td width="150">
        <?php if ((int) $p['IdPago'] === (int) $pago['IdPago']): ?>
        <input name="Pago" type="text" value="<?= esc($p['Importe']) ?>" />
        <?php else: ?>
        <span class="m8"><?= number_format((float) $p['Importe'], 2) ?></span>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
  <p>&nbsp;</p>
  <p align="center">
    <input type="button" value="Guardar" onclick="validacion(1)"/>
    <input type="button" value="Cancelar" onclick="validacion(2)"/>
    <input type="hidden" name="opc" />
  </p>
</form>
<?= $this->endSection() ?>
