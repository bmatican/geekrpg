<?php
  
  class DefaultTemplate extends GeekTemplate{
    
    public function __construct(){
      parent::__construct();
      
      $this->addCSS(  array(
                        HTTP_ROOT . 'library/css/html5cssReset.css',  
                        HTTP_ROOT . 'library/css/form.css',
                        HTTP_ROOT . 'library/css/post.css',
                        HTTP_ROOT . 'library/css/comment.css',
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
    
    protected function addTop(){
      $h = '
<!DOCTYPE html>
<html lang="en">
  '.$this->getHead().'
  <body>

    <header id="header">
      <h1>RPGeek - <span style="color:lightgreen">ALPHA</span></h1>
      <h2>Productive roleplaying for Geeks</h2>
      <div id="login">
        '.Geek::getView( 'LogIn' )->toString().'
      </div>
      </form>
      <nav id="menu">
        <ul>
          <li><a href="'.Geek::path('dashboard/index').'">Dashboard</a></li>
          <li><a href="'.Geek::path('Home').'">Home</a></li>
          <li><a href="'.Geek::path('post/index').'">Forum</a></li>
          <li><a href="'.Geek::path('problem/index').'">Tasks</a></li>
          <li><a href="'.Geek::path('user/profile').'">Profile</a></li>
          <li><a href="'.Geek::path('user/index').'">Users</a></li>'
//          <li><a href="'.Geek::path('user/notifications').'" id="notifications">Notifications</a></li>
         .' <li><a href="'.Geek::path('Sitemap').'">Sitemap</a></li>
          <li><a href="'.Geek::path('Disclaimer').'"><b>Disclaimer</b></a></li>';
          if( !Geek::isOnline() ){
            $h .= '<li><a href="'.Geek::path('user/signup').'">Sign Up</a></li>';
          }
          $h .= '
        </ul>
      </nav>
    </header>

  <section id="content">
    ';
    
      $this->prepend( $h );
    }
    
    protected function addBottom(){
      $this->add('
  </section>

  <footer id="footer">
  (C) Code4Fun 2011
  </footer>

  </body>
</html>
      ');
    }
    
  }
  
?>
