<?php
    require_once '../business.php';
?>
<!DOCTYPE html>
<html lang="pl" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Wikingowie | Galeria</title>
    <link rel="stylesheet" href="http://192.168.56.10:8080/static/css/galeria.css">
</head>
<body id="body">
<div id="wrapper">
    <header>
        <picture>
            <img src="http://192.168.56.10:8080/static/png/vikings_header.png" alt="WIKINGOWIE">
        </picture>
    </header>

    <div id="content">
        <?php
            show_gallery();
        ?>
        <form action="/" method="post">
            <input type="submit" name="start" formaction="start" value="Strona startowa">
            <input type="submit" name="unlog" value="Wyloguj"/>
        </form>
    </div>

    <div class="menu">
        <nav>
            <ul>
                <li><a class="active"> Galeria </a></li>
            </ul>
        </nav>
    </div>
</div>

<div id="space">
</div>

<footer class="footer">
    <i>Rzemieślnik strony: Michał Boczoń, s184263</i>
</footer>

</body>
</html>
