<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo">FURLONG APPRAISERS</p>
<div style="max-width:700px;margin:0 auto;text-align:center;">
    <p>Sesión iniciada como <strong><?= esc(session('login')) ?></strong>
        (<?= esc(session('nombre')) ?>).</p>
    <p>Usa el menú de arriba para acceder a los módulos del sistema.</p>
</div>
<?= $this->endSection() ?>
