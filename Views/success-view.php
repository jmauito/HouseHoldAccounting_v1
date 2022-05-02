<?php
$this->layout('Layouts/layout', [
    'title' => $title
]);
?>

<h2><?= $message ?></h2>
<div>
    <a href="/load-from-xml">
        <button>Registrar una nueva factura</button>
    </a>
</div>
<div>
    <a href="/">
        <button>Menú principal</button>
    </a>
</div>