<?php
$this->layout('Layouts/layout', [
    'title' => $title
]);
?>

<form action="insert-bill" method="post">
    <div class="m-3">
        <input type="hidden" name="voucherTypeId" id="voucherTypeId" value="1">
    </div>

    <div class="m-3 row">
        <div class="col-sm-1">
            <label>Number:</label>
        </div>
        <div class="col">
            <input type="text" name="establishment" id="establishment" value="001" maxlength="3" size="3">
            <input type="text" name="emissionPoint" id="emissionPoint" value="001" maxlength="3" size="3">
            <input type="text" name="sequential" id="sequential">
        </div>
    </div>
    <div class="m-3 row">
        <div class="col-sm-1">
        <label for="dateOfIssue">Date:</label>
        </div>
        <div class="col">
            <input type="date" name="dateOfIssue" id="dateOfIssue">
        </div>
    </div>

    <div class="m-3 row">
        <div class="col">

        </div>
    </div>
    <div class="m-3 row">
        <h3>Store</h3>
        <div class="col-sm-6">
            <div class="col">
                <label for="ruc">RUC:</label>
                <input type="text" name="ruc" id="ruc">
            </div>
            <div class="col">
                <label for="businessName">Business name:</label>
                <input type="text" name="businessName" id="businessName">
            </div>
            <div class="col">
                <label for="tradeName">Trade name:</label>
                <input type="text" name="tradeName" id="tradeName">
            </div>
            <div class="col">
                <label for="parentAddress">Parent address:</label>
                <input type="text" name="parentAddress" id="parentAddress">
            </div>
        </div>
        <div class="col-sm-6">
            <h3>Buyer</h3>
            <div class="row">
                <div class="col">
                    <input type="hidden" name="identificationType" id="identificationType" value="05">
                    <label for="identification">Identification:</label>
                    <input type="text" name="identification" id="identification">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name">
                </div>
            </div>
        </div>
    </div>

    <div class="m-3 row">
        <div class="col">
            <label for="tip">Tip:</label>
            <input type="text" name="tip" id="tip" value="0">
        </div>
        <div class="col">
            <label for="totalWithoutTax">Total without tax:</label>
            <input type="text" name="totalWithoutTax" id="totalWithoutTax" value="0">
        </div>
        <div class="col">
            <label for="totalDiscount">Total discount:</label>
            <input type="text" name="totalDiscount" id="totalDiscount" value="0">
        </div>
        <div class="col">
            <label for="total">Total:</label>
            <input type="text" name="total" id="total" value="0">
        </div>

    </div>

    <div class="m-3 row">
        <h3>Deductibles</h3>

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

    <div class="m-3 row">
        <h3>Expenses</h3>
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

    <div class="m-3">
        <h3>
            Details
            <span class="badge">
                <input class="btn btn-secondary btn-sm" type="button" onclick="insertItem()"  value="+"/>
            </span>
        </h3>
        <div class="row">
            <div class="col-sm-1">
                <h6>Nro.</h6>
            </div>
            <div class="col">
                <h6>Description.</h6>
            </div>
            <div class="col-sm-1">
                <h6>Quantity.</h6>
            </div>
            <div class="col-sm-1">
                <h6>Unit price.</h6>
            </div>
            <div class="col-sm-1">
                <h6>Discount</h6>
            </div>
            <div class="col-sm-1">
                <h6>Total</h6>
            </div>
            <div class="col-sm">
                <h6>Deductible</h6>
            </div>
            <div class="col-sm-1">
                ..
            </div>
        </div>
        <input type="hidden" id="itemsCount" name="itemsCount" value="0">
        <div id="items" name = "items"></div>
    </div>
    <div class="d-grid gap-2">
        <input class="btn btn-primary" type="submit" value="Save">
    </div>

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
        divItem.setAttribute("class","row")
        divItem.id = 'item-' + i
        const removeItem = document.createElement('input')
        removeItem.type = "button"
        removeItem.value = '-'
        removeItem.setAttribute("class", "btn btn-secondary btn-sm")
        removeItem.setAttribute('onclick', `removeItem(${i})`)
        const quantity = createInput('quantity', i, 0)
        quantity.setAttribute("onchange", `calculateTotalPriceWithoutTaxes(${i})`)
        quantity.setAttribute("size", "6")
        const unitPrice = createInput('unitPrice', i, 0)
        unitPrice.setAttribute("onchange", `calculateTotalPriceWithoutTaxes(${i})`)
        unitPrice.setAttribute("size", "6")
        const discount = createInput('discount', i, 0)
        discount.setAttribute("onchange", `calculateTotalPriceWithoutTaxes(${i})`)
        discount.setAttribute("size","6")
        const totalPriceWithoutTaxes = createInput('totalPriceWithoutTaxes', i, 0)
        totalPriceWithoutTaxes.oldValue = 0
        totalPriceWithoutTaxes.setAttribute('onchange',`changeTotalPriceWithoutTaxes(${i},this)`)
        totalPriceWithoutTaxes.setAttribute("size","6")
        const mainCode = createInput('mainCode', i, i.toString())
        mainCode.setAttribute("size","3")
        const description = createInput('description', i, null)
        const divMainCode = document.createElement('div')
        divMainCode.setAttribute("class", "col-sm-1")
        divMainCode.appendChild(mainCode)
        divItem.appendChild(divMainCode)

        const divDescription = document.createElement('div')
        divDescription.setAttribute("class", "col")
        divDescription.appendChild(description)
        divItem.appendChild(divDescription)

        const divQuantity = document.createElement('div')
        divQuantity.setAttribute("class", "col-sm-1")
        divQuantity.appendChild(quantity)
        divItem.appendChild(divQuantity)

        const divUnitPrice = document.createElement('div')
        divUnitPrice.setAttribute("class", "col-sm-1")
        divUnitPrice.appendChild(unitPrice)
        divItem.appendChild(divUnitPrice)

        const divDiscount = document.createElement('div')
        divDiscount.setAttribute("class", "col-sm-1")
        divDiscount.appendChild(discount)
        divItem.appendChild(divDiscount)

        const divTotalPriceWithoutTaxes = document.createElement('div')
        divTotalPriceWithoutTaxes.setAttribute("class", "col-sm-1")
        divTotalPriceWithoutTaxes.appendChild(totalPriceWithoutTaxes)
        divItem.appendChild(divTotalPriceWithoutTaxes)

        createDeductibleDetailControls(i,divItem)

        const divRemoveItem = document.createElement('div')
        divRemoveItem.setAttribute("class", "col-sm-1")
        divRemoveItem.appendChild(removeItem)
        divItem.appendChild(divRemoveItem)
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
    }
    function createDeductibleDetailControls(i, divItem){
        const selectDeductible = document.getElementById('deductibles')
        const newSelectDeductible = selectDeductible.cloneNode(true)
        newSelectDeductible.id = "billDetailDeductible-" + i
        newSelectDeductible.name = `billDetailDeductible[${i}]`
        newSelectDeductible.removeAttribute('hidden')
        newSelectDeductible.setAttribute('onchange',`changeBillDetailDeductible(${i}, document.getElementById('totalPriceWithoutTaxes-${i}').value)`)
        newSelectDeductible.setAttribute('class', 'sm-1')
        const divDeductibleDetail = document.createElement('div')
        divDeductibleDetail.setAttribute("class", "col")
        divDeductibleDetail.appendChild(newSelectDeductible)
        divItem.appendChild( divDeductibleDetail )
        const deductibleValue = document.createElement('input')
        deductibleValue.id = "deductibleId" + i
        deductibleValue.name = `deductibleId${i}`
        deductibleValue.type = "hidden"
        divItem.appendChild(deductibleValue)
    }
    function removeItem(i){
        const items = document.getElementById('items')
        const item = document.getElementById('item-' + i)
        const billDetailDeductible = document.getElementById(`billDetailDeductible-${i}`)
        billDetailDeductible.value = 0
        changeBillDetailDeductible(i, document.getElementById(`totalPriceWithoutTaxes-${i}`).value)
        items.removeChild(item)
    }
    
    function changeBillDetailDeductible(mainCode, totalPriceWithoutTaxes){
        const selectedDeductible = document.getElementById('billDetailDeductible-' + mainCode)
        const previouslyDeductible = document.getElementById('deductibleId' + mainCode)
        updateDetail('deductible', selectedDeductible, previouslyDeductible, totalPriceWithoutTaxes)
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