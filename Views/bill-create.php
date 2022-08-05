<?php
$this->layout('Layouts/layout', [
    'title' => $title
]);
?>

<form action="insert-bill" method="post">
    <input type="hidden" name="voucherTypeId" id="voucherTypeId" value="1">
    <label for="establishment">Establishment:</label>
    <input type="text" name="establishment" id="establishment">
    <label for="emissionPoint">Emission Point:</label>
    <input type="text" name="emissionPoint" id="emissionPoint">
    <label for="sequential">Secuential:</label>
    <input type="text" name="sequential" id="sequential">
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
<select name="deductibles" id="deductibles" hidden>
    <option value="0">No deductible</option>
    <?php foreach ($deductibles as $deductible) :  ?>
        <option value="<?= $deductible->getId() ?>"> <?= $deductible->getName() ?> </option>
    <?php endforeach;?>
</select>
<script>
    function createInput(name, i, value){
        const input = document.createElement('input')
        input.id= `${name}-${i}`
        input.name = `${name}[${i}]`
        input.value = value
        return input
    }
    function insertItem(){
        let itemsCount = document.getElementById('itemsCount')
        const i = (itemsCount.value * 1) + 1
        itemsCount.value = i
        const divItem = document.createElement('div')
        divItem.id = 'item-' + i
        const removeItem = document.createElement('input')
        removeItem.type = "button"
        removeItem.value = '-'
        removeItem.setAttribute('onclick', `removeItem(${i})`)
        const quantity = createInput('quantity', i, 0)
        quantity.setAttribute("onchange", `calculateTotalPriceWithoutTaxes(${i})`)
        const unitPrice = createInput('unitPrice', i, 0)
        unitPrice.setAttribute("onchange", `calculateTotalPriceWithoutTaxes(${i})`)
        const discount = createInput('discount', i, 0)
        discount.setAttribute("onchange", `calculateTotalPriceWithoutTaxes(${i})`)
        const totalPriceWithoutTaxes = createInput('totalPriceWithoutTaxes', i, 0)
        totalPriceWithoutTaxes.oldValue = 0
        totalPriceWithoutTaxes.setAttribute('onchange',`changeTotalPriceWithoutTaxes(${i},this)`)
        divItem.appendChild(createInput('mainCode', i, i.toString()))
        divItem.appendChild(createInput('description', i, null))
        divItem.appendChild(quantity)
        divItem.appendChild(unitPrice)
        divItem.appendChild(discount)
        divItem.appendChild(totalPriceWithoutTaxes)
        createDeductibleDetailControls(i,divItem)
        divItem.appendChild(removeItem)
        let items = document.getElementById("items")
        items.appendChild(divItem)
    }
    function calculateTotalPriceWithoutTaxes(i){
        const quantity = document.getElementById(`quantity-${i}`)
        const unitPrice = document.getElementById(`unitPrice-${i}`)
        const discount = document.getElementById(`discount-${i}`)
        const totalWithoutTaxes = document.getElementById(`totalPriceWithoutTaxes-${i}`)
        totalWithoutTaxes.oldValue = totalWithoutTaxes.value
        totalWithoutTaxes.value = quantity.value * unitPrice.value - discount.value
        updateDeductibleValue(i, totalWithoutTaxes.value, totalWithoutTaxes.oldValue)
    }
    function createDeductibleDetailControls(i, divItem){
        const selectDeductible = document.getElementById('deductibles')
        const newSelectDeductible = selectDeductible.cloneNode(true)
        newSelectDeductible.id = "deductibleDetailId-" + i
        newSelectDeductible.name = `deductibleDetailId[${i}]`
        newSelectDeductible.removeAttribute('hidden')
        newSelectDeductible.setAttribute('onchange',`changeDeductibleDetail(this,${i})`)
        divItem.appendChild( newSelectDeductible )
        const deductibleValue = document.createElement('input')
        deductibleValue.id = "deductibleDetailValue-" + i
        deductibleValue.name = `deductibleDetailValue[${i}]`
        deductibleValue.type = "hidden"
        divItem.appendChild(deductibleValue)
    }
    function removeItem(i){
        const items = document.getElementById('items')
        const item = document.getElementById('item-' + i)
        const deductibleDetailId = document.getElementById(`deductibleDetailId-${i}`)
        const deductibleDetailValue = document.getElementById(`deductibleDetailValue-${i}`)
        subtractDeductibleValue(deductibleDetailId.value, deductibleDetailValue.value)
        items.removeChild(item)
    }
    function changeDeductibleDetail(select, i){
        const totalWithoutTax = document.getElementById(`totalPriceWithoutTaxes-${i}`)
        const deductibleDetailValue = document.getElementById(`deductibleDetailValue-${i}`)
        const deductibleId = select.value
        const deductible = document.getElementById(`deductible-${deductibleId}`)
        deductibleDetailValue.value = totalWithoutTax.value
        if (null != select.oldValue && 0 != select.oldValue){
            subtractDeductibleValue(select.oldValue, totalWithoutTax.value)
        }
        select.oldValue = select.value
        if(0 != deductibleId){
            deductible.value = deductible.value * 1 + (deductibleDetailValue.value*1)
        }

    }
    function updateDeductibleValue(i, newValue, oldValue){
        const deductibleDetail = document.getElementById(`deductibleDetailId-${i}`)
        if(deductibleDetail.value == 0 || deductibleDetail.value == null){
            return
        }
        const deductible = document.getElementById(`deductible-${deductibleDetail.value}`)
        deductible.value = deductible.value*1 - oldValue*1
        deductible.value = deductible.value*1 + newValue*1
    }
    function subtractDeductibleValue(deductibleId, value){
        const deductible = document.getElementById(`deductible-${deductibleId}`)
        if (deductible != null){
            deductible.value = deductible.value - value
        }
    }

</script>