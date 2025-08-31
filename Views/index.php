<?php $this->layout('Layouts/layout', [
    'title' => $title,
    'lastBillsRegistered' => $lastBillsRegistered,
    'totalByDeductible' => $totalByDeductible,
    'year' => $year,
    'years' => $years
]) ?>

<h1>Dashboard</h1>

<h2>Total by Deductible</h2>
<h3>Year: <?= $year ?></h3>
<label for="year">Change year:</label>
<select name="year" id="year" onchange="location = this.value;">
    <?php foreach($years as $y):?>
        <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
    <?php endforeach; ?>
</select>

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

<table class="table table-bordered border-primary table-hover">
    <thead>
        <tr>
            <td>Date of issue</td>
            <td>Store</td>
            <td>Number</td>
            <td>Total without taxes</td>
            <td>Total taxes</td>
            <?php foreach ($totalByDeductible as $deductible) : ?>
                <td class = "text-center"> <?= $deductible['deductibleName'] ?> </td>
            <?php endforeach; ?>
            <td>Action</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lastBillsRegistered as $bill) : ?>
            <tr>
                <td><?= $bill->getDateOfIssue()->format('d-M-Y') ?></td>
                <td><?= $bill->getStore()->getTradeName() ?></td>
                <td><?= $bill->getNumber() ?></td>
                <td><?= $bill->getTotalWithoutTax() ?></td>
                <td class = "center"><?= $bill->getTotal() ?></td>
                <?php foreach ($totalByDeductible as $deductible) : ?>
                    <td>
                        <?php
                            $deductibleFind = current( array_filter($bill->getBillDeductibles(), function ($billDeductible) use ($deductible) {
                                if ($billDeductible->getDeductibleId() == $deductible['deductibleId']) return $billDeductible;
                            }) );
                            if ( FALSE !== $deductibleFind ) {
                                echo $deductibleFind->getValue();
                            }
                        ?>
                    </td>
                <?php endforeach; ?>
                <td>
                    <a href="bills/<?= $bill->getId() ?>">Edit</a>
                    <a href="bill-delete/<?= $bill->getId() ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>