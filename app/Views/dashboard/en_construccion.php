<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo">EN CONSTRUCCIÓN</p>
<div style="max-width:600px;margin:0 auto;text-align:center;">
    <p>El módulo "<strong><?= esc($modulo) ?></strong>" todavía no se ha migrado a CodeIgniter.</p>
    <p><a href="<?= site_url('/') ?>">Volver al inicio</a></p>
</div>
<?= $this->endSection() ?>
