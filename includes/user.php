<?php
require_once("database.php");  // it's smart to include database.php file since we need it to manupulate with the user class 

class User {
	protected static $table_name = "users";
	// what we need to return the only attributes that present the database field names (id,firstname,lastname,... by create an array and put inside it all these 
	// and then check them with class properties 
	protected static $db_fields = array('id','username','password','first_name','last_name');
	public $id;
	public $username;
	public $password;
	public $first_name='Sofia';
	public $last_name='Akram';

      public static function find_all() {     // create new function for all users query 
		  global $database; // here we use the global $database variable to refere the global (defiened outside the Mysql_database class)
		  $sql = "select * from ".self::$table_name;    // use normal query to select all the columns from users table 
		  $resault = self::find_by_sql($sql);     // here we pass the query string to query function inside $database instance class 
		  return $resault;   // return the resault from the query of the database to the caller in index file 
		// all the above 3 lines can be convert to one line 
		// return self::find_by_sql("select * from users");
      }

      public static function find_by_id($id=0) {      // here defiene new function for the query type by user Id number 
		  global $database;    // also define the global instance of Mysql_database class 
		  $sql = "select * from ".self::$table_name." where id = {$id}" ; // use the sql quers sentence to select the specific user by ID number 
		  $resault = self::find_by_sql($sql); // here we pass the sql query sentence to query() function in mysql_database class 
		  // $found = $database->fetch_array($resault);   // fetch the resault in (found) variable (it's better to return the specific row from inside the function) 
		  // return $found; 
		  return !empty($resault) ? array_shift($resault) : false;

      }

    public static function find_by_sql($string="") {   	// defiene a new function for the other kind of queries like sub-queries 
		  global $database;      // also defiene the global instance to deal with the connections of the Mysql_database class 
		 // $sql = $database->Mysql_prep($string);   // use the preparation function to check if the sql sentence is suitable to deal with database 
		  $resault = $database->query($string); // pass the sql sentence to the query function inside database and return the resault as an array (which could contain row or rows!!)
		  $object_array= array();
		  while ($row = $database->fetch_array($resault)) {
			  $object_array[] = self::instantiation($row);
			}
	
		  return $object_array;
	  }
	public static function authenticate($username = "", $password = "") {
		global $database; //brings in the database from global scoop (instance defined in Mysql_databse class 
		 // $username=$database->Mysql_prep($username);
		 // $password = $database->Mysql_prep($password);
		 
		$sql="SELECT * FROM users WHERE username = '{$username}' AND password = '{$password}' LIMIT 1";
		
		$resault= self::find_by_sql($sql);
				
		return !empty($resault) ? array_shift($resault) : false;
	}
// ****************************************************** Abstracting Database *******************************************
// Is making the database methods (CRUD Operations) of user class for example reusabl by the other classes and this will apply by 2 steps 
//1- Abstracting Database Name : by create protected static method called - $table_name = "user" and replace all the table names inside the sql 
// statments with this variable by using ".self::$table_name."  that will work for abstract table name 
// 2- Abstract database Attributes : in the attributes we have a list of attributes of Keys and a list of attributes of values so we need 
	public function create_user() {
		global $database;
	//good sql habits to keep in mind:
	// 1- INSERT INTO table(key,key) VALUES ('value','value');
	// 2- single qoute around values
	// 3- use escape method (Mysql_prep) to prevent sql injection 
	$sql = "INSERT INTO ".self::$table_name." (join(", ", array_keys($attributes))) VALUES ('join("', '",array_value($attributes))')";
	// I know doing the sql statment in one line it's not very cool !! but for now I will doing it becouse it's clear to me to undestand 
	// ah keep in mind the way I used to cll the Mysql_prep and pass the public attributes of User class 
	if($database->query($sql)) {
		$this->id = $database->insert_id(); // it's important to update the ID attribute of the object -- the ID in the database is auto increment but the ID object need to update manually
		return true; // no need to pass a message it's just true or false
	} else {
		return false;
	}
	}
	
	// create a new method to find the database attributes inside the class or the object 
	// here we use the abstract Database techniqes to reuse the methods (CRUD) of user class in other class and that's by abstract 1- the databse names
	// and 2- abstract the database attributes (with values)
	 protected function attributes() {
		 
		 // come through each element of this array and check it with the class property if its exsist ?
		 foreach (self::$db_fields as $field) {
		 if(property_exisit($this,$field) {
		 $attributes[$field] = ['$this->filed'];
		 }
		 return attributes;
		 }		 
		 
	 }
	public function update() {
	global $database;
	//good sql habits to keep in mind:
	// 1- UPDATE user set key='value' ,key= 'value' WHERE condition ;
	// 2- single qoute around values
	// 3- use escape method (Mysql_prep) to prevent sql injection 
	$attributes = $this->attributes();  
	$attribute_pairs = array();
	foreach($attributes as $key=> $value) {
		$attribute_pairs[]= "{$key} = '{$value}'";
	}
	$sql= "UPDATE ".self::$table_name." SET join(", ",$attribute_pairs) where id=". $database->Mysql_prep($this->id); 
	
	$database->query($sql);
	return ($database->effected_rows() == 1) ? true : false; // updating is not like create to check if query happend true or false ther is another function to check if 
	// there is any effects happend on the rows at least 1 effect !
	}
	public function save() {    // here create smart method to decide which function to implement and this could check by the id if the id excit then 
	// mean that it#s update and if not that's is create 
	//a new record won't have an Id yet 
		return isset($this->id) ? $this->update() : $this->create_user() ;
	}
	
	public function delete_user() {
		global $database;
	//good sql habits to keep in mind:
	// 1- Delete FROM table where condition;
	// 2- single qoute around values
	// 3- use escape method (Mysql_prep) to prevent sql injection 
	$sql = "DELETE FROM users where id =" . $this->id . " LIMIT 1";
	$database->query($sql);
	return ($database->effected_rows() == 1) ? true : false;
	}
      
	  public static function full_name($record) {
		 $return_object=self::instantiation($record);
		 // if (isset(self::first_name) && isset(self::last_name)) {
		 return $return_object->first_name . " " . $return_object->last_name;
		  //} else 
	  }
	  
// ******************************************************+++++ Instantiating User Object +++++***********************************************
// what does that mean ?? it means taking all the columns inside that row in  mysql user table and assign all these values to the correct attributes to User class 
// why we would like to make all the row elements of user table to be an attribute ?? So we can perform more complex tasks with an objects then just a text in an array, 
//there will be a lot of complexity built in the objects 
// so for insantiating user objects we can use a PRIVATE method because we don't want to access it from the outside of the class and we will make it static 

//*********************************************************** the First way (simple & long way for many columns) ****************************

	  private static function instantiation($record) { // this is the long way to assign colums of a selected record to an attributes and returnd as an object 
													   // and it is not practical if there are many colums inside the table and there is another way by using functions and loop
		  $object = new self;  // here is making new object from the same class User by refer to it as (self) 
		  
		   $object->id         = $record['id'];
		   $object->username   = $record['username'];
		   $object->password   = $record['password'];
		   $object->first_name = $record['first_name'];
		   $object->last_name  = $record['last_name'];
		   return $object;
		  
		    
// ************************************************************ the second way (short way for many columns) *************************
	/*foreach($record as $attribute=>$value)  {
		if($object->has_attribute($attribute)) {
			$object->$attribute = $value;
		}
	}
	 return $object; 
	  }

		private function has_attribute($attribute) {
		$object_var = get_object_vars($this);       // here we will use the new function - get_object_vars ) which use to return an associative array 
												    //with all attributes (including the private onse!) as the keys and their current values as the value 
			return array_key_exists($attribute,$object_var); // to check if the attribute (passed by the caller) is exists in $object_var array or not (return true OR false) 
															// we don't care about the value we just want to know if the key is excite or not ...
		}*/
}
}



?>