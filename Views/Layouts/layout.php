<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Household accounting</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
<div class="main-container">
    <div class="lateral-menu">
        <nav class="nav">
            <ul>
                <li>
                    <a href="load-from-xml">Load from xml file</a>
                </li>
                <li>Option 2</li>
                <li>Option 3</li>
                <li>Option 4</li>
                <li>Option 5</li>
            </ul>

        </nav>
    </div>



    <div class="content">

        <header>
            <h1> <?= $title ?>: </h1>
        </header>

        <section id="main">
            <?= $this->section('content')  ?>
        </section>

    </div>
</div>


</body>
</html>
