<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<script type="text/javascript">
function CopiarDatos(Numero, Nombre, t) {
    if (t == 1) {
        document.form1.NombreCliente.value = Nombre;
        document.form1.cliente.value = Numero;
    } else if (t == 2) {
        document.form1.NombreSol.value = Nombre;
        document.form1.solicitante.value = Numero;
    } else if (t == 3) {
        document.form1.NombreProp.value = Nombre;
        document.form1.propietario.value = Numero;
    }
}

function abrir(tipo) {
    var left = (screen.width - 1024) / 2;
    var top = (screen.height - 650) / 2;
    window.open('<?= site_url('clientes') ?>?t=' + tipo, '', 'width=1024, height=650, left=' + left + ',top=' + top);
}

function ir() {
    location.href = '<?= site_url('avaluos') ?>';
}

function validaFloat(numero) {
    return /^([0-9])*[.]?[0-9]*$/.test(numero) ? 1 : 0;
}

function esc2(a) {
    var valor = a.value;
    if (valor.length == 0 || validaFloat(valor) != 1) {
        a.value = '0.00';
        return;
    }
    var pos = valor.indexOf('.');
    var long = valor.length;
    if (pos == -1) a.value = valor + '.00';
    else if (pos == 0 && valor == '.') a.value = '0.00';
    else if (pos == (long - 1)) a.value = valor + '00';
    else if (pos > 0 && pos == (long - 2)) a.value = valor + '0';
    else if (pos == 0 && long == 2) a.value = '0' + valor + '0';
    else if (pos == 0 && long > 2) a.value = '0' + valor.substring(0, 3);
}

function validar() {
    if (document.form1.direccion.value.length == 0) { alert('Ingrese Direccion'); document.form1.direccion.focus(); return; }
    if (document.form1.colonia.value.length == 0) { alert('Ingrese Colonia'); document.form1.colonia.focus(); return; }
    if (document.form1.ciudad.value.length == 0) { alert('Ingrese Ciudad'); document.form1.ciudad.focus(); return; }
    if (!/^([0-9]){0,9}[.]?[0-9]{0,2}$/.test(document.form1.valorAvaluo.value)) { alert('Valor muy grande para Valor de Avaluo'); return; }
    if (!/^([0-9]){0,9}[.]?[0-9]{0,2}$/.test(document.form1.valorReal.value)) { alert('Valor muy grande para Valor Real'); return; }
    if (!/^([0-9]){0,7}[.]?[0-9]{0,2}$/.test(document.form1.honorarios.value)) { alert('Valor muy grande para Honorarios'); return; }

    var statusSel = document.getElementById('status');
    var statusVal = statusSel ? statusSel.value : '0';
    var fechaEntrega = document.form1.fechaEntrega.value;
    var fechaTer = document.form1.fechaTer.value;
    var fechaVenc = document.form1.fechaVencimiento.value;
    var hoy = new Date().toISOString().slice(0, 10);

    if (statusVal == '5') {
        if (fechaEntrega.length != 0) { alert('No se puede ingresar Fecha de Entrega en este Status'); return; }
        if (hoy > fechaVenc) { alert('La Fecha de Vencimiento ya expiro'); return; }
    }
    if (statusVal == '7' && fechaEntrega.length != 0) {
        alert('No se puede ingresar Fecha de Entrega en este Status');
        return;
    }
    if (fechaTer.length != 0 && fechaEntrega.length == 0) {
        var checks = document.querySelectorAll('.doc-pendiente');
        for (var i = 0; i < checks.length; i++) {
            if (checks[i].dataset.obligatorio == '1' && !checks[i].checked) {
                alert('Es necesario entregar el documento ' + checks[i].dataset.nombre);
                return;
            }
        }
    }
    if (fechaEntrega.length != 0 && fechaTer.length == 0) {
        alert('Es necesario Ingresar Fecha de Terminación');
        return;
    }
    document.form1.submit();
}
</script>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
<p class="titulo">MODIFICAR AVALÚO</p>

<?php if (session('error')): ?>
<script>alert('<?= esc(session('error'), 'js') ?>')</script>
<?php endif; ?>

<form enctype="multipart/form-data" id="form1" name="form1" method="post" action="<?= site_url('avaluos/' . $avaluo['Folio'] . '/actualizar') ?>">
  <p>
    <span class="m105">Folio:</span>
    <input type="text" value="<?= esc($avaluo['Folio']) ?>" readonly="readonly" />
    <span class="m135">Fecha de Solicitud:</span>
    <input name="fechaSolicitud" type="date" value="<?= esc(old('fechaSolicitud', $avaluo['FechaSolicitud'])) ?>" />
  </p>
  <p>Nombre del Inspector:
    <input name="nombreInspector" type="text" size="40" maxlength="60" onkeyup="this.value=this.value.toUpperCase()" value="<?= esc(old('nombreInspector', $avaluo['NombreInspeccion'])) ?>" />
    Fecha de Inspección:
    <input name="fechaInspeccion" type="date" value="<?= esc(old('fechaInspeccion', $avaluo['FechaInspeccion'])) ?>" />
    Fecha de Vencimiento:
    <input name="fechaVencimiento" type="date" id="fechaVencimiento" value="<?= esc(old('fechaVencimiento', $avaluo['FechaVencimiento'])) ?>" />
  </p>
  <p><span class="m26">Fecha de Entrega:</span>
    <input name="fechaEntrega" type="date" value="<?= esc(old('fechaEntrega', $avaluo['FechaEntregado'] ?? '')) ?>" />
    <label style="margin-left:171px;">Fecha de Terminación:</label>
    <input name="fechaTer" type="date" value="<?= esc(old('fechaTer', $avaluo['FechaTerminacion'] ?? '')) ?>" />
  </p>
  <p><span class="m92">Cliente:</span>
    <input name="NombreCliente" id="NombreCliente" type="text" size="50" readonly="readonly" value="<?= esc($avaluo['NombreCliente']) ?>" />
    <input type="button" value="..." onclick="abrir(1)" />
    <input type="hidden" name="cliente" id="cliente" value="<?= esc($avaluo['IdCliente']) ?>" />
  </p>
  <p><span class="m71">Solicitante:</span>
    <input name="NombreSol" id="NombreSol" type="text" size="50" readonly="readonly" value="<?= esc($avaluo['NombreSolicitante']) ?>" />
    <input type="button" value="..." onclick="abrir(2)" />
    <input type="hidden" name="solicitante" id="Solicitante" value="<?= esc($avaluo['Solicitante']) ?>" />
  </p>
  <p><span class="m68">Propietario:</span>
    <input name="NombreProp" id="NombreProp" type="text" size="50" readonly="readonly" value="<?= esc($avaluo['NombrePropietario']) ?>" />
    <input type="button" value="..." onclick="abrir(3)" />
    <input type="hidden" name="propietario" id="Propietario" value="<?= esc($avaluo['Propietario']) ?>" />
  </p>
  <p style="font-weight:bolder" align="center">Inmueble a valuar</p>
  <p><span class="m76">Direccion:</span>
    <input name="direccion" type="text" onkeyup="this.value=this.value.toUpperCase()" value="<?= esc(old('direccion', $avaluo['Direccion'])) ?>" size="50" maxlength="50" />
    <span class="m83">Colonia:</span>
    <input name="colonia" type="text" onkeyup="this.value=this.value.toUpperCase()" value="<?= esc(old('colonia', $avaluo['Colonia'])) ?>" maxlength="35" size="50" />
  </p>
  <p><label class="m90">Ciudad:</label>
    <input name="ciudad" type="text" onkeyup="this.value=this.value.toUpperCase()" value="<?= esc(old('ciudad', $avaluo['Ciudad'])) ?>" size="50" maxlength="35" />
    <span class="m85">Estado:</span>
    <select name="estado">
      <?php foreach ($estados as $e): ?>
      <option value="<?= esc($e) ?>" <?= $avaluo['Estado'] === $e ? 'selected' : '' ?>><?= esc($e) ?></option>
      <?php endforeach; ?>
    </select>
  </p>
  <p>
    <label class="m13">Nombre del archivo:</label>
    <input name="nombreArchivo" type="text" onkeyup="this.value=this.value.toUpperCase()" value="<?= esc(old('nombreArchivo', $avaluo['NombreArchivo'])) ?>" maxlength="30" />
  </p>
  <p><span class="m35">Valor del Avaluo:</span>
    <input name="valorAvaluo" type="text" onblur="esc2(this)" value="<?= esc(old('valorAvaluo', $avaluo['ValorAvaluo'])) ?>" maxlength="12" />
    <label class="m182">Honorarios:</label>
    <input name="honorarios" type="text" onblur="esc2(this)" value="<?= esc(old('honorarios', $avaluo['Honorarios'])) ?>" maxlength="10"/>
    <select class="m55" name="tipo">
      <option value="0" <?= (int) $avaluo['Tipo'] === 0 ? 'selected' : '' ?>>Real</option>
      <option value="1" <?= (int) $avaluo['Tipo'] === 1 ? 'selected' : '' ?>>Aproximado</option>
    </select>
  </p>
  <p><label class="m72">Valor Real:</label>
    <input name="valorReal" type="text" onblur="esc2(this)" value="<?= esc(old('valorReal', $avaluo['ValorReal'])) ?>" maxlength="12" />
    <label class="m202">Imagen:</label>
    <input type="file" name="archivo" size="30" />
  </p>
  <p class="m560"><img src="<?= site_url('avaluos/' . $avaluo['Folio'] . '/imagen') ?>" width="120"></p>
  <p><span class="m24">Trabajo a Realizar:</span>
    <select name="trabajo">
      <?php foreach ($trabajos as $t): ?>
      <option value="<?= $t['IdTrabajo'] ?>" <?= (int) $avaluo['IdTrabajo'] === (int) $t['IdTrabajo'] ? 'selected' : '' ?>><?= esc($t['Nombre']) ?></option>
      <?php endforeach; ?>
    </select>
  </p>

  <fieldset id="Documentos" style="border:0">
  <?php if ($pendientes !== []): ?>
  <p>Documentos pendientes:</p>
  <div id="scroll2"><table class="tab" align="left">
  <tr class="tituloTabla"><td class="tituloTabla" width="300"><b>DOCUMENTOS</b></td></tr>
  <?php foreach ($pendientes as $i => $doc): ?>
  <tr <?= $i % 2 === 0 ? 'bgcolor="#DAE6F3"' : '' ?>>
    <td><input class="doc-pendiente" name="documentos[<?= $doc['IdDocumento'] ?>]" type="checkbox" value="1"
        data-obligatorio="<?= (int) $doc['Tipo'] ?>" data-nombre="<?= esc($doc['Nombre'], 'attr') ?>"><?= esc($doc['Nombre']) ?><?= (int) $doc['Tipo'] === 1 ? ' (obligatorio)' : '' ?></td>
  </tr>
  <?php endforeach; ?>
  </table></div><br>
  <?php endif; ?>
  <?php
    $recibidosOk = array_filter($recibidos, static fn ($d) => (int) $d['StatusR'] === 1);
  ?>
  <?php if ($recibidosOk !== []): ?>
  <p>Documentos ya recibidos:</p>
  <div id="scroll2"><table class="tab" align="left">
  <?php foreach ($recibidosOk as $i => $doc): ?>
  <tr <?= $i % 2 === 0 ? 'bgcolor="#DAE6F3"' : '' ?>><td>&nbsp;<?= esc($doc['Nombre']) ?></td></tr>
  <?php endforeach; ?>
  </table></div><br>
  <?php endif; ?>
  </fieldset>

  <p>
    <label class="m65">Comentario:</label></p><p>
    <textarea name="comentario" class="m143" cols="81" rows="5" onkeyup="this.value=this.value.toUpperCase()"><?= esc(old('comentario', $avaluo['Comentario'])) ?></textarea>
  </p>
  <p>
    <label for="status">Status:</label>
    <select name="status" id="status">
      <?php foreach ($opcionesStatus as $valor => $etiqueta): ?>
      <option value="<?= $valor ?>"><?= esc($etiqueta) ?></option>
      <?php endforeach; ?>
    </select>
  </p>

  <p align="center">
    <input type="button" value="Guardar" onclick="validar()" />
    <input type="submit" name="cancelar" value="Cancelar" />
  </p>
</form>
<?= $this->endSection() ?>
