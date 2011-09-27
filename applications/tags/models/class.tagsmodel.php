<?php

class TagsModel extends Geek_Model {
  protected $_tagsTable;
  protected $_tagMapTable;
  public function __construct($tableName) {
    parent::__construct($tableName);
    $this->_tagsTable = $this->_tableName . "_tags";
    $this->_tagMapTable = $this->_tableName . "_tagmap";
    $this->createTables();
  }

  protected function createTables() {
    $createTags   = "CREATE TABLE IF NOT EXISTS " 
      . $this->_tagsTable
      . " ( "
      . " id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, "
      . " name VARCHAR(30) NOT NULL UNIQUE KEY, "
      . " description TINYTEXT NOT NULL "
      . " )";
    $createTagMap = "CREATE TABLE IF NOT EXISTS " 
      . $this->_tagMapTable 
      .  " ( "
      . "id INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(id), "
      . " objectid INT NOT NULL, "
      . " tagid INT NOT NULL, "
      . " KEY (objectid), "
      . " KEY (tagid), "
      . " UNIQUE KEY uq_key (objectid, tagid),  "
      . " CONSTRAINT fk_tag FOREIGN KEY(tagid) REFERENCES " 
      . $this->_tagsTable . "(id) "
      . " ON UPDATE CASCADE ON DELETE CASCADE, " 
      . " CONSTRAINT fk_object FOREIGN KEY(objectid) REFERENCES " 
      . $this->_tableName . "(id) "
      . " ON UPDATE CASCADE ON DELETE CASCADE "
      . " )";
    mysql_query($createTags) or die(mysql_error());
    mysql_query($createTagMap) or die(mysql_error());
  }

  /**
    * For development purposes now.
    * @param $tags an array of arrays of "name" and "description"
    */
  public function createTags($tags) {
    $query = "INSERT INTO " 
      . $this->_tagsTable
      . " (name, description) VALUES ";
    $values = "";
    foreach($tags as $ind => $entry) {
      $name = mysql_real_escape_string($entry["name"]);
      $description = mysql_real_escape_string($entry["description"]);
      $values .= " (\"$name\", \"$description\"), ";
    }
    
    if ("" !== $values) {
      $values = substr($values, 0, -2);
    }

    $query .= $values;
    mysql_query($query) or die(mysql_error());
  }

  /**
    * For development purposes now.
    * @param $tags an array tag names
    */
  public function destroyTags($tags) {
      $query = "DELETE tags.* FROM "
        . $this->_tagsTable . " tags "
        . "WHERE tags.name IN " . $this->_createSetOfStrings($tags);
      mysql_query($query) or die(mysql_error());
  }

  /**
    * Add a specific set of tags to an object.
    * @param $objectid the object to attach tags to
    * @param $tags an array of tag names 
    */
  public function getTagsFor($objectid) {
    $objectid = mysql_real_escape_string($objectid);
    $query = "SELECT tags.name FROM "
      . $this->_tagMapTable . " tm, "
      . $this->_tagsTable . " tags "
      . " WHERE tm.objectid = $objectid "
      . " AND tags.id = tm.tagid ";  
    $result = mysql_query($query) or die(mysql_error());
    $tags = array();
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $tags[] = $row["name"];
    }
    mysql_free_result($result);
    return $tags;
  }

  /**
    * Delete specific tags for a specific object
    * @param $objectid the object to remove tags from
    * @param $tags the tag strings to remove
    */
  public function deleteTagsFor($objectid, $tags) {
    $objectid = mysql_real_escape_string($objectid);
    $query = "DELETE tm.* FROM "
      . $this->_tagMapTable . " tm "
      . " LEFT JOIN " . $this->_tagsTable . " tags "
      . " ON tags.id = tm.tagid "
      . " WHERE tm.objectid = $objectid "
      . " AND tags.name in ";

    $query .= $this->_createSetOfStrings($tags);
    mysql_query($query) or die(mysql_error());
  }

  /**
    * Add specific tags for a specific object
    * @param $objectid the object to attach to
    * @param $tags an array of tag strings to attach
    */
  public function addTagsFor($objectid, $tags) {
    $objectid = mysql_real_escape_string($objectid);
    $query = "INSERT INTO "
      . $this->_tagMapTable
      . " (objectid, tagid) "
      . " SELECT $objectid, tags.id "
      . " FROM " . $this->_tagsTable
      . " WHERE tags.name IN " . $this->_createSetOfStrings($tags);
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

  public function getObjectsFor(
      $tags, 
      $sortby = "id", 
      $ascending = TRUE, 
      $limit = FALSE, 
      $offset = FALSE
      ) {
    $sortby = mysql_real_escape_string($sortby);
    $ascending = mysql_real_escape_string($ascending);
    $limit = mysql_real_escape_string($ascending);
    $offset = mysql_real_escape_string($offset);
    $query = "SELECT obj.* FROM "
      . $this->_tableName . " obj, "
      . $this->_tagsTable . " tags, "
      . $this->_tagMapTable . " tm "
      . " WHERE obj.id = tm.objectid "
      . " AND tm.tagid = tags.id "
      . " AND ( tags.name IN " . $this->_createSetOfStrings($tags) . " ) "
      . " GROUP BY obj.$sortby " . ($ascending ? "ASC" : "DESC")
      . " HAVING COUNT(obj.$sortby) = " . count($tags);
    if (FALSE !== $limit) {
      $query .= " LIMIT " . $limit;
      if (FALSE !== $offset) {
        $query .= " OFFSET " . $offset;
      }
    }

    $objects = array();
    $result = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $objects[] = $row;
    }
    mysql_free_result($result);
    return $objects;
  }

  /**
    * Creates a set of strings to be used with IN
    *
    * @example ( "c++", "java", "bash" ) from the respective array
    *
    * @param $strings the array of strings we want to use
    */
  private function _createSetOfStrings($strings) {
    $set = "";
    foreach ($strings as $string) {
      $string = mysql_real_escape_string($string);
      $set .= " \"$string\", ";
    }

    if ("" !== $set) {
      $set = substr($set, 0, -2);
    }

    $set = " ( " . $set . " ) ";

    return $set;
  }
}

?>
