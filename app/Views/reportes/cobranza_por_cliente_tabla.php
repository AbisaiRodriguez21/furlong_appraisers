<table align="left" cellspacing="2" class="tabla">
  <tr>
    <td width="67" class="tituloTabla">FOLIO</td>
    <td class="tituloTabla" width="681">TRABAJO</td>
    <?php if ($tipo === 0): ?><td class="tituloTabla" width="106">PAGOS</td><?php endif; ?>
    <td class="tituloTabla" width="106">ADEUDOS</td>
  </tr>
</table>
<div id="scroll">
<table align="left" cellspacing="2" class="tabla" id="tab">
<?php $totalP = 0; $totalA = 0; ?>
<?php foreach ($filas as $i => $f): ?>
<?php $totalP += $f['Pago']; $totalA += $f['Adeudo']; ?>
<?php $color = (int) $f['Tipo'] === 1 ? '#FF9933' : ($i % 2 === 1 ? '#DAE6F3' : ''); ?>
  <tr <?= $color ? 'bgcolor="' . $color . '"' : '' ?>>
    <td width="80" align="center" class="celdasel"><?= esc($f['Folio']) ?></td>
    <td width="646" class="celdasel"><?= esc($f['Trabajo']) ?></td>
    <?php if ($tipo === 0): ?><td width="117" class="celdasel" align="right">$ <?= number_format($f['Pago'], 2) ?></td><?php endif; ?>
    <td width="117" align="right" class="celdasel">$ <?= number_format($f['Adeudo'], 2) ?></td>
  </tr>
<?php endforeach; ?>
</table>
</div>
<?php if ($tipo === 0): ?>
<p align="right"><label class="subtitulo">Monto Total de Pagos: $<?= number_format($totalP, 2) ?></label></p>
<?php endif; ?>
<p align="right"><label class="subtitulo">Monto Total de Adeudos: $<?= number_format($totalA, 2) ?></label></p>
