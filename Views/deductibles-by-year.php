<?php
$this->layout('Layouts/layout', [
    'title' => $title,
]);
?>

<h1> Año de reporte: <?= $year ?> </h1>
<table class="table table-striped table-sm">
    <thead>
    <tr>
        <th>Deducible</th>
        <th>Total acumulado</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($deductibleValueList as $deductibleValue): ?>
    <tr>
        <td><?= $deductibleValue['deductibleName'] ?></td>
        <td><?= $deductibleValue['deductibleTotal'] ?></td>
    </tr>
    <?php endforeach ?>
    </tbody>
</table>
