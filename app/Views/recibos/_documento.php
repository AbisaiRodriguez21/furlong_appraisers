<?php
/**
 * @var array $recibo
 * @var string|null $letraTexto  Si ya se conoce (recién guardado). Si no, se
 *      calcula en el navegador con la misma librería del original.
 */
?>
<table width="804" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr><td>
  <fieldset style="border:2px solid #006;">
  <br />
  <p class="titulo2">RECIBO DE HONORARIOS</p>
  <fieldset style="border:2px solid #006;">
  <table width="800" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="100">RECIBO</td>
      <td width="200">NO: <?= esc($recibo['NoFolio']) ?> A</td>
      <td width="100">&nbsp;</td>
      <td width="400">FECHA: <?= esc($recibo['Fecha']) ?></td>
    </tr>
  </table>
  </fieldset>
  <br />
  <?= view('recibos/_membrete') ?>
  <br />
  <fieldset style="border:2px solid #006;">
  <table width="800" border="0" cellspacing="4" cellpadding="0">
    <tr>
      <td width="200" align="right">RECIBÍ DE:</td>
      <td width="600" align="left"><?= esc($recibo['NombreCliente']) ?></td>
    </tr>
    <tr>
      <td width="200" align="right" valign="top">DIRECCIÓN:</td>
      <td width="600" align="left" valign="top"><?= esc($recibo['Direccion']) ?></td>
    </tr>
    <tr>
      <td width="200" align="right">R.F.C.:</td>
      <td width="600" align="left"><?= esc($recibo['RFC']) ?></td>
    </tr>
    <tr>
      <td width="200" align="right" valign="top">POR CONCEPTO DE: </td>
      <td width="600" align="left" valign="top"><?= esc($recibo['Concepto']) ?></td>
    </tr>
    <tr>
      <td width="200" align="right">CANTIDAD:$</td>
      <td width="600" align="left"><?= number_format((float) $recibo['Cantidad'], 2) ?></td>
    </tr>
    <tr>
      <td width="200" align="right" valign="top">CANTIDAD CON LETRA:</td>
      <td width="600" align="left" valign="top">
        <?php if ($letraTexto !== null): ?>
        <label id="canletra"><?= esc($letraTexto) ?></label>
        <?php else: ?>
        <label id="canletra"></label>
        <script type="text/javascript">covertirNumLetras2(<?= (float) $recibo['Cantidad'] ?>)</script>
        <?php endif; ?>
      </td>
    </tr>
    <tr>
      <td align="right">METODO DE PAGO:</td>
      <td align="left"><?= ['','EFECTIVO','CHEQUE','TRANSFERENCIA BANCARIA'][(int) $recibo['MetodoPago']] ?? 'NINGUNO' ?></td>
    </tr>
    <?php if ((int) $recibo['MetodoPago'] === 3): ?>
    <tr>
      <td width="200" align="right" valign="top">NÚM. CTA. BANCARIA:</td>
      <td width="600" align="left" valign="top"><?= esc($recibo['CtaBanco']) ?></td>
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
      <td width="100" align="right">&nbsp;</td>
      <td width="250" align="left">HONORARIOS: </td>
      <td width="12" align="left">$</td>
      <td width="180" align="right"><?= number_format((float) $recibo['Honorarios'], 2) ?></td>
      <td width="58">&nbsp;</td>
    </tr>
    <tr>
      <td width="100" align="right">&nbsp;</td>
      <td width="250" align="left">+I.V.A.: </td>
      <td width="12" align="left">$</td>
      <td width="180" align="right"><?= number_format((float) $recibo['Iva'], 2) ?></td>
      <td width="58">&nbsp;</td>
    </tr>
    <tr>
      <td width="100" align="right">&nbsp;</td>
      <td width="250" align="left">SUBTOTAL: </td>
      <td width="12" align="left">$</td>
      <td width="180" align="right"><?= number_format((float) $recibo['Subtotal'], 2) ?></td>
      <td width="58">&nbsp;</td>
    </tr>
    <tr>
      <td width="100" align="right">&nbsp;</td>
      <td width="250" align="left">(-)RETENCION I.S.R.: </td>
      <td width="12" align="left">$</td>
      <td width="180" align="right"><?= (float) $recibo['RetISR'] > 0 ? number_format((float) $recibo['RetISR'], 2) : '' ?></td>
      <td width="58">&nbsp;</td>
    </tr>
    <tr>
      <td width="100" align="right">&nbsp;</td>
      <td width="250" align="left">(-)RETENCION I.V.A.: </td>
      <td width="12" align="left">$</td>
      <td width="180" align="right"><?= (float) $recibo['RetIVA'] > 0 ? number_format((float) $recibo['RetIVA'], 2) : '' ?></td>
      <td width="58">&nbsp;</td>
    </tr>
    <tr>
      <td width="100" align="right">&nbsp;</td>
      <td width="250" align="left">NETO A RECIBIR: </td>
      <td width="12" align="left">$</td>
      <td width="180" align="right"><?= number_format((float) $recibo['Subtotal'] - (float) $recibo['RetISR'] - (float) $recibo['RetIVA'], 2) ?></td>
      <td width="58">&nbsp;</td>
    </tr>
    <tr><td width="100">&nbsp;</td><td width="250">&nbsp;</td><td width="12"></td><td width="180"></td><td width="58"></td></tr>
    <tr>
      <td width="100">&nbsp;</td><td width="250">&nbsp;</td>
      <td colspan="3" align="center" style="font-weight:bold"><p>FIRMA</p><br /><p>_____________________<br />JAVIER FURLONG SALGADO</p></td>
    </tr>
  </table>
  </fieldset>
  </fieldset>
  </td></tr>
</table>
