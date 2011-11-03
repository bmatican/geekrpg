<?php
  
  if( count( $this->controller->users ) == 0 ){
    echo 'No users found';
  } else {
    foreach( $this->controller->users as $k => $v ){
      echo '<div class="post">
                <b>'.$v['username'].'</b>: '.$v['email'].'
            </div>';
    }
  }

?>
