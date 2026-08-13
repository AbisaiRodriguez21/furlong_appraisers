<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<?= view('recibos/_documento', ['recibo' => $recibo, 'letraTexto' => $letra]) ?>
<table width="804" border="0" cellspacing="4" cellpadding="0" align="center">
<tr><td align="center">ORIGINAL</td></tr>
</table>
<p align="center"><a href="<?= site_url('recibos') ?>">Volver al listado</a></p>
<script type="text/javascript">window.print();</script>
<?= $this->endSection() ?>
