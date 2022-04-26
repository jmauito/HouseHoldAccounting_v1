<?php $this->layout('Layouts/layout', [
    'title' => $title
]) ?>

<form action="BillXmlLoad.php" method="post" enctype="multipart/form-data">
    <label for="xml-file">Search file:</label>
    <input type="file" name="xml-file" id="xml-file">
    <input type="submit" value="Load">
</form>