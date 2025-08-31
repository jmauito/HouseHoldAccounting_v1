<?php
$this->layout('Layouts/layout', [
    'title' => $title,
]);
$encodedBill = base64_encode($bill->toJson());
$action = '/register-bill';
?>

<form action="<?= $action  ?>" method="post" >
    <input type="hidden" name="update" value="<?= $update ?>">
    <input type="hidden" name="bill" id="bill" value="<?= $encodedBill ?>">
    <div>
        <label >Access key: </label>
        <label> <?= $bill->getAccessKey() ?> </label>
    </div>

    <div>
        <label for="">Bill number:</label>
        <label> <?= "{$bill->getEstablishment()}-{$bill->getEmissionPoint()}-{$bill->getSequential()}" ?> </label>
    </div>

    <div>
        <label for="">Date:</label>
        <label> <?= $bill->getDateOfIssue()->format('Y-m-d') ?> </label>
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
        <h2> Taxes </h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <td>Code</td>
                    <td>Base</td>
                    <td>Value</td>
                </tr>
            </thead>
            <tbody>
            <?php foreach($bill->getBillTaxRates() as $billTaxRate): ?>
            <tr>
                <td><?=  $billTaxRate->getTaxRateId() ?> </td>
                <td><?=  $billTaxRate->getTaxBase() ?> </td>
                <td><?=  $billTaxRate->getValue() ?> </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div>
        <label >Total:</label>
        <label> <?= $bill->getTotal() ?> </label>
    </div>
    <div>
        <label >License plate:</label>
        <label> <?= $bill->getLicensePlate() ?> </label>
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
            <?php $inputExpenseName = "expense-{$expense->getId()}" ?>
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
    <table class="table table-bordered border-primary table-hover">
        <thead>
            <tr>
                <th>Code</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Discount</th>
                <th>Total</th>
                <th>Deductibles</th>
                <th>Expenses</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bill->getBillDetails() as $billDetail): ?>
                <?php $inputDetailName = "bill-detail-{$billDetail->getMainCode()}" ?>
                <tr>
                    <td>
                        <input type="hidden" id="billDetailId<?= $billDetail->getMainCode() ?>" name="billDetailId<?= $billDetail->getMainCode() ?>" value="<?= $billDetail->getId() ?>" />
                        <?= $billDetail->getMainCode() ?>
                    </td>
                    <td><?= $billDetail->getDescription() ?></td>
                    <td><?= $billDetail->getQuantity() ?></td>
                    <td><?= $billDetail->getUnitPrice() ?></td>
                    <td><?= $billDetail->getDiscount() ?></td>
                    <td><?= $billDetail->getTotalPriceWithoutTaxes() ?></td>
                    <td>
                        <select name="billDetailDeductible[<?= $billDetail->getMainCode() ?>]"
                            id="billDetailDeductible-<?= $billDetail->getMainCode() ?>"
                            onchange="changeBillDetailDeductible('<?= $billDetail->getMainCode() ?>', '<?= $billDetail->getTotalPriceWithoutTaxes() ?>')"
                        >
                            <?php 
                            $notDeductibleSelected = "";
                            if($billDetail->getBillDetailDeductible() === null){
                                $notDeductibleSelected = 'selected';
                            } 
                            ?>
                            <option value="0" <?= $notDeductibleSelected ?>>Select deductible...</option>
                            
                            <?php foreach ($deductibles as $deductible): ?>
                                <?php 
                                    $deductibleSelected = "";
                                    if($billDetail->getBillDetailDeductible() !== NULL && $deductible->getId() === $billDetail->getBillDetailDeductible()->getDeductibleId()){
                                        $deductibleSelected = 'selected';
                                    }
                                ?>
                                <option <?= $deductibleSelected ?> value="<?= $deductible->getId()  ?>" ><?= $deductible->getName() ?></option>
                            <?php endforeach;?>
                        </select>
                        <input type="hidden" id="deductibleId<?= $billDetail->getMainCode() ?>" name="deductibleId<?= $billDetail->getMainCode() ?>" 
                            value="<?= $billDetail->getBillDetailDeductible() === null ? 0 : $billDetail->getBillDetailDeductible()->getDeductibleId() ?>" />
                    </td>
                    <td>
                        <select name="billDetailExpense[<?= $billDetail->getMainCode() ?>]"
                                id="billDetailExpense-<?= $billDetail->getMainCode() ?>"
                                onchange="changeBillDetailExpense('<?= $billDetail->getMainCode() ?>', '<?= $billDetail->getTotalPriceWithoutTaxes() ?>')"
                        >
                            <?php 
                            $notExpenseSelected = "";
                            if($billDetail->getBillDetailExpense() === null){
                                $notExpenseSelected = 'selected';
                            }
                            ?>
                            <option <?= $notExpenseSelected ?> value="0" >Select expense...</option>
                            <?php foreach ($expenses as $expense): ?>
                                <?php
                                $expenseSelected = "";
                                if($billDetail->getBillDetailExpense() !== NULL && $expense->getId() === $billDetail->getBillDetailExpense()->getExpenseId()){
                                    $expenseSelected = 'selected';
                                }
                                ?>
                            <option <?= $expenseSelected ?> value="<?= $expense->getId()  ?>"><?= $expense->getName() ?></option>
                            <?php endforeach;?>
                        </select>
                        <input type="hidden" id="expenseId<?= $billDetail->getMainCode() ?>" name="expenseId<?= $billDetail->getMainCode() ?>" 
                            value="<?= $billDetail->getBillDetailExpense() === null ? 0 : $billDetail->getBillDetailExpense()->getExpenseId() ?>" />
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <input type="submit" value="Save">
</form>

<script>

function changeBillDetailDeductible(mainCode, totalPriceWithoutTaxes){
    const selectedDeductible = document.getElementById('billDetailDeductible-' + mainCode)
    const previouslyDeductible = document.getElementById('deductibleId' + mainCode)
    updateDetail('deductible', selectedDeductible, previouslyDeductible, totalPriceWithoutTaxes)
}

function changeBillDetailExpense(mainCode, totalPriceWithoutTaxes){
    const selectedExpense = document.getElementById('billDetailExpense-' + mainCode)
    const oldExpense = document.getElementById('expenseId' + mainCode)
    updateDetail('expense', selectedExpense, oldExpense, totalPriceWithoutTaxes)
}

function updateDetail(objectName, selectedObject, previouslyObject, value){
    if (selectedObject.value == 0 ) {
        const deductible = document.getElementById(objectName + '-' + previouslyObject.value)
        deductible.value = ( Math.round( Number(deductible.value) * 100 ) - Math.round( Number(value) * 100 ) ) /100
    } else {
        const deductible = document.getElementById(objectName + '-' + selectedObject.value)
        deductible.value = (Math.round( Number(deductible.value) * 100 ) + Math.round( Number(value) * 100 ) ) /100

        if(previouslyObject.value != 0 && previouslyObject.value != selectedObject.value){
            const deductible = document.getElementById(objectName + '-' + previouslyObject.value)
            deductible.value = ( Math.round( Number(deductible.value) * 100 ) - Math.round( Number(value) * 100 ) ) /100
        }

    }
    previouslyObject.value = selectedObject.value
}


</script>