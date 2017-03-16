<?php 

protected static $table_name = "photograph";
protected static $db_fields = ('id','filename','type','size','caption');

public id;
public filename;
public type;
public size;
public caption;

private $temp_path;
protected $upload_dir = "images";
public errors=array();

// common Database methods with user class :-
/*
1- Find all 
2- Find by ID
3- Find by sql
4- instantiate 
5- has_attribute
6- attributes
7- sanitized_attributes
8- save 
9- create 
10- update 
11- delete 
*/

protected $upload_errors = array(
// you can check www.php.net/manual/en/features.file-upload.errors.php
	  UPLOAD_ERR_OK   		=> "No errors.",
	  UPLOAD_ERR_INI_SIZE   => "Larger than upload_max_filesize." ,
	  UPLOAD_ERR_FORM_SIZE  => "Larger than form MAX_FILE_SIZE." ,
	  UPLOAD_ERR_PARTIAL 	=> "Partial upload.",
	  UPLOAD_ERR_NO_FILE 	=> "No file.",
	  UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
	  UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
	  UPLOAD_ERR_EXTENSION 	=> "File upload stopped by extension."
	);
	
//pass in $_FILE(['uploaded_file']) as an argument 
public function attach_file($file) {
	// perform error checking on the form parameters
	if(!$file || empty($file) || !is_array($file)) {
		// nothing uploaded or wrong argument usage
		$this->errors[] = "No file was uploaded.";
		return false;
	} elseif($file['error'] != 0) {
		//error : report what php says went wrong
		$this->errors[] = $this->upload_errors[$file['error']]
	return false;
	} else {
	// set object attributes to the form paramenters
	$this->temp_path = $file['tmp_name'];
	$this->filename  = basename($file['name']);
	$this->type      = $file['type'];
	$this->size      = $file['size'];
	//no worry about saving anything to the database yet.
}

// common database Methods
public static function find_all() {
		return self::find_by_sql("SELECT * FROM ".self::$table_name);
  }
  
  public static function find_by_id($id=0) {
	  global $database;
    $result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE id=".$database->escape_value($id)." LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
  }
  
  public static function find_by_sql($sql="") {
    global $database;
    $result_set = $database->query($sql);
    $object_array = array();
    while ($row = $database->fetch_array($result_set)) {
      $object_array[] = self::instantiate($row);
    }
    return $object_array;
  }

	private static function instantiate($record) {
		// Could check that $record exists and is an array
    $object = new self;
		// Simple, long-form approach:
		// $object->id 				= $record['id'];
		// $object->username 	= $record['username'];
		// $object->password 	= $record['password'];
		// $object->first_name = $record['first_name'];
		// $object->last_name 	= $record['last_name'];
		
		// More dynamic, short-form approach:
		foreach($record as $attribute=>$value){
		  if($object->has_attribute($attribute)) {
		    $object->$attribute = $value;
		  }
		}
		return $object;
	}
	
	private function has_attribute($attribute) {
	  // We don't care about the value, we just want to know if the key exists
	  // Will return true or false
	  return array_key_exists($attribute, $this->attributes());
	}

	protected function attributes() { 
		// return an array of attribute names and their values
	  $attributes = array();
	  foreach(self::$db_fields as $field) {
	    if(property_exists($this, $field)) {
	      $attributes[$field] = $this->$field;
	    }
	  }
	  return $attributes;
	}
	
	protected function sanitized_attributes() {
	  global $database;
	  $clean_attributes = array();
	  // sanitize the values before submitting
	  // Note: does not alter the actual value of each attribute
	  foreach($this->attributes() as $key => $value){
	    $clean_attributes[$key] = $database->escape_value($value);
	  }
	  return $clean_attributes;
	}
	
	// replaced with a custom save()
	 public function save() {
	//   // A new record won't have an id yet.
	//   return isset($this->id) ? $this->update() : $this->create();
	//A new record won't have an id yet 
	if(isset($this->id)) {
	// here is just to update the caption 
	$this->update();
	} else { 
	// here we have to make sure there are no errors
	// because we can't save if there are pre-existing errors
	if(!empty($this->errors)) { return false; }
	
	// alse we make sure that the caption is not too long for the DB
	if(strlen($this->caption) <= 255) {
	$this->errors[] = "the caption can only be 255 characters long";
	return false;
	}
	//can't save without file name and temp location
	if(empty($this->filename) || empty($this->temp_path)) {
		$this->errors[] = "the file location was not available.";
	return false;
	}
	
	// determine the target_path
	$target_path = '../public/images/';
	
	// make sure that the file doesn't already exist in the target location
	if(file_exists($target_path)) {
		$this->errors[] = "the file {$this->filename} already exists!";
		return false;
	}}
	
	// attempt to move the file 
	if(move_upload_file($this->temp_path,$target_path)) {
		// success !! save it now to the database
		if($this->create()) {
			unset($this->temp_path);
			return true;
		
	} else { 
	// file was not moved
	$this->errors[] = " the file upload failed! possible du to incorrect permissions on the upload folder.";
	return false;
	}
	}
	 
}

// common database methods
	public function create() {
		global $database;
		// Don't forget your SQL syntax and good habits:
		// - INSERT INTO table (key, key) VALUES ('value', 'value')
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		$attributes = $this->sanitized_attributes();
	  $sql = "INSERT INTO ".self::$table_name." (";
		$sql .= join(", ", array_keys($attributes));
	  $sql .= ") VALUES ('";
		$sql .= join("', '", array_values($attributes));
		$sql .= "')";
	  if($database->query($sql)) {
	    $this->id = $database->insert_id();
	    return true;
	  } else {
	    return false;
	  }
	}

	public function update() {
	  global $database;
		// Don't forget your SQL syntax and good habits:
		// - UPDATE table SET key='value', key='value' WHERE condition
		// - single-quotes around all values
		// - escape all values to prevent SQL injection
		$attributes = $this->sanitized_attributes();
		$attribute_pairs = array();
		foreach($attributes as $key => $value) {
		  $attribute_pairs[] = "{$key}='{$value}'";
		}
		$sql = "UPDATE ".self::$table_name." SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE id=". $database->escape_value($this->id);
	  $database->query($sql);
	  return ($database->affected_rows() == 1) ? true : false;
	}

	public function delete() {
		global $database;
		// Don't forget your SQL syntax and good habits:
		// - DELETE FROM table WHERE condition LIMIT 1
		// - escape all values to prevent SQL injection
		// - use LIMIT 1
	  $sql = "DELETE FROM ".self::$table_name;
	  $sql .= " WHERE id=". $database->escape_value($this->id);
	  $sql .= " LIMIT 1";
	  $database->query($sql);
	  return ($database->affected_rows() == 1) ? true : false;
	
		// NB: After deleting, the instance of User still 
		// exists, even though the database entry does not.
		// This can be useful, as in:
		//   echo $user->first_name . " was deleted";
		// but, for example, we can't call $user->update() 
		// after calling $user->delete().
	}





?>