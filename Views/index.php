<?php $this->layout('Layouts/layout', [
    'title' => $title,
    'lastBillsRegistered' => $lastBillsRegistered
]) ?>


<h1>Dashboard</h1>
<h2>Last bills registered</h2>

<table class="table">
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
        <?php foreach($lastBillsRegistered as $bill): ?>
            <tr>
                <td><?= $bill->getDateOfIssue()->format('d-M-Y') ?></td>
                <td><?= $bill->getNumber() ?></td>
                <td><?= $bill->getTotalWithoutTax() ?></td>
                <td><?= $bill->getTotal() ?></td>
                <td> <a href="bills/<?= $bill->getId()?>"  >Edit</a>  </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>