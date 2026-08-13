<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<style type="text/css">.aliderecha{ text-align:right;}</style>
<script type="text/javascript" src="<?= base_url('assets/js/ReciboHonorarios.js') ?>"></script>
<link href="<?= base_url('assets/css/impresion.css') ?>" rel="stylesheet" type="text/css" media="print"/>
<script type="text/javascript">
function abrir(tipo) {
    var left = (screen.width - 1024) / 2;
    var top = (screen.height - 650) / 2;
    window.open('<?= site_url('clientes') ?>?t=' + tipo + '&ventana=2', '', 'width=1024, height=650, left=' + left + ',top=' + top);
}
function CopiarDatos(Numero, Nombre, rfc, dir) {
    document.form1.NombreCliente.value = Nombre;
    document.getElementById('numcli').value = Numero;
    document.getElementById('rfc').innerHTML = rfc;
    document.getElementById('dir').innerHTML = dir;
}
function validaFloat(numero) {
    return /^([0-9])*[.]?[0-9]*$/.test(numero) ? 1 : 0;
}
function validacion(Opcion) {
    if (Opcion == 1) {
        if (document.form1.NombreCliente.value.length == 0) { alert('Ingrese Cliente'); return; }
        if (document.form1.fecha.value.length == 0) { alert('Ingrese Fecha'); document.form1.fecha.focus(); return; }
        if (validaFloat(document.form1.cantidad.value.replace(',', '')) == 0) { alert('Valor Incorrecto para Cantidad'); return; }
        if (parseFloat(document.form1.cantidad.value.replace(',', '')) <= 0.00) { alert('Cantidad debe ser mayor a 0'); return; }
        var indice = document.form1.metodoPago.selectedIndex;
        if (indice == 0) { alert('Seleccione Metodo de Pago'); document.form1.metodoPago.focus(); return; }
        if (indice == 3 && document.getElementById('cuenta') && document.getElementById('cuenta').value.length == 0) {
            alert('Ingrese Número de cuenta bancaria');
            return;
        }
    }
    document.form1.opc.value = Opcion;
    document.form1.submit();
}
function insertarcol(m) {
    var tabla = document.getElementById('datos');
    var numFilas = tabla.rows.length;
    if (m.selectedIndex == 3 && !document.getElementById('cuenta')) {
        agregarFila();
        document.getElementById('cuenta').focus();
    } else if (m.selectedIndex != 3 && document.getElementById('rowDetalle_1')) {
        cancelarFila();
    }
}
function agregarFila() {
    var objTr = document.createElement('tr');
    objTr.id = 'rowDetalle_1';
    var objTd1 = document.createElement('td');
    objTd1.innerHTML = 'NUM. CTA. BANCARIA:';
    objTd1.className = 'aliderecha';
    objTr.appendChild(objTd1);
    var objTd2 = document.createElement('td');
    var ele = document.createElement('input');
    ele.type = 'text'; ele.name = 'cuenta'; ele.id = 'cuenta'; ele.size = 30; ele.maxLength = 20;
    objTd2.appendChild(ele);
    objTr.appendChild(objTd2);
    document.getElementById('datos').appendChild(objTr);
    return false;
}
function cancelarFila() {
    var obj = document.getElementById('rowDetalle_1');
    obj.parentNode.removeChild(obj);
}
window.addEventListener('DOMContentLoaded', function () {
    convertirNumLetras(document.getElementById('cantidad').value);
});
</script>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
<form id="form1" name="form1" method="post" action="<?= site_url('recibos/' . $recibo['NoFolio'] . '/actualizar') ?>">
<table width="804" border="0" cellspacing="0" cellpadding="0" align="center" id="oculto">
  <tr><td>
  <fieldset style="border:2px solid #006;">
  <br />
  <p class="titulo2">RECIBO DE HONORARIOS</p>
  <fieldset style="border:2px solid #006;">
  <table width="800" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="100">RECIBO</td>
      <td width="200">NO: <?= esc($recibo['NoFolio']) ?> A</td>
      <td width="100"></td>
      <td width="400">
        <label for="fecha">FECHA: </label>
        <input name="fecha" type="text" id="fecha" value="<?= esc($recibo['Fecha']) ?>" size="35" maxlength="25" onkeyup="this.value=this.value.toUpperCase()"/>
      </td>
    </tr>
  </table>
  </fieldset>
  <br />
  <?= view('recibos/_membrete') ?>
  <br />
  <fieldset style="border:2px solid #006;">
  <table width="800" border="0" cellspacing="4" cellpadding="0" id="datos">
    <tr>
      <td width="175" align="right">RECIBÍ DE:</td>
      <td width="625" align="left">
        <input name="NombreCliente" id="NombreCliente" type="text" size="70" readonly="readonly" value="<?= esc($recibo['NombreCliente']) ?>"/>
        <input type="button" value="..." onclick="abrir(1)" />
        <input type="hidden" name="numcli" id="numcli" value="<?= esc($recibo['IdCliente']) ?>"/>
      </td>
    </tr>
    <tr>
      <td width="175" align="right" valign="top">DIRECCIÓN:</td>
      <td width="625" align="left" valign="top"><span id="dir"><?= esc($recibo['Direccion']) ?></span></td>
    </tr>
    <tr>
      <td width="175" align="right">R.F.C.:</td>
      <td width="625" align="left"><span id="rfc"><?= esc($recibo['RFC']) ?></span></td>
    </tr>
    <tr>
      <td width="175" align="right" valign="top">POR CONCEPTO DE: </td>
      <td width="625" align="left" valign="top"><textarea name="concepto" id="concepto" cols="70" rows="2" onkeyup="this.value=this.value.toUpperCase()"><?= esc($recibo['Concepto']) ?></textarea></td>
    </tr>
    <tr>
      <td width="175" align="right">CANTIDAD:$</td>
      <td width="625" align="left"><input name="cantidad" type="text" id="cantidad" onblur="esc(this)" value="<?= number_format((float) $recibo['Cantidad'], 2) ?>" readonly="readonly"/></td>
    </tr>
    <tr>
      <td width="175" align="right">CANTIDAD CON LETRA:</td>
      <td width="625" align="left"><label id="canletra"></label><input type="hidden" name="letra" id="letra"/></td>
    </tr>
    <tr>
      <td align="right">METODO DE PAGO:</td>
      <td align="left">
        <select name="metodoPago" id="metodoPago" onchange="insertarcol(this)">
          <option value="0">NINGUNO</option>
          <option value="1" <?= (int) $recibo['MetodoPago'] === 1 ? 'selected' : '' ?>>EFECTIVO</option>
          <option value="2" <?= (int) $recibo['MetodoPago'] === 2 ? 'selected' : '' ?>>CHEQUE</option>
          <option value="3" <?= (int) $recibo['MetodoPago'] === 3 ? 'selected' : '' ?>>TRANSFERENCIA BANCARIA</option>
        </select>
      </td>
    </tr>
    <?php if ((int) $recibo['MetodoPago'] === 3): ?>
    <tr id="rowDetalle_1">
      <td width="175" align="right">NUM. CTA. BANCARIA:</td>
      <td width="625" align="left"><input type="text" name="cuenta" id="cuenta" size="30" maxlength="20" value="<?= esc($recibo['CtaBanco']) ?>"/></td>
    </tr>
    <?php endif; ?>
  </table>
  </fieldset>
  <br />
  <fieldset style="border:2px solid #006;">
  <p align="left">RELACION:</p>
  <table width="800" border="0" cellspacing="4" cellpadding="0">
    <tr>
      <?= view('recibos/_sello', ['folio' => (int) $recibo['NoFolio']]) ?>
      <td width="100" align="left">&nbsp;</td>
      <td width="250" align="left">HONORARIOS: </td>
      <td width="50" align="left">$</td>
      <td width="200" align="left"><input name="hono" type="text" id="hono" value="<?= number_format((float) $recibo['Honorarios'], 2) ?>" onblur="esc(this)" onkeypress="calcularT(event)"/></td>
    </tr>
    <tr>
      <td width="100" align="left">&nbsp;</td>
      <td width="250" align="left">+I.V.A.: </td>
      <td width="50" align="left">$</td>
      <td width="200" align="left"><input name="iva" type="text" id="iva" value="<?= number_format((float) $recibo['Iva'], 2) ?>" onblur="esc(this)"/></td>
    </tr>
    <tr>
      <td width="100" align="left">&nbsp;</td>
      <td width="250" align="left">SUBTOTAL: </td>
      <td width="50" align="left">$</td>
      <td width="200" align="left"><input name="subtotal" type="text" id="subtotal" value="<?= number_format((float) $recibo['Subtotal'], 2) ?>" onblur="esc(this)"/></td>
    </tr>
    <tr>
      <td width="100" align="left">&nbsp;</td>
      <td width="250" align="left">(-)RETENCION I.S.R.: </td>
      <td width="50" align="left">$</td>
      <td width="200" align="left"><input type="text" name="retISR" id="retISR" value="<?= (float) $recibo['RetISR'] > 0 ? number_format((float) $recibo['RetISR'], 2) : '' ?>" onkeypress="calcularT(event)"/></td>
    </tr>
    <tr>
      <td width="100" align="left">&nbsp;</td>
      <td width="250" align="left">(-)RETENCION I.V.A.: </td>
      <td width="50" align="left">$</td>
      <td width="200" align="left"><input type="text" name="retIVA" id="retIVA" value="<?= (float) $recibo['RetIVA'] > 0 ? number_format((float) $recibo['RetIVA'], 2) : '' ?>" onkeypress="calcularT(event)" /></td>
    </tr>
    <tr>
      <td width="100" align="left">&nbsp;</td>
      <td width="250" align="left">NETO A RECIBIR: </td>
      <td width="50" align="left">$</td>
      <td width="200" align="left"><input name="neto" type="text" id="neto" value="<?= number_format((float) $recibo['Subtotal'] - (float) $recibo['RetISR'] - (float) $recibo['RetIVA'], 2) ?>" onblur="esc(this)"/></td>
    </tr>
    <tr><td width="100">&nbsp;</td><td width="250">&nbsp;</td><td width="50"></td><td width="200"></td></tr>
    <tr>
      <td width="100">&nbsp;</td><td width="250">&nbsp;</td>
      <td colspan="2" align="center" style="font-weight:bold"><p>FIRMA</p><br /><p>_____________________<br />JAVIER FURLONG SALGADO</p></td>
    </tr>
  </table>
  </fieldset>
  </fieldset>
  </td></tr>
</table>
<p id="oculto" align="center">
<input type="button" value="Calcular retencion I.V.A." onclick="calcularIVA()"/>
<input type="button" value="Calcular retencion I.S.R." onclick="calcularISR()"/>
<input type="button" value="Guardar" onclick="validacion(1)"/>
<input type="button" value="Cancelar" onclick="validacion(2)"/>
<input type="hidden" name="opc" id="opc" />
</p>
</form>
<?= $this->endSection() ?>
