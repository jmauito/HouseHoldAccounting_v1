<?php $this->layout('Layouts/layout', [
    'title' => $title
]) ?>

<div class="content">
    <form action="create-bill-from-xml" method="post" enctype="multipart/form-data">
        <div style="width:90%; margin:auto; align-content: center">
            <label for="xml-file">Search file:</label>
            <input type="file" name="xml-file" id="xml-file">
        </div>
        <div style="width:90%; margin:auto">
            <input style="display: block; text-align: center" type="submit" value="Load">
        </div>

    </form>
</div>