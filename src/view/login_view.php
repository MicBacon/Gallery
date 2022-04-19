<!DOCTYPE html>
<html lang="pl" dir="ltr">
    <head>
        <?php include_once 'layouts/head.phtml'; ?>
    </head>
    <body id="body">
        <div id="wrapper">

            <?php include_once 'layouts/header.phtml'; ?>

            <div id="content">
                <?php include_once 'forms/login.phtml'; ?>
                <a class="navButton" href="/registration"> Zarejestruj </a>
                <hr class="line"/>
                <a class="navButton" href="/"> Galeria </a>
            </div>

        </div>

        <?php include_once 'layouts/footer.phtml'; ?>

    </body>
</html>
