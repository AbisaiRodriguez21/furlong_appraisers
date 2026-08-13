<table align="left" cellspacing="2" class="tabla">
  <tr>
    <td width="100" class="tituloTabla">FOLIO</td>
    <td class="tituloTabla" width="480">TRABAJO</td>
    <td class="tituloTabla" width="480">CLIENTE</td>
    <td class="tituloTabla" width="100">MONTO</td>
  </tr>
</table>
<div id="scroll">
<table align="left" cellspacing="2" class="tabla" id="tab">
<?php $total = 0; ?>
<?php foreach ($filas as $i => $f): ?>
<?php $total += (float) $f['Importe']; ?>
  <tr <?= $i % 2 === 1 ? 'bgcolor="#DAE6F3"' : '' ?>>
    <td width="118" align="center" class="celdasel"><?= esc($f['Folio']) ?></td>
    <td width="460" class="celdasel"><?= esc($f['Trabajo']) ?></td>
    <td width="465" class="celdasel"><?= esc($f['Cliente']) ?></td>
    <td width="117" align="right" class="celdasel"><?= number_format((float) $f['Importe'], 2) ?></td>
  </tr>
<?php endforeach; ?>
</table>
</div>
<br />
<p align="right"><label class="subtitulo">Monto Total de Pagos: $<?= number_format($total, 2) ?></label></p>
