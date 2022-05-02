<?php
$this->layout('Layouts/layout', [
    'title' => $title
]);
$encodedBill = base64_encode($bill->toJson());
?>

<form action="save-bill" method="post" >
    <input type="hidden" name="bill" id="bill" value="<?= $encodedBill ?>">
    <div>
        <label >Access key: </label>
        <label> <?= $bill->getAccessKey() ?> </label>
    </div>

    <div>
        <label for="">Bill number:</label>
        <label> <?= "{$bill->getEstablishment()}-{$bill->getEmissionPoint()}-{$bill->getSecuential()}" ?> </label>
    </div>

    <div>
        <label for="">Date:</label>
        <label> <?= $bill->getDateOfIssue() ?> </label>
    </div>

    <div>
        <label >Total without taxes:</label>
        <label> <?= $bill->getTotalWithoutTax() ?> </label>
    </div>
    <div>
        <label >Total discount:</label>
        <label> <?= $bill->getTotalDiscount() ?> </label>
    </div>
    <div>
        <label >Tip:</label>
        <label> <?= $bill->getTip() ?> </label>
    </div>
    <div>
        <label >Total:</label>
        <label> <?= $bill->getTotal() ?> </label>
    </div>

    <div>
        <h2>Store:</h2>
        <div>
            <label >Business name:</label>
            <label> <?= $bill->getStore()->getBusinessName() ?> </label>
        </div>
        <div>
            <label >Trade name:</label>
            <label> <?= $bill->getStore()->getTradeName() ?> </label>
        </div>
        <div>
            <label >RUC:</label>
            <label> <?= $bill->getStore()->getRuc() ?> </label>
        </div>
        <div>
            <label >Parent Address:</label>
            <label> <?= $bill->getStore()->getParentAddress() ?> </label>
        </div>

    </div>

    <div>
        <h2>Buyer</h2>
        <div>
            <label >Name:</label>
            <label> <?= $bill->getBuyer()->getName() ?> </label>
        </div>
        <div>
            <label >Identification:</label>
            <label> <?= $bill->getBuyer()->getIdentification() ?> </label>
        </div>
    </div>

    <div>
        <h2>Deductibles</h2>

        <?php foreach ($deductibles as $deductible): ?>
            <?php $inputDeductibleName = "deductible-{$deductible->getId()}" ?>
            <div>
                <label for="<?= $inputDeductibleName ?>">
                    <?= $deductible->getName() ?>
                </label>
                <input type="text" name="bill-deductibles[<?= $deductible->getId() ?>]" 
                       id ="<?= $inputDeductibleName ?>" />
            </div>
        <?php endforeach; ?>
    </div>

    <h2>Details:</h2>
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Discount</th>
                <th>Total</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bill->getBillDetails() as $billDetail): ?>
                <?php $inputDetailName = "bill-detail-{$billDetail->getMainCode()}" ?>
                <tr>
                    <td><?= $billDetail->getMainCode() ?></td>
                    <td><?= $billDetail->getDescription() ?></td>
                    <td><?= $billDetail->getQuantity() ?></td>
                    <td><?= $billDetail->getUnitPrice() ?></td>
                    <td><?= $billDetail->getDiscount() ?></td>
                    <td><?= $billDetail->getTotalPriceWithoutTaxes() ?></td>
                    <td>
                        <select name="billDetailDeductible[<?= $billDetail->getMainCode() ?>]">
                            <option value="0" selected>Select deductible...</option>
                        </select>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>



    <input type="submit" value="Save">
</form>
