<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo">COBRANZA HISTORIAL</p>

<?php if (session('mensaje')): ?>
<script>alert('<?= esc(session('mensaje'), 'js') ?>')</script>
<?php endif; ?>

<form id="form1" name="form1" method="get" action="<?= site_url('cobranza') ?>">
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
      <td width="200" class="tituloTablaCobranza">Saldo por Cobrar $</td>
      <td width="25" class="tituloTablaCobranza"></td>
      <td width="25" class="tituloTablaCobranza"></td>
    </tr>
    <?php $acumulado = 0; ?>
    <?php foreach ($pagos as $i => $p): ?>
    <?php $acumulado += (float) $p['Importe']; $saldo = (float) $avaluo['Honorarios'] - $acumulado; ?>
    <tr <?= $i % 2 === 1 ? 'bgcolor="#DAE6F3"' : '' ?>>
      <td width="150"><?= esc($p['Fecha']) ?></td>
      <td width="150"><span class="m8"><?= number_format((float) $p['Importe'], 2) ?></span></td>
      <td width="200"><span class="m8"><?= number_format($saldo, 2) ?></span></td>
      <td width="25"><a href="<?= site_url('cobranza/pago/' . $p['IdPago'] . '/editar') ?>"><img src="<?= base_url('assets/img/edit.png') ?>" width="22" height="22" border="0"/></a></td>
      <td width="25"><a href="<?= site_url('cobranza/pago/' . $p['IdPago'] . '/eliminar') ?>"><img src="<?= base_url('assets/img/document-close-3.png') ?>" width="24" height="24" border="0"/></a></td>
    </tr>
    <?php endforeach; ?>
    <tr>
      <td width="150"></td>
      <td width="150" class="bordercelda">$<?= number_format($acumulado, 2) ?></td>
      <td width="200" class="bordercelda">$<?= number_format((float) $avaluo['Honorarios'] - $acumulado, 2) ?></td>
      <td width="25"></td>
      <td width="25"></td>
    </tr>
  </table>
  <p align="center"><input type="submit" value="Regresar" /></p>
</form>
<?= $this->endSection() ?>
