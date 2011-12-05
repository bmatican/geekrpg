<?php
/**
  * This is generally where the license goes :)
  */

class PostController extends Geek_Controller {
  public $postModel;
  public $postCommentModel;
  public $tagModel;
    
  /**
    * Default constructor.
    */
  public function __construct() {
    parent::__construct();
    $this->postModel = new PostModel();
    $this->postCommentModel = new CommentModel("Posts");
    $this->tagModel = new TagModel("Posts");
  }
  
  // POSTS 
  
  public function index( $limit = 20, $offset = 0 ) {
    //$posts = $this->tagModel->getObjectsWithTags( array(), $limit, $offset );
    $result = $this->postModel->getAllWhere( array(), $limit, $offset );
    $ids = array();
    $posts = array();
    foreach( $result as $k => $v ){
      $ids[] = $v['id'];
      $posts[$v['id']] = $v;
    }
    $tags = $this->tagModel->getTagsForObjects( $ids );
    foreach( $tags as $k => $v ){
      $posts[$k]['tags'] = $v;
    }
    $this->render( 'index', array( 'posts' => $posts ) );
  }

  public function search( $query = null ) {
    if ( null == $query ) {
      $this->render('index');
    } else {
      $posts = $this->postModel->getAllWhere( array("title LIKE '%$query%' OR body LIKE '%$query%'") );
      $this->render('index', array( 'posts' => $posts ));
    }
  }
  
  public function view( $id = null ){
    if( null != $id && is_numeric($id) ){
      $post = $this->postModel->getAllWhere( array("id = $id") );
      $post = $post[0];
      $post["comments"] = $this->postCommentModel->getComments($post["id"]);
      $post["comments"] = $post["comments"][0]["children"];
      $this->render( 'view', array( 'post' => $post ) );
    } else {
      $this->renderError( '404' );
    }
  }

  private function _check_editAdd( $view, $title = null, $body = null, $state = PostModel::POST_OPEN ){
    if( $title === null ){
      $this->render( $view );
      return null;
    } else {
      if ($state < 0 || $state >= PostModel::POST_MAX_STATE) {
        $this->render( '404' );
        return null;
      } else {
        if( strlen( $title ) < 4 || strlen( $title ) > 42 ){
          $this->_errors['title'] = 'Title must be between 4 and 42 characters long!';
        }
        if( strlen( $body ) < 10 ){
          $this->_errors['body'] = 'You can\'t find at least 10 characters for this textfield?';
        }
        if( !empty( $this->_errors ) ){
          $this->render( $view, array( '__errors' => $this->_errors ) );
        } else {
          $userid = $_SESSION['user']['id'];
          $values = array(
            "userid"    => $userid,
            "body"      => $body,
            "title"     => $title,
            "dateAdded" => time(),
            "state"     => $state,
          );

          return $values;
        }
      }
    }
  }

  private function _checkTags( $view, $tagString ){
    $arr = $this->tagModel->getAllWhere( array('id>0') );
    $tags = array();
    foreach( $arr as $k => $v ){
      $tags[] = $v['name'];
    }
    $tmp = array_map( 'trim', explode(',', $tagString) );
    $diff = array_diff( $tmp, $tags );
    if( !empty( $diff ) ){
      $this->_errors['tags'] = 'Unknown tag(s): '.implode(', ', $diff);
      $this->render( $view, array( '__errors' => $this->_errors ) );
      return false;
    } else {
      return $tmp;
    }
  }
  
  public function add($title = null, $body = null, $tags = null, $state = PostModel::POST_OPEN) {
    // TODO: check rights??
    if( $values = $this->_check_editAdd( 'Add', $title, $body, $state ) ){
      if( $tags = $this->_checkTags( 'add', $tags ) ){
        $this->postModel->insert($values);
        $id = $this->postModel->getInsertId();
        $this->tagModel->setTagsFor( $id, $tags );
        Geek::redirect( Geek::path('post/index') );
      }
    }
  }

  /**
    * Removes a post from the DB.
    */
  public function remove($postid = null) {
    //TODO: admin rights??
    $this->postModel->removeById($postid);
    $this->render();
  }
  
  public function edit( $id, $title = null, $body = null, $state = PostModel::POST_OPEN ){
    $post = $this->postModel->getById( $id );

    Geek::setDefaults( $_POST, array( '__edit' => false ) );

    if( $_POST['__edit'] ){
      if( $values = $this->_check_editAdd( 'Add', $title, $body, $state ) ){
        $values['id'] = $id;
        $this->postModel->update( $values );
        Geek::redirect( Geek::path('post/index') );
      }
    } else {
      $addView = $this->getViewInstance( 'Add' );
      $addView
        ->get( 'form/post' )
        ->setAction( 'post/edit/'.$id )
        ->setArgsOrder( 'id,title,body' )
        ->addData( array('__edit' => 'yes', 'id' => $id) )
        ->setValues( $post );

      $this->render( $addView );
    }
  }
  
  // COMMENTS
  
  public function comment($postid, $body, $parentid = 0, $state = CommentModel::COMMENT_OPEN) {
    //TODO: check rights
    $userid = $_SESSION['user']['id'];
    $values = array(
      "userid"    => $userid,
      "postid"    => $postid,
      "body"      => $body,
      "dateAdded" => time(),
      "parentid"  => $parentid,
      "state"     => $state,
    );
    $this->postCommentModel->insert($values);
    Geek::redirectBack();
  }
  
  // TAGS
  
  public function tags($limit = 50, $offset = 0) {
    $this->tags = $this->tagModel->getAllWhere( array('id>0'), $limit, $offset );
    $this->render('tags');
  }
  
  public function tag($tags = null, $method = "and"){
    if ( !$tags || !in_array($method, array("and", "or"))) {
      $this->render("404");
    } else {
      $tags = explode(",", $tags);
      $this->posts = $this->tagModel->getObjectsFor(
        $tags,
        'id',
        $method == "and" ? TRUE : FALSE
      );
      $this->render("index");
    }
  }
  
  // OTHERS
}

?>

