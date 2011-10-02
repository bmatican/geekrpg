<?php

class TagModel extends Geek_Model {
  public $_tagTable;
  public $tagMapTable;
  public function __construct($tableName) {
    $this->_tagTable = $tableName . '_tags';
    $this->tagMapTable = $tableName . '_tagmap';
    parent::__construct($tableName);
  }

  /**
   * @override
   */
  protected function _createTables() {
    $createTags   = 'CREATE TABLE IF NOT EXISTS ' 
      . $this->_tagTable
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
      . $this->_tagTable . '(id) '
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
      . $this->_tagTable
      . ' (name, description) VALUES ';
    $values = '';
    foreach($tags as $ind => $entry) {
      $values .= ' ( "' . $entry['name'] . '", "' . $entry['description'] . '"), ';
    }
    
    if ('' !== $values) {
      $values = substr($values, 0, -2);
    }

    $query .= $values;
    $this->query($query);
  }

  /**
    * For development purposes now.
    * @param $tags an array tag names
    */
  public function destroyTags($tags) {
      $query = 'DELETE tags.* FROM '
        . $this->_tagTable . ' tags '
        . 'WHERE tags.name IN ' . $this->_createSetOfStrings($tags);
        
      $this->query($query);
  }

  /**
    * Add a specific set of tags to an object.
    * @param $objectid the object to attach tags to
    * @param $tags an array of tag names 
    * @return {ARRAY} an array of 'name' => 'tag' arrays 
    */
  public function getTagsFor($objectid) {
    $query = 'SELECT tags.name FROM '
      . $this->tagMapTable . ' tm, '
      . $this->_tagTable . ' tags '
      . ' WHERE tm.objectid = $objectid '
      . ' AND tags.id = tm.tagid ';  
    
    return $this->_getResult($this->query($query));
  }

  /**
    * Delete specific tags for a specific object
    * @param $objectid the object to remove tags from
    * @param $tags the tag strings to remove
    */
  public function deleteTagsFor($objectid, $tags) {
    $query = 'DELETE tm.* FROM '
      . $this->tagMapTable . ' tm '
      . ' LEFT JOIN ' . $this->_tagTable . ' tags '
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
      . ' (objectid, tagid) '
      . ' SELECT $objectid, tags.id '
      . ' FROM ' . $this->_tagTable
      . ' WHERE tags.name IN ' . $this->_createSetOfStrings($tags);
      
    $this->query($query);
  }

  /**
    * Set the tags for a specific object. Will select all the preexistent tags
    * and remove the missing ones and add the extra ones.
    */
  public function setTagsFor($objectid, $tags) {
    $prevTags = $this->getTags($objectid);
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
      $tags, 
      $sortby = 'id', 
      $and = FALSE,
      $ascending = TRUE, 
      $limit = FALSE, 
      $offset = FALSE
      ) {
    $query = 'SELECT obj.* FROM '
      . $this->tablename . ' obj, '
      . $this->tagTable . ' tags, '
      . $this->tagMapTable . ' tm '
      . ' WHERE obj.id = tm.objectid '
      . ' AND tm.tagid = tags.id '
      . ' AND ( tags.name IN ' . $this->_createSetOfStrings($tags) . ' ) '
      . ' GROUP BY obj.$sortby ' . ($ascending ? 'ASC' : 'DESC');
    if (TRUE === $and) {
    	$query .= ' HAVING COUNT(obj.$sortby) = ' . count($tags);
    }
    if (FALSE !== $limit) {
      $query .= ' LIMIT ' . $limit;
      if (FALSE !== $offset) {
        $query .= ' OFFSET ' . $offset;
      }
    }

    return $this->_getResult($this->query($query));
  }
}

?>
