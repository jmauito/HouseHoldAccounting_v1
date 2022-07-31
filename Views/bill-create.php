<?php
$this->layout('Layouts/layout', [
    'title' => $title
]);
?>

<form action="insert-bill" method="post">
    <input type="hidden" name="voucherTypeId" id="voucherTypeId" value="2">
    <label for="establishment">Establishment:</label>
    <input type="text" name="establishment" id="establishment">
    <label for="emissionPoint">Emission Point:</label>
    <input type="text" name="emissionPoint" id="emissionPoint">
    <label for="secuential">Secuential:</label>
    <input type="text" name="secuential" id="secuential">
    <label for="dateOfIssue">Date:</label>
    <input type="text" name="dateOfIssue" id="dateOfIssue">
    <label for="tip">Tip:</label>
    <input type="text" name="tip" id="tip">
    <label for="totalWithoutTax">Total without tax:</label>
    <input type="text" name="totalWithoutTax" id="totalWithoutTax">
    <label for="totalDiscount">Total discount:</label>
    <input type="text" name="totalDiscount" id="totalDiscount">
    <label for="total">Total:</label>
    <input type="text" name="total" id="total">
    <h3>Store</h3>
    <label for="ruc">RUC:</label>
    <input type="text" name="ruc" id="ruc">
    <label for="businessName">Business name:</label>
    <input type="text" name="businessName" id="businessName">
    <label for="tradeName">Trade name:</label>
    <input type="text" name="tradeName" id="tradeName">
    <label for="parentAddress">Parent address:</label>
    <input type="text" name="parentAddress" id="parentAddress">
    <h3>Buyer</h3>
    <input type="hidden" name="identificationType" id="identificationType" value="05">
    <label for="identification">Identification:</label>
    <input type="text" name="identification" id="identification">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name">

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
    <input type="submit" value="Save">
</form>
