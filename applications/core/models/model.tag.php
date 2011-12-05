<?php

class TagModel extends Geek_Model {
  public $tagTable;
  public $tagMapTable;
  public $objectTable;
  public function __construct($tableName) {
    $this->tagTable = $tableName . '_tags';
    $this->tagMapTable = $tableName . '_tagmap';
    $this->objectTable = $tableName;
    parent::__construct($this->tagTable);
  }

  /**
   * @override
   */
  protected function _createTables() {
    $createTags   = 'CREATE TABLE IF NOT EXISTS ' 
      . $this->tagTable
      . ' ( '
      . ' id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, '
      . ' name VARCHAR(30) NOT NULL UNIQUE KEY, '
      . ' description TINYTEXT NOT NULL '
      . ' )';
    $createTagMap = 'CREATE TABLE IF NOT EXISTS ' 
      . $this->tagMapTable 
      .  ' ( '
      . 'id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(id), '
      . ' objectid INT NOT NULL, '
      . ' tagid INT NOT NULL, '
      . ' KEY (objectid), '
      . ' KEY (tagid), '
      . ' UNIQUE KEY uq_key (objectid, tagid),  '
      . ' CONSTRAINT fk_tag FOREIGN KEY(tagid) REFERENCES ' 
      . $this->tagTable . '(id) '
      . ' ON UPDATE CASCADE ON DELETE CASCADE, ' 
      . ' CONSTRAINT fk_object FOREIGN KEY(objectid) REFERENCES ' 
      . $this->tablename . '(id) '
      . ' ON UPDATE CASCADE ON DELETE CASCADE '
      . ' )';
    
    $this->query($createTags);
    $this->query($createTagMap);
  }

  /**
    * For development purposes now.
    * @param $tags an array of arrays of 'name' and 'description'
    */
  public function createTags($tags) {
    $query = 'INSERT INTO ' 
      . $this->tagTable
      . ' (name, description) VALUES ';
    $values = '';
    foreach($tags as $ind => $entry) {
      $values .= ' ( "' . $entry['name'] . '", "' . $entry['description'] . '"), ';
    }
    
    if ('' !== $values) {
      $values = substr($values, 0, -2);
    }

    $query .= $values;
    return $this->query($query);
  }

  /**
    * For development purposes now.
    * @param $tags an array tag names
    */
  public function destroyTags( array $tags ) {
      $query = 'DELETE FROM '
        . $this->tagTable . ' '
        . 'WHERE name IN ' . $this->_createSetOfStrings($tags);
        
      return $this->query($query);
  }

  /**
    * Add a specific set of tags to an object.
    * @param $objectid the object to attach tags to
    * @param $tags an array of tag names 
    * @return {ARRAY} an array of 'name' => 'tag' arrays 
    */
  public function getTagsFor($objectid) {
    $filterString = '';
    if( null !== $objectid ){
      $filterString = ' AND tm.objectid = '.$objectid;
    }
    $query = 'SELECT tags.name FROM '
      . $this->tagMapTable . ' tm, '
      . $this->tagTable . ' tags '
      . ' WHERE tags.id = tm.tagid '
      . $filterString;  
    $result = $this->_getResult($this->query($query));
    $arr = array();
    foreach( $result as $k => $v ){
      $arr[] = $v['name'];
    }
    return $arr;
  }

  /**
    * Delete specific tags for a specific object
    * @param $objectid the object to remove tags from
    * @param $tags the tag strings to remove
    */
  public function deleteTagsFor($objectid, $tags) {
    $query = 'DELETE tm.* FROM '
      . $this->tagMapTable . ' tm '
      . ' LEFT JOIN ' . $this->tagTable . ' tags '
      . ' ON tags.id = tm.tagid '
      . ' WHERE tm.objectid = $objectid '
      . ' AND tags.name in ';

    $query .= $this->_createSetOfStrings($tags);
    $this->query($query);
  }

  /**
    * Add specific tags for a specific object
    * @param $objectid the object to attach to
    * @param $tags an array of tag strings to attach
    */
  public function addTagsFor($objectid, $tags) {
    $query = 'INSERT INTO '
      . $this->tagMapTable
      . ' (objectid, tagid)'
      . ' SELECT '.$objectid.', tags.id '
      . ' FROM ' . $this->tagTable .' tags '
      . ' WHERE tags.name IN ' . $this->_createSetOfStrings($tags);

      $this->query($query);
  }

  /**
    * Set the tags for a specific object. Will select all the preexistent tags
    * and remove the missing ones and add the extra ones.
    */
  public function setTagsFor($objectid, $tags) {
    $prevTags = $this->getTagsFor($objectid);
    if( !$prevTags ) {
      $prevTags = array();
    }
    $delTags = array_diff($prevTags, $tags);
    $insTags = array_diff($tags, $prevTags);

    $this->deleteTagsFor($objectid, $delTags);
    $this->addTagsFor($objectid, $insTags);
  }

  /**
   * Get all objects with the specific list of tags. 
   * 
   * @param {ARRAY} $tags list of tags
   * @param {STRING} $sortby column by which to be sorted -- default = id
   * @param {BOOLEAN} $and whether or not to require ALL tags -- default = FALSE 
   * @param {BOOLEAN} $ascending whether or not to sort ascending -- default = TRUE
   * @param {INT} $limit limit on the number of responses -- default = FALSE
   * @param {INT} $offset offset from which to start getting -- default = FALSE
   * @return {ARRAY} an array of results
   */
  public function getObjectsFor(
      array $tags,
      $sortby = 'id', 
      $and = FALSE,
      $ascending = TRUE, 
      $limit = FALSE, 
      $offset = FALSE
      ) {
      
    $tagFilterString = ' AND ( tags.name IN ' . $this->_createSetOfStrings($tags) . ' ) ';

    $query = 'SELECT obj.* FROM '
      . $this->objectTable . ' obj, '
      . $this->tagTable . ' tags, '
      . $this->tagMapTable . ' tm '
      . ' WHERE obj.id = tm.objectid '
      . ' AND tm.tagid = tags.id '
      . $tagFilterString;
    
    $query . ' GROUP BY obj.' . $sortby .  ($ascending ? ' ASC ' : ' DESC ');
    if (TRUE === $and) {
    	$query .= ' HAVING COUNT(obj.' . $sortby . ') = ' . count($tags);
    }
    if (FALSE !== $limit) {
      $query .= ' LIMIT ' . $limit;
      if (FALSE !== $offset) {
        $query .= ' OFFSET ' . $offset;
      }
    }

    return $this->_getResult($this->query($query));
  }

  /**
   * Adds the tags to a pre-fetched list of objects, by using their ids
   * to query the required information.
   *
   * @param $results the previously queried results
   * @return the same resulsts but with ['tags'] added to them
   */
  public function getObjectsWithTags( array $results ){
    $ids = array();
    $objects = array();
    foreach( $results as $k => $v ){
      $ids[] = $v['id'];
      $objects[$v['id']] = $v;
    }
    if( !empty( $ids ) ){
      $query = 'SELECT o.id,t.name FROM ' . $this->objectTable . ' o, '
                . $this->tagTable . ' t, ' . $this->tagMapTable . ' tm '
                . ' WHERE tm.objectid = o.id AND tm.tagid = t.id '
                . ' AND o.id IN ' . $this->_createSetOfStrings( $ids );
                
      $arr = $this->_getResult( $this->query( $query ) );
      $tags = array();

      foreach( $arr as $k => $v ){
        if( !isset( $tags[ $v['id'] ] ) ){
          $tags[ $v['id'] ] = array();
        }
        $tags[ $v['id'] ][] = $v['name'];
      }
      
      foreach( $tags as $k => $v ){
        $objects[$k]['tags'] = $v;
      }
      
      return $objects;
    } else {
      return array();
    }
  }
  
}

?>
