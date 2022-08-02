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
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

</head>
<header class="nav-bar">
    <nav>
        <div class="logo-space">
            <a href="/" class="logo">Main menu</a>
        </div>
        <div class="icon-nav">
            <i class="fa fa-bars" aria-hidden="true"></i>
            <i class="fa fa-times" aria-hidden="true" style="display: none"></i>
        </div>

        <ul>
            <li>
                <a href="load-from-xml">Load bill from xml file</a>
            </li>
            <li><a href="create-bill">Register bill</a> </li>

        </ul>
    </nav>
</header>
<body>
<div class="main-container">
    <div class="content">
        <header class="main-title">
            <h1> <?= $title ?>: </h1>
        </header>

        <section id="main">
            <?= $this->section('content')  ?>
        </section>

    </div>
</div>

<script src="styles/jquery.js"></script>
<script type="text/javascript" src="styles/index.js"></script>
<script src="styles/fa.js"></script>
</body>

</html>
