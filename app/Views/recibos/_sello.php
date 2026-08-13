<?php
/** @var int $folio */
if ($folio > 500 && $folio <= 700) {
    $imagen  = '23001263_FUSJ650512D37.png';
    $sicofi  = 'SICOFI 23001263';
    $vigencia = 'ESTE COMPROBANTE TENDRÁ UNA VIGENCIA DE DOS AÑOS CONTADOS A PARTIR DE LA FECHA DE APROBACIÓN DE LA ASIGNACIÓN DE FOLIOS, LA CUAL ES 24/02/2012';
} elseif ($folio > 700 && $folio <= 1200) {
    $imagen  = '12demarzo201324818970_FUSJ650512D37.png';
    $sicofi  = 'SICOFI 24818970';
    $vigencia = 'ESTE COMPROBANTE TENDRÁ UNA VIGENCIA DE DOS AÑOS CONTADOS A PARTIR DE LA FECHA DE APROBACIÓN DE LA ASIGNACIÓN DE FOLIOS, LA CUAL ES 12/03/2013';
} else {
    $imagen = null;
    $sicofi = '';
    $vigencia = '';
}
?>
<td width="200" rowspan="8">
  <p align="center">
    <?php if ($imagen): ?><img src="<?= base_url('assets/img/' . $imagen) ?>" width="131" height="131" /><?php endif; ?>
    <br /><br />
    <span style="font-size:8px; font-weight:bold">"NÚMERO DE APROBACIÓN <?= esc($sicofi) ?>"</span>
  </p>
  <p align="center" style="font-size:11px; font-weight:bold">"IMPUESTO RETENIDO DE CONFORMIDAD CON LA LEY DEL IMPUESTO AL VALOR AGREGADO"</p>
  <p align="center" style="font-size:11px; font-weight:bold">EFECTOS FISCALES AL PAGO <br />PAGO HECHO EN UNA SOLA EXHIBICIÓN</p>
  <p align="center" style="font-size:8px; font-weight:bold">"LA REPRODUCCIÓN APÓCRIFA DE ESTE COMPROBANTE CONSTITUYE UN DELITO EN LOS TÉRMINOS DE LAS DISPOSICIONES FISCALES"</p>
  <p align="center" style="font-size:8px; font-weight:bold">"<?= esc($vigencia) ?>"</p>
</td>
