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

    <div>
        <h2>Details</h2>
        <input type="button" onclick="insertItem()"  value="New item"/>
        <input type="hidden" id="itemsCount" name="itemsCount" value="0">
        <div id="items" name = "items"></div>
    </div>
    <input type="submit" value="Save">
</form>
<script>
    function createInput(name, i, value){
        const input = document.createElement('input')
        input.id= name + i
        input.name = `${name}[${i}]`
        input.value = value
        return input
    }
    function insertItem(){
        let itemsCount = document.getElementById('itemsCount')
        const i = (itemsCount.value * 1) + 1
        itemsCount.value = i
        const divItem = document.createElement('div')
        divItem.id = 'item' + i
        const mainCode = createInput('mainCode', i, i.toString())
        const description = createInput('description', i, null)
        const quantity = createInput('quantity', i, 0)
        const unitPrice = createInput('unitPrice', i, 0)
        const discount = createInput('discount', i, 0)
        const totalPriceWithoutTaxes = createInput('totalPriceWithoutTaxes', i, 0)
        const removeItem = document.createElement('button')
        removeItem.innerHTML = '-'
        removeItem.setAttribute('onclick', `removeItem(${i})`)

        divItem.appendChild(mainCode)
        divItem.appendChild(description)
        divItem.appendChild(quantity)
        divItem.appendChild(unitPrice)
        divItem.appendChild(discount)
        divItem.appendChild(totalPriceWithoutTaxes)
        divItem.appendChild(removeItem)
        let items = document.getElementById("items")
        items.appendChild(divItem)
    }
    function removeItem(i){
        const item = document.getElementById('item' + i)
        const items = document.getElementById('items')
        items.removeChild(item)
    }
</script>