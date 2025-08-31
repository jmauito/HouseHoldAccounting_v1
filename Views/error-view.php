<?php
$this->layout('Layouts/layout', [
    'title' => $title
]);
?>

<h2>Se produjeron los siguientes errores:</h2>
<?php foreach ($errorMessages as $message): ?>
<p><?= $message ?></p>
<?php endforeach; ?>
<div>
    <a href="/load-from-xml">
        <button>Intentar nuevamente</button>
    </a>
</div>
<div>
    <a href="/">
        <button>Menú principal</button>
    </a>
</div>