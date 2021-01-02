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
        <form action="create_user" method="post">
            <label for="name">Imię: </label>
            <input type="text" name="name" required><br/>
            <label for="mail">E-mail: </label>
            <input type="email" name="mail" required><br/>
            <label for="login">Login: </label>
            <input type="text" name="login" required><br/>
            <label for="password">Hasło: </label>
            <input type="password" name="password" required><br/>
            <label for="password">Powtórz hasło: </label>
            <input type="password" name="reppassword" required><br/>
            <?php
                if(isset($_SESSION['regerror'])){
                    echo $_SESSION['regerror'];
                }
            ?>
            <input type="submit" value="Zarejestruj"/>
        </form>
        <form method="post">
            <input type="submit" formaction="/" value="Logowanie"/>
        </form>
    </div>

    <div class="menu">
        <nav>
            <ul>
                <li><a class="active"> Rejestracja </a></li>
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
