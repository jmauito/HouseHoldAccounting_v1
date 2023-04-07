<?php $this->layout('Layouts/layout', [
    'title' => $title,
    'lastBillsRegistered' => $lastBillsRegistered,
    'totalByDeductible' => $totalByDeductible,
    'year' => $year
]) ?>

<?php
//  echo '<pre>';
//  print_r($totalByDeductible);exit;
?>

<h1>Dashboard</h1>

<h2>Total by Deductible</h2>
<h3>Year: <?= $year ?></h3>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Deductible</th>
            <th>Total</th>
            <th>Bills</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($totalByDeductible as $deductible) : ?>
            <tr>
                <td><?= $deductible['deductibleName'] ?></td>
                <td><?= $deductible['deductibleTotal'] ?></td>
                <td>
                    <a href="bills-by-deductible-and-year/<?= $deductible['deductibleId'] ?>/<?= $year ?>">View bills</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Last bills registered</h2>

<table class="table table-striped">
    <thead>
        <tr>
            <td>Date of issue</td>
            <td>Number</td>
            <td>Total without taxes</td>
            <td>Total taxes</td>
            <td>Action</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lastBillsRegistered as $bill) : ?>
            <tr>
                <td><?= $bill->getDateOfIssue()->format('d-M-Y') ?></td>
                <td><?= $bill->getNumber() ?></td>
                <td><?= $bill->getTotalWithoutTax() ?></td>
                <td><?= $bill->getTotal() ?></td>
                <td>
                    <a href="bills/<?= $bill->getId() ?>">Edit</a>
                    <a href="bill-delete/<?= $bill->getId() ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>