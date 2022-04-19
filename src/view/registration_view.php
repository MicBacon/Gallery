<!DOCTYPE html>
<html lang="pl" dir="ltr">
    <head>
        <?php include_once 'layouts/head.phtml'; ?>
    </head>
    <body id="body">
        <?php include_once 'layouts/header.phtml'; ?>

        <div id="content">
            <?php include_once 'forms/registration.phtml'; ?>
            <hr class="line" />
            <a class="navButton" href="/login"> Powrót do logowania </a>
            <a class="navButton" href="/"> Galeria </a>
        </div>

        <?php include_once 'layouts/footer.phtml'; ?>
    </body>
</html>
