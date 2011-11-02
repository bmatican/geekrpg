<?php
  
  class Default_Template extends Geek_Template{
    
    public function __construct(){
    
      parent::__construct();
      
      $this->addCSS(  array(
                        HTTP_ROOT . 'library/css/html5cssReset.css',  
                        HTTP_ROOT . 'library/css/form.css',
                        HTTP_ROOT . 'library/css/post.css',
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
      $h = '
<!DOCTYPE html>
<html lang="en">
  '.$this->getHead().'
  <body>

    <header id="header">
      <h1>RPGeek - <span style="color:lightgreen">ALPHA</span></h1>
      <h2>Productive roleplaying for Geeks</h2>
      <div id="login">
        ';
/** TODO: HACKED FOR NOW
    NOT THE WAY TO DO IT
    SHOULD BE INCLUDED FROM SOMEWHERE ELSE, NOT HACKED FROM HERE
*/
if( isset($_SESSION['username']) ){
  $h .= '
  <div>
    Welcome <b>'.$_SESSION['username'].'</b>.
    <a href="'.Geek::path('user/logout').'">log out</a>
  </div>';
} else {
  $h .= '
    <form action="'.Geek::path('user/login').'" method="post">
      <input type="hidden" name="__form_name" value="login" />
      <input type="hidden" name="__argumentsOrder" value="username,password" />
      <input type="text" name="login/username" size="10" placeholder="username" title="Username" />
      <input type="password" name="login/password" size="10" placeholder="password" title="Password" />
      <input type="checkbox" name="login/remember" title="remember?" />
      <input type="submit" name="login/submit" value="Log In" title="GO!" />
    </form>
  ';
}

$h .= '
        <div style="text-align: right;margin-top:3px">
          <form action="' . Geek::path('user/search') . '" method="post">
            <input type="text" name="search/username" size="25" placeholder="Type to search" title="Search for fellow geeks" />
            <input type="submit" name="search/submit" value="Search" />
          </form>
        </div>
      </div>
      </form>
      <nav id="menu">
        <ul>
          <li><a href="'.Geek::path('dashboard/index').'">Dashboard</a></li>
          <li><a href="'.Geek::path('home.php').'">Home</a></li>
          <li><a href="'.Geek::path('post/index').'">Forum</a></li>
          <li><a href="'.Geek::path('problem/index').'">Tasks</a></li>
          <li><a href="'.Geek::path('user/profile').'">Profile</a></li>
          <li><a href="'.Geek::path('user/notifications').'" id="notifications">Notifications</a></li>
          <li><a href="'.Geek::path('sitemap.php').'">Sitemap</a></li>
          <li><a href="'.Geek::path('disclaimer.php').'"><b>Disclaimer</b></a></li>';
      if( !isset($_SESSION['username']) ){
        $h .= '<li><a href="'.Geek::path('user/signup').'">Sign Up</a></li>';
      }
          $h .= '
        </ul>
      </nav>
    </header>

  <section id="content">
    ';
    
      return $h;
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
