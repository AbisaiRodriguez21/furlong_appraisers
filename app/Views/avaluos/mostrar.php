<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo">CONSULTAR AVALÚO</p>
<p>
  <span class="m105">Folio:</span>
  <input type="text" value="<?= esc($avaluo['Folio']) ?>" readonly="readonly" />
  <span class="m135">Fecha de Solicitud:</span>
  <input type="date" value="<?= esc($avaluo['FechaSolicitud']) ?>" readonly="readonly" disabled />
</p>
<p>Nombre del Inspector:
  <input type="text" size="40" value="<?= esc($avaluo['NombreInspeccion']) ?>" readonly="readonly" />
  Fecha de Inspección:
  <input type="date" value="<?= esc($avaluo['FechaInspeccion']) ?>" readonly="readonly" disabled />
  Fecha de Vencimiento:
  <input type="date" value="<?= esc($avaluo['FechaVencimiento']) ?>" readonly="readonly" disabled />
</p>
<p><span class="m26">Fecha de Entrega:</span>
  <input type="date" value="<?= esc($avaluo['FechaEntregado'] ?? '') ?>" readonly="readonly" disabled />
  <label style="margin-left:171px;">Fecha de Terminación:</label>
  <input type="date" value="<?= esc($avaluo['FechaTerminacion'] ?? '') ?>" readonly="readonly" disabled />
</p>
<p class="m92">Cliente:
  <input type="text" value="<?= esc($avaluo['NombreCliente']) ?>" size="50" readonly="readonly" />
</p>
<p><span class="m71">Solicitante:</span>
  <input type="text" size="50" value="<?= esc($avaluo['NombreSolicitante']) ?>" readonly="readonly" />
</p>
<p><span class="m68">Propietario:</span>
  <input type="text" size="50" value="<?= esc($avaluo['NombrePropietario']) ?>" readonly="readonly"/>
</p>
<p style="font-weight:bolder" align="center">Inmueble a valuar</p>
<p><span class="m78">Direccion:</span>
  <input type="text" value="<?= esc($avaluo['Direccion']) ?>" size="50" readonly="readonly" />
  <span class="m83">Colonia:</span>
  <input type="text" value="<?= esc($avaluo['Colonia']) ?>" readonly="readonly" size="50" />
</p>
<p><label class="m90">Ciudad:</label>
  <input type="text" value="<?= esc($avaluo['Ciudad']) ?>" size="50" readonly="readonly" />
  <span class="m85">Estado:</span>
  <input type="text" value="<?= esc($avaluo['Estado']) ?>" readonly="readonly" />
</p>
<p><span class="m14">Nombre del archivo:</span>
  <input type="text" value="<?= esc($avaluo['NombreArchivo']) ?>" readonly="readonly" />
</p>
<p><span class="m35">Valor del Avaluo:</span>
  <input type="text" value="<?= esc($avaluo['ValorAvaluo']) ?>" readonly="readonly" />
  <span class="m182">Honorarios:</span>
  <input type="text" value="<?= esc($avaluo['Honorarios']) ?>" readonly="readonly"/>
  <label class="m55"><?= (int) $avaluo['Tipo'] === 0 ? 'Real' : 'Aproximado' ?></label>
</p>
<p><span class="m72">Valor Real:</span>
  <input type="text" value="<?= esc($avaluo['ValorReal']) ?>" readonly="readonly" />
  <label class="m202">Imagen: </label><img src="<?= site_url('avaluos/' . $avaluo['Folio'] . '/imagen') ?>" width="80">
</p>
<p><span class="m24">Trabajo a Realizar:</span> <?= esc($avaluo['NombreTrabajo']) ?></p>
<fieldset id="Documentos" style="border:0">
<p>
<?php if ($checklist !== []): ?>
<div id="scroll3"><table width="600px" align="left">
<tr class="tituloTabla">
  <td class="tituloTabla" width="300"><b>DOCUMENTO</b></td>
  <td class="tituloTabla" width="300"><b>USUARIO</b></td>
  <td class="tituloTabla" width="300"><b>FECHA RECIBIDO</b></td>
</tr>
<?php foreach ($checklist as $i => $d): ?>
<tr align="center" <?= $i % 2 === 0 ? 'bgcolor="#DAE6F3"' : '' ?>>
  <td><?= esc($d['Nombre']) ?></td>
  <td><?= (int) $d['StatusR'] === 1 ? esc($d['Usuario']) : '' ?></td>
  <td><?= (int) $d['StatusR'] === 1 ? esc($d['FechaRecibido']) : '' ?></td>
</tr>
<?php endforeach; ?>
</table></div>
<?php endif; ?>
</p>
<p>
  <label class="m65">Comentario:</label></p><p>
  <textarea class="m143" cols="81" rows="5" readonly="readonly"><?= esc($avaluo['Comentario']) ?></textarea>
</p>
</fieldset>
<p align="center"><a href="<?= site_url('avaluos') ?>"><input type="button" value="Regresar" /></a></p>
<?= $this->endSection() ?>
