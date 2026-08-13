<?= $this->extend('layouts/main') ?>

<?= $this->section('contenido') ?>
<p class="titulo">FURLONG APPRAISERS</p>
<div style="max-width:700px;margin:0 auto;text-align:center;">
    <p>Sesión iniciada como <strong><?= esc(session('login')) ?></strong>
        (<?= esc(session('nombre')) ?>).</p>
    <p>Este panel reemplaza a <code>Avaluos.php</code> como pantalla de entrada mientras se
        migran los módulos uno por uno. Progreso actual:</p>
    <table align="center" cellpadding="6" style="border-collapse:collapse;">
        <tr><td>Núcleo (login, roles, layout)</td><td><strong>Listo</strong></td></tr>
        <tr><td>Usuarios</td><td>Pendiente</td></tr>
        <tr><td>Clientes</td><td>Pendiente</td></tr>
        <tr><td>Trabajos a Realizar</td><td>Pendiente</td></tr>
        <tr><td>Avalúos</td><td>Pendiente</td></tr>
        <tr><td>Cobranza / Recibos</td><td>Pendiente</td></tr>
        <tr><td>Documentación</td><td>Pendiente</td></tr>
    </table>
</div>
<?= $this->endSection() ?>
