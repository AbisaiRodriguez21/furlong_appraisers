<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<script type="text/javascript">
function validaFloat(numero) {
    return /^([0-9])*[.]?[0-9]*$/.test(numero) ? 1 : 0;
}

function esc2(a) {
    var valor = a.value;
    if (valor.length == 0) { a.value = '0.00'; return; }
    if (validaFloat(valor) != 1) return;
    var pos = valor.indexOf('.');
    var long = valor.length;
    if (pos == -1) a.value = valor + '.00';
    else if (pos == 0 && valor == '.') a.value = '0.00';
    else if (pos == (long - 1)) a.value = valor + '00';
    else if (pos > 0 && pos == (long - 2)) a.value = valor + '0';
    else if (pos == 0 && long == 2) a.value = '0' + valor + '0';
    else if (pos == 0 && long == 3) a.value = '0' + valor;
    else if (pos == 0 && long > 3) a.value = '0' + valor.substring(0, 3);
    else if (pos > 0 && long > (pos + 3)) a.value = valor.substring(0, pos + 3);
}

function validacion(opcion) {
    if (opcion == 2) {
        document.form1.opc.value = 2;
        document.form1.submit();
        return;
    }
    var pago = document.form1.pago;
    if (pago.value.length == 0) { alert('Ingrese Pago'); pago.focus(); return; }
    if (validaFloat(pago.value) == 0) { alert('Valor Incorrecto para Pago'); pago.focus(); return; }
    if (parseFloat(pago.value) <= 0) { alert('Pago debe ser mayor a 0'); pago.focus(); return; }
    if (parseFloat(pago.value) > parseFloat(document.form1.saldo.value)) { alert('El Pago Excede el saldo por cobrar'); pago.focus(); return; }
    document.form1.opc.value = 1;
    document.form1.submit();
}
</script>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
<p class="titulo">COBRANZA</p>

<?php if (session('error')): ?>
<script>alert('<?= esc(session('error'), 'js') ?>')</script>
<?php endif; ?>

<form id="form1" name="form1" method="post" action="<?= site_url('cobranza/' . $avaluo['Folio'] . '/pagar') ?>">
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
  <p>
    <label class="m4">Saldo por Cobrar: $</label>
    <input type="text" disabled="disabled" value="<?= number_format($saldo, 2) ?>" size="10" maxlength="10"/>
    <input name="saldo" type="hidden" value="<?= esc($saldo) ?>" />
  </p>
  <p>
    <label class="m78">Pago: $</label>
    <input name="pago" type="text" onblur="esc2(this)" value="0.00" size="10" maxlength="10"/>
  </p>
  <p align="center">
    <input type="button" value="Guardar" onclick="validacion(1)"/>
    <input type="button" value="Cancelar" onclick="validacion(2)"/>
    <input type="hidden" name="opc" />
  </p>
</form>
<?= $this->endSection() ?>
