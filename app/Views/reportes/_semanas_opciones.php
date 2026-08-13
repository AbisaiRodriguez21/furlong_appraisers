<option value="-1">Seleccione Semana</option>
<?php foreach ($semanas as $s): ?>
<?php
  $ini = date('d/m/Y', strtotime($s['FechaIni']));
  $fin = date('d/m/Y', strtotime($s['FechaFin']));
  $num = str_pad((string) $s['No'], 2, '0', STR_PAD_LEFT);
?>
<option value="<?= $s['IdSem'] ?>">Semana <?= $num ?> - <?= $ini ?> - <?= $fin ?></option>
<?php endforeach; ?>
