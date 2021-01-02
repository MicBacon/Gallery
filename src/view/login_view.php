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
          <?php include_once 'forms/login.phtml'; ?>
          <form method="post">
              <input type="submit" formaction="registration" value="Zarejestruj"/>
          </form>
      </div>

      <div class="menu">
          <nav>
            <ul>
              <li><a class="active"> Logowanie </a></li>
            </ul>
          </nav>
      </div>
    </div>

    <div id="space">
    </div>

  <?php include_once 'layouts/footer.phtml'; ?>

  </body>
</html>
