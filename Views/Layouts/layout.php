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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

</body>

</html>
