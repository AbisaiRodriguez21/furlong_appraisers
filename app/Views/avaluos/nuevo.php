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

function cargaContenido(idSelectOrigen) {
    var divResultado = document.getElementById('Documentos');
    var select = document.getElementById(idSelectOrigen);
    var idTrabajo = select.options[select.selectedIndex].value;
    if (idTrabajo == 0) {
        divResultado.innerHTML = '';
        return;
    }
    var ajax = new XMLHttpRequest();
    ajax.open('GET', '<?= site_url('avaluos/documentos-de-trabajo') ?>/' + idTrabajo);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4) {
            divResultado.innerHTML = ajax.responseText;
        }
    };
    ajax.send(null);
}

function validar() {
    if (document.form1.cliente.value == 0) { alert('Seleccione Cliente'); return; }
    if (document.form1.solicitante.value == 0) { alert('Seleccione Solicitante'); return; }
    if (document.form1.propietario.value == 0) { alert('Seleccione Propietario'); return; }
    if (document.form1.direccion.value.length == 0) { alert('Ingrese Direccion'); document.form1.direccion.focus(); return; }
    if (document.form1.colonia.value.length == 0) { alert('Ingrese Colonia'); document.form1.colonia.focus(); return; }
    if (document.form1.ciudad.value.length == 0) { alert('Ingrese Ciudad'); document.form1.ciudad.focus(); return; }
    if (document.getElementById('estado').value == '0') { alert('Seleccione Estado'); return; }
    if (document.getElementById('tipo').value == '-1') { alert('Seleccione Tipo'); return; }
    if (document.getElementById('trabajo').value == '0') { alert('Seleccione Trabajo'); return; }
    if (!/^([0-9]){0,9}[.]?[0-9]{0,2}$/.test(document.form1.valorAvaluo.value)) { alert('Valor muy grande para Valor de Avaluo'); return; }
    if (!/^([0-9]){0,9}[.]?[0-9]{0,2}$/.test(document.form1.valorReal.value)) { alert('Valor muy grande para Valor Real'); return; }
    if (!/^([0-9]){0,7}[.]?[0-9]{0,2}$/.test(document.form1.honorarios.value)) { alert('Valor muy grande para Honorarios'); return; }
    document.form1.submit();
}
</script>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
<p class="titulo">AGREGAR AVALÚO</p>

<?php if (session('error')): ?>
<script>alert('<?= esc(session('error'), 'js') ?>')</script>
<?php endif; ?>

<form enctype="multipart/form-data" id="form1" name="form1" method="post" action="<?= site_url('avaluos/guardar') ?>">
  <p>
    <label class="m24">Fecha de Solicitud:</label>
    <input name="fechaSolicitud" type="date" value="<?= esc(old('fechaSolicitud', date('Y-m-d'))) ?>" />
  </p>
  <p>
    <label>Nombre del Inspector:</label>
    <input name="nombreInspector" type="text" size="40" maxlength="60" onkeyup="this.value=this.value.toUpperCase()" value="<?= esc(old('nombreInspector')) ?>" />
    <label>Fecha de Inspección:</label>
    <input name="fechaInspeccion" type="date" value="<?= esc(old('fechaInspeccion', date('Y-m-d'))) ?>" />
    <label>Fecha de Vencimiento:</label>
    <input name="fechaVencimiento" type="date" value="<?= esc(old('fechaVencimiento', date('Y-m-d'))) ?>" />
  </p>
  <p>
    <label class="m92">Cliente:</label>
    <input name="NombreCliente" id="NombreCliente" type="text" size="50" readonly="readonly" />
    <input type="button" value="..." onclick="abrir(1)" />
    <input type="hidden" name="cliente" id="cliente" value="0" />
  </p>
  <p class="m71">
    <label>Solicitante:</label>
    <input name="NombreSol" id="NombreSol" type="text" size="50" readonly="readonly" />
    <input type="button" value="..." onclick="abrir(2)" />
    <input type="hidden" name="solicitante" id="Solicitante" value="0" />
  </p>
  <p class="m67">
    <label>Propietario:
      <input name="NombreProp" id="NombreProp" type="text" size="50" readonly="readonly" />
    </label>
    <input type="button" value="..." onclick="abrir(3)" />
    <input type="hidden" name="propietario" id="Propietario" value="0" />
  </p>
  <p style="font-weight:bolder" align="center">Inmueble a valuar</p>
  <p>
    <label class="m76">Direccion:</label>
    <input name="direccion" type="text" size="50" maxlength="50" onkeyup="this.value=this.value.toUpperCase()" value="<?= esc(old('direccion')) ?>" />
    <label class="m83">Colonia:</label>
    <input name="colonia" type="text" size="45" maxlength="35" onkeyup="this.value=this.value.toUpperCase()" value="<?= esc(old('colonia')) ?>" />
  </p>
  <p>
    <label class="m90">Ciudad:</label>
    <input name="ciudad" type="text" size="50" maxlength="35" onkeyup="this.value=this.value.toUpperCase()" value="<?= esc(old('ciudad')) ?>" />
    <label class="m85">Estado:</label>
    <select name="estado" id="estado">
      <option value="0">Seleccione un Estado</option>
      <?php foreach ($estados as $e): ?>
      <option value="<?= esc($e) ?>" <?= old('estado') === $e ? 'selected' : '' ?>><?= esc($e) ?></option>
      <?php endforeach; ?>
    </select>
  </p>
  <p>
    <label class="m13">Nombre del archivo:</label>
    <input name="nombreArchivo" type="text" maxlength="30" onkeyup="this.value=this.value.toUpperCase()" value="<?= esc(old('nombreArchivo')) ?>" />
  </p>
  <p>
    <label class="m35">Valor del Avaluo:</label>
    <input name="valorAvaluo" type="text" value="0.00" maxlength="12" onblur="esc2(this)" />
    <label class="m182">Honorarios:</label>
    <input name="honorarios" type="text" value="0.00" maxlength="10" onblur="esc2(this)" />
    <select class="m55" name="tipo" id="tipo">
      <option value="-1">Seleccione Tipo</option>
      <option value="0">Real</option>
      <option value="1">Aproximado</option>
    </select>
  </p>
  <p>
    <label class="m72">Valor Real:</label>
    <input name="valorReal" type="text" value="0.00" maxlength="12" onblur="esc2(this)" />
    <label class="m202">Imagen: </label>
    <input type="file" name="archivo" size="30" />
  </p>
  <p>
    <label class="m24">Trabajo a Realizar:</label>
    <select name="trabajo" id="trabajo" onchange="cargaContenido(this.id)">
      <option value="0">Seleccione Trabajo</option>
      <?php foreach ($trabajos as $t): ?>
      <option value="<?= $t['IdTrabajo'] ?>"><?= esc($t['Nombre']) ?></option>
      <?php endforeach; ?>
    </select>
  </p>
  <p>
    <label class="m12">Número de Avaluos:</label>
    <input name="numeroAvaluos" type="text" value="1" maxlength="3" />
  </p>
  <fieldset id="Documentos" style="border:0"></fieldset>
  <p>
    <label class="m65">Comentario:</label></p><p>
    <textarea class="m143" name="comentario" cols="81" rows="5" onkeyup="this.value=this.value.toUpperCase()"><?= esc(old('comentario')) ?></textarea>
  </p>
  <p align="center">
    <input type="button" value="Guardar" onclick="validar()" />
    <input type="submit" name="cancelar" value="Cancelar" />
  </p>
</form>
<?= $this->endSection() ?>
