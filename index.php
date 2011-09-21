<!DOCTYPE html>
<html lang="en">
  <head>
    <title>RPGeek - ALPHA</title>
    <link rel="stylesheet" type="text/css" href="/geekrpg/css/main.css" />
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.cookie.js"></script>
    <link rel="stylesheet" type="text/css" href="css/tTitle.css" />
    <script type="text/javascript" src="js/jquery.tTitle.js"></script>
    <script type="text/javascript" src="js/default.js"></script>
  </head>

  <body>

    <header id="header">
      <h1>RPGeek - <span style="color:lightgreen">ALPHA</span></h1>
      <form action="login.php" method="post" id="login">
        <div>
          <input type="text" name="username" size="10" placeholder="username" title="Username" />
          <input type="password" name="password" size="10" placeholder="password" title="Password" />
          <input type="checkbox" name="remember" title="remember?" />
          <input type="submit" value="Log In" title="GO!" />
        </div>
      </form>
      <nav id="menu">
        <ul>
          <li><a href="home.php">Home</a></li>
          <li><a href="signup.php">Sign Up</a></li>
        </ul>
      </nav>
    </header>

  <section id="content">
    <?php
      define( 'WEB_ROOT' , dirname(__FILE__));
      define( 'DS', DIRECTORY_SEPARATOR  );

      require_once "config/config.php";
      require_once PATH_CORE . DIRECTORY_SEPARATOR . "globals.php";

      requireFolder(PATH_CORE);

      $q    = isset($_GET['q']) ? $_GET['q'] : 'home.php';

      $pathComponents = explode("/", $q);
      $application = $pathComponents[0];
      $method = $pathComponents[1];
      $args = array_slice($pathComponents, 2);

      $dispatcher = new Geek_Dispatcher();
      $dispatcher->dispatch($application, $method, $args);

      $file = "views/$q";
      if( file_exists( $file ) ){
        include_once( $file );
      } else {
        include_once( 'views/404.php' );
      }
    ?>
  </section>

  <footer id="footer">
  (C) CS Club 2011
  </footer>

  </body>
</html>
