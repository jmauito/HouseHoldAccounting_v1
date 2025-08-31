<?php $this->layout('Layouts/layout', [
    'title' => $title,
    'deductibleName' => $deductibleName,
    'bills' => $bills,
    'year' => $year
]) ?>

<h2>Deductible: <?= $deductibleName ?></h2>
<h3>Year: <?= $year ?></h3>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Access key</th>
            <th>Sequential</th>
            <th>Date</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bills as $bill) : ?>
            <tr>
                <td><?= $bill->getAccessKey() ?></td>
                <td><?= $bill->getSequential() ?></td>
                <td><?= $bill->getDateOfIssue()->format('d-M-Y') ?></td>
                <td><?= $bill->getBillDeductibles()[0]->getValue() ?></td>
                <td>
                    <a href="/bills/<?= $bill->getId() ?>">View bills</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
