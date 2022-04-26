<?php $this->layout('Layouts/layout', [
    'title' => $title
]) ?>

<form action="BillSave.php" method="post" >
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
            <tr>
                <td><?= $billDetail->getMainCode() ?></td>
                <td><?= $billDetail->getDescription() ?></td>
                <td><?= $billDetail->getQuantity() ?></td>
                <td><?= $billDetail->getUnitPrice() ?></td>
                <td><?= $billDetail->getDiscount() ?></td>
                <td><?= $billDetail->getTotalPriceWithoutTaxes() ?></td>
                <td>
                    <select>
                        <option value="0" selected>Select deductible...</option>
                    </select>
                </td>
            </tr>
            <?php endforeach;  ?>
        </tbody>
    </table>
    
    
    
    <input type="submit" value="Load">
</form>