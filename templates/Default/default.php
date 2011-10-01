<?php
  
  class Default_Template extends Geek_Template{
    
    public function __construct(){
      $this->addCSS(  array(
                        HTTP_ROOT . 'library/css/html5cssReset.css',  
                        HTTP_ROOT . 'library/css/tTitle.css',
                        HTTP_ROOT . 'templates/Default/css/main.css'
                      ) 
      );
      $this->addJS( array( 
                      HTTP_ROOT . 'library/js/jquery.js', 
                      HTTP_ROOT . 'library/js/jquery.cookie.js',
                      HTTP_ROOT . 'library/js/jquery.tTitle.js',
                      HTTP_ROOT . 'templates/Default/js/default.js'
                    )
      );
    }
    
    public function getTop(){
      return '
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>'.$this->getTitle().'</title>
    '.$this->printCSS().$this->printJS().'
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
    ';
    }
    
    public function getBottom(){
      return '
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
