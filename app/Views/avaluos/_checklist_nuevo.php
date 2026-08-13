<link href="<?= base_url('assets/css/Estilo.css') ?>" rel="stylesheet" type="text/css" />
<p>Documentos que ya se recibieron (marca los que traiga el cliente, los demás quedan pendientes):</p>
<div id="scroll2"><table class="tab" align="left">
<tr class="tituloTabla"><td class="tituloTabla" width="300"><b>DOCUMENTOS</b></td></tr>
<?php foreach ($documentos as $i => $doc): ?>
<tr <?= $i % 2 === 0 ? 'bgcolor="#DAE6F3"' : '' ?>>
  <td><input name="documentos[<?= $doc['IdDocumento'] ?>]" type="checkbox" value="1"><?= esc($doc['Nombre']) ?><?= (int) $doc['Tipo'] === 1 ? ' (obligatorio)' : '' ?></td>
</tr>
<?php endforeach; ?>
</table></div><br>
