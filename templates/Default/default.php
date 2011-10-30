<?php
  
  class Default_Template extends Geek_Template{
    
    public function __construct(){
    
      parent::__construct();
      
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
  '.$this->getHead().'
  <body>

    <header id="header">
      <h1>RPGeek - <span style="color:lightgreen">ALPHA</span></h1>
      <h2>Productive roleplaying for Geeks</h2>
      <div id="login">
        <form action="login.php" method="post">
          <input type="text" name="username" size="10" placeholder="username" title="Username" />
          <input type="password" name="password" size="10" placeholder="password" title="Password" />
          <input type="checkbox" name="remember" title="remember?" />
          <input type="submit" value="Log In" title="GO!" />
        </form>
        <div style="text-align: right;margin-top:3px">
          <input type="text" name="username" size="25" placeholder="Type to search" title="Search for fellow geeks" />
          <input type="submit" value="Search" />
        </div>
      </div>
      </form>
      <nav id="menu">
        <ul>
          <li><a href="?q=dashboard/index">Dashboard</a></li>
          <li><a href="?q=home.php">Home</a></li>
          <li><a href="?q=post/index">Forum</a></li>
          <li><a href="?q=problem/index">Tasks</a></li>
          <li><a href="?q=user/profile">Profile</a></li>
          <li><a href="?q=user/notifications" id="notifications">Notifications</a></li>
          <li><a href="?q=sitemap.php">Sitemap</a></li>
          <li><a href="?q=disclaimer.php"><b>Disclaimer</b></a></li>
          <li><a href="?q=signup.php">Sign Up</a></li>
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
  (C) Code4Fun 2011
  </footer>

  </body>
</html>
      ';
    }
    
  }
  
?>
