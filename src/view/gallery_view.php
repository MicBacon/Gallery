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
            echo 'Witaj ' . $_SESSION["name"] . ' :)!';
            echo '<br/>Zalogowano pomyślnie.';
        ?>
        <form action="photo_upload" method="post" enctype="multipart/form-data">
            <input type="file" name="file"/><br/>
            <label for="title">Tytuł: </label>
            <input type="text" name="title"><br/>
            <label for="author">Autor: </label>
            <input type="text" name="author"><br/>
            <label for="watermark">Znak wodny:</label>
            <input type="text" name="watermark" id="watermark" required><br/>
            <input type="submit" value="Prześlij plik"/><br/>
            <?php
                if(isset($_SESSION['pictureerror'])){
                    echo $_SESSION['pictureerror'];
                }
            ?>
        </form>
        <br/>
        <form method="post">
            <input type="submit" name="show" formaction="show" value="Pokaż galerię"/>
            <input type="submit" name="unlog" formaction="/" value="Wyloguj"/>
        </form>
    </div>

    <div class="menu">
        <nav>
            <ul>
                <li><a class="active"> Strona startowa </a></li>
            </ul>
        </nav>
    </div>
</div>

<div id="space">
</div>

    <?php include_once 'layouts/footer.phtml'; ?>

</body>
</html>
