<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<script type="text/javascript">
function recargar() {
    var fecha = document.getElementById('Fecha').value;
    var ajax = new XMLHttpRequest();
    ajax.open('GET', '<?= site_url('cobranza/reportes/dia/datos') ?>?fecha=' + fecha);
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4) {
            document.getElementById('Documentos').innerHTML = ajax.responseText;
        }
    };
    ajax.send(null);
}
function imprimir() {
    document.form1.action = '<?= site_url('cobranza/reportes/dia/pdf') ?>';
    document.form1.target = '_blank';
    document.form1.submit();
}
</script>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
<p class="titulo">Reporte de Cobranza</p>
<form id="form1" name="form1" method="post" action="">
  <label class="m135">Fecha:</label>
  <input name="fecha" type="date" id="Fecha" value="<?= esc($fecha) ?>" onchange="recargar()" />
  <fieldset id="Documentos" style="border:0">
    <?= view('reportes/cobranza_dia_tabla', ['filas' => []]) ?>
  </fieldset>
  <p align="center">
    <input type="button" value="Imprimir" onclick="imprimir()" />
    <input type="button" value="Regresar" onclick="location.href='<?= site_url('cobranza/reportes') ?>'" />
  </p>
</form>
<script type="text/javascript">recargar();</script>
<?= $this->endSection() ?>
