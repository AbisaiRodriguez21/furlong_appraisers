<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<script type="text/javascript">
function abrir(tipo) {
    var left = (screen.width - 1024) / 2;
    var top = (screen.height - 650) / 2;
    window.open('<?= site_url('clientes') ?>?t=' + tipo + '&ventana=2', '', 'width=1024, height=650, left=' + left + ',top=' + top);
}
function CopiarDatos(Numero, Nombre) {
    document.getElementById('Cliente').value = Nombre;
    document.getElementById('IdCliente').value = Numero;
    var ajax = new XMLHttpRequest();
    ajax.open('GET', '<?= site_url('cobranza/reportes/cliente/datos') ?>?id=' + Numero + '&tipo=<?= $tipo ?>');
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4) {
            document.getElementById('Documentos').innerHTML = ajax.responseText;
        }
    };
    ajax.send(null);
}
function imprimir() {
    if (document.getElementById('IdCliente').value.length == 0) {
        alert('Seleccione un cliente');
        return;
    }
    document.form1.action = '<?= site_url('cobranza/reportes/cliente/pdf') ?>';
    document.form1.target = '_blank';
    document.form1.submit();
}
</script>
<?= $this->endSection() ?>

<?= $this->section('contenido') ?>
<p class="titulo">Reporte de Cobranza <?= $tipo === 1 ? 'Pendiente ' : '' ?>por Cliente</p>
<form id="form1" name="form1" method="post" action="">
  <input name="Cliente" id="Cliente" type="text" size="70" readonly="readonly" />
  <input name="IdCliente" id="IdCliente" type="hidden"/>
  <input name="Tipo" type="hidden" value="<?= $tipo ?>" />
  <input type="button" value="..." onclick="abrir(4)" />
  <fieldset id="Documentos" style="border:0"></fieldset>
  <p align="center">
    <input type="button" value="Imprimir" onclick="imprimir()" />
    <input type="button" value="Regresar" onclick="location.href='<?= site_url('cobranza/reportes') ?>'" />
  </p>
</form>
<?= $this->endSection() ?>
