<div style="text-align:right;margin-bottom:5px;padding:2px;border-bottom:1px solid #ccc;">
  <a href="<?php echo Geek::path('post/createTag'); ?>">Add a post</a>
</div>
<?php
  
  if( count( $this->controller->posts ) == 0 ){
    echo 'No tags found';
  } else {
    foreach( $this->controller->tags as $k => $v ){
      echo '<div class="post">
              <a href="'.Geek::path('post/tag/'.$v['name']).'">
                <b>'.$v['name'].'</b>: '.$v['description'].'
              </a>
            </div>';
    }
  }

?>
