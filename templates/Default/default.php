<?php
  
  class Default_Template extends Geek_Template{
    
    public function __construct(){
      $this->css  = array(
                      WEB_ROOT . 'library/css/html5cssReset.css',  
                      WEB_ROOT . 'library/css/tTitle.css',
                      WEB_ROOT . 'templates/default/css/main.css'
                    );
      $this->js   = array( 
                      WEB_ROOT . 'library/js/jquery.js', 
                      WEB_ROOT . 'library/js/jquery.cookie.js',
                      WEB_ROOT . 'library/js/jquery.tTitle.js',
                      WEB_ROOT . 'templates/default/js/default.js'
                    );
    }
    
    public function render( $view ){
      echo '
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>'.$this->name.'</title>
    
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
    '.$view.'
  </section>

  <footer id="footer">
  (C) CS Club 2011
  </footer>

  </body>
</html>
      ';
    }
    
  }
  
?>
