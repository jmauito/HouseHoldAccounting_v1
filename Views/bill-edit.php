<?php
$this->layout('Layouts/layout', [
    'title' => $title,
]);
$encodedBill = base64_encode($bill->toJson());
?>

<form action="save-bill" method="post" >
    <input type="hidden" name="update" value="<?= $update ?>">
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
        <h2> Additional Information</h2>
        <?php foreach ($bill->getBillAdditionalInformation() as $additionalInformation ): ?>
        <div>
            <label><?= $additionalInformation->getName() ?> : </label>
            <label><?= $additionalInformation->getValue() ?> </label>
        </div>
        <?php endforeach; ?>
    </div>

    <div>
        <h2>Deductibles</h2>
        
        <?php foreach ($deductibles as $deductible): ?>
            <?php $inputDeductibleName = "deductible-{$deductible->getId()}" ?>
            <?php
                $billDeductibleValue = null;
                $deductibleId = $deductible->getId();
                $billDeductibleExists = current( array_filter($bill->getBillDeductibles(), function($arr) use ($deductibleId){
                    return $arr->getDeductible()->getId() === $deductibleId;
                }) );
                if ($billDeductibleExists !== false){
                    $billDeductibleValue = $billDeductibleExists->getValue();
                }
            ?>
            <div>
                <label for="<?= $inputDeductibleName ?>">
                    <?= $deductible->getName() ?>
                </label>
                <input type="text" name="bill-deductibles[<?= $deductible->getId() ?>]" 
                       id ="<?= $inputDeductibleName ?>" value="<?= $billDeductibleValue ?>"/>
            </div>
        <?php endforeach; ?>
    </div>

    <div>
        <h2>Expenses</h2>

        <?php foreach ($expenses as $expense): ?>
            <?php $inputExpenseName = "deductible-{$expense->getId()}" ?>
            <?php
            $billExpenseValue = null;
            $expenseId = $expense->getId();
            $billExpenseExists = current( array_filter($bill->getBillExpenses(), function($arr) use ($expenseId){
                return $arr->getExpense()->getId() === $expenseId;
            }) );
            if ($billExpenseExists !== false){
                $billExpenseValue = $billExpenseExists->getValue();
            }
            ?>
            <div>
                <label for="<?= $inputExpenseName ?>">
                    <?= $expense->getName() ?>
                </label>
                <input type="text" name="bill-expenses[<?= $expense->getId() ?>]"
                       id ="<?= $inputExpenseName ?>" value="<?= $billExpenseValue ?>"/>
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
                        <select 
                            name="billDetailDeductible[<?= $billDetail->getMainCode() ?>]" 
                            id="<?= $billDetail->getMainCode() ?>" 
                            onchange="changeBillDetailDeductible('<?= $billDetail->getMainCode() ?>', '<?= $billDetail->getTotalPriceWithoutTaxes() ?>', document.getElementById('<?= $billDetail->getMainCode() ?>').value)"
                        >
                            <option value="0" selected>Select deductible...</option>
                            <?php foreach ($deductibles as $deductible): ?>
                            <option value="<?= $deductible->getId()  ?>"><?= $deductible->getName() ?></option>
                            <?php endforeach;?>
                        </select>
                        <input type="hidden" id="deductibleId<?= $billDetail->getMainCode() ?>" name="deductibleId<?= $billDetail->getMainCode() ?>" value="0" />
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <input type="submit" value="Save">
</form>

<script>

function changeBillDetailDeductible(mainCode, totalPriceWithoutTaxes){
    const selectedDeductible = document.getElementById(mainCode)
    const oldDeductible = document.getElementById('deductibleId' + mainCode)

    if (selectedDeductible.value == 0 ) {
        const deductible = document.getElementById('deductible-' + oldDeductible.value)
        deductible.value = deductible.value * 1 - totalPriceWithoutTaxes * 1
    } else {
        const deductible = document.getElementById('deductible-' + selectedDeductible.value)
        deductible.value = deductible.value * 1 + totalPriceWithoutTaxes * 1

        if(oldDeductible.value != 0 && oldDeductible.value != selectedDeductible.value){
            const deductible = document.getElementById('deductible-' + oldDeductible.value)
            deductible.value = deductible.value * 1 - totalPriceWithoutTaxes * 1
        }

    }



    oldDeductible.value = selectedDeductible.value
}
</script>