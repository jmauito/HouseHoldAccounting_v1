<?php
$this->layout('Layouts/layout', [
    'title' => $title,
]);
$encodedBill = base64_encode($bill->toJson());
$action = "/delete-bill";
?>

<form action="<?= $action ?>" method="post" >
    <input type="hidden" name="billId" id="billId" value="<?= $bill->getId() ?>">
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
                <p> <?= $deductible->getName() ?>: <?= $billDeductibleValue ?></p>
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
                <p><?= $expense->getName() ?>: <?= $billExpenseValue ?></p>
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
                        <?php 
                        if($billDetail->getBillDetailDeductible() !== NULL){
                            $deductibleId = $billDetail->getBillDetailDeductible()->getDeductibleId();
                            $deductible = current( array_filter( $deductibles, function($d) use ($deductibleId){
                                return $d->getId() == $deductibleId;
                            }) );
                            print($deductible->getName());
                        }
                        ?>
                        
                    </td>
                    <td>
                        <?php 
                        if($billDetail->getBillDetailExpense() !== NULL){
                            $expenseId = $billDetail->getBillDetailExpense()->getExpenseId();
                            $expense = current( array_filter( $expenses, function($e) use ($expenseId){
                                return $e->getId() == $expenseId;
                            }) );
                            print($expense->getName());
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    
  <input type="submit" value="Delete" />  
</form>
