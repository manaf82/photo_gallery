<?php
//require_once("config.php");
//require_once("initialize.php"); // includes all the 
/*
++++++++++++++++++ use the srtacture method to connect the DataBase ++++++++++++++
1- create a database connection :-
$connection = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
if(mysqli_connect_errno()) {
die("DataBase connection failed: ".mysqli_connect_error()."(".mysqli_errno() . ")");
}
**************************************
2- perform database Query
$sql = "select * from subjects";
$result = mysqli_query($connection,$sql);
if(!$result) {
	die("Dataase query faild.);
	
}

*************************************
3- use retrned data 
while ($row = mysqli_fetch_array($result)) {
	// output data 
}
*************************************
4- close connection 
if(isset($connection)) {
	mysqli_close($connection);
	unset($connection);
}
*/ 
//////////////////////////////////////////////////////////////////////////////////////////////////////

// *************************** Use the OOP methods to connect the Database ************************//
// 1- define variables for database connection and queries I call it (MysqlDatabase)
class MysqlDatabase {
	
    private $connection ; // by define this variable as a private now can take scoop for everywhere inside the class 

	// create constractor method (which call automatically every time create an object which is here open_connection
	function __construct() {
	$this->open_connection();
	}
	// 2- define a function for databse open connection 	
	public function open_connection() {
	$this->connection = mysqli_connect("localhost","gallery","manaf123","photo_gallery"); // $connection is a local variable we can't use it in other functions 
	// but we can use $this to change it to an attribute to hold the connection 
	if(mysqli_connect_errno()) {
	die("DataBase Connection faild: " . mysqli_connect_error() ."(".mysqli_connect_errno() . ")");
		}
	}
	// 3- create close_connection function 
	function close_connection() {
		if (isset($this->connection)) {
			mysqli_close($this->connection);
			unset($this->connection);
		}
		
	}
	// 4- because the query for the database will be diferant for each time so there is no need to put it inside the class isteade we pass it to the function and put the result in $resault variable
 
	function query($sql) {
		$resault = mysqli_query($this->connection,$sql);
		$this->confirm_query($resault);  // pass the resault to confirm_query function 
		return $resault;
	}
	
	// 4- we make a confirm function (helper function) to confirm if the query made correctly or not by checking the reault 
 
	private function confirm_query($resault) {    // it doesn't need to call from outside the class only from inside to check the validation of the query
	if(!$resault) {
			die("Database query faild!");
		} 
	}
	
	// 5- use function preperation the value for sql query to make sure that the value is clean and accepted for the use inside the mysql, we call this function from outside the class so we would use public 
	public function Mysql_prep($string) {
		$escaped_string = mysqli_real_escape_string($this->connection,$string); 
		return $escaped_string;
	}
	 
	// database netural functions - it's use in case we have multiple database use in the web so our databse can play as a netural adabter to process other databases

	public function fetch_array($resault) { // fetch ,to get the resault from the database
		return mysqli_fetch_array($resault);
	}
	
	public function num_rows($resault) { // how many rows effected by the last sql statment (nethral function)
		return mysqli_num_rows($resault);
	}
	
	public function insert_id() { //the last id inserted over the databse connection 
		return mysqli_insert_id($this->connection);
	}
	
	public function effected_rows() { // how many rows were effected by the last sql 
		return mysqli_affected_rows($this->connection);
	}
	
}

$database = new MysqlDatabase(); // create an object for the MysqlDatabase class which can be called from index file and be used in case we include this file in index file and it work like one file
// $database->open_connection(); no need for call open_connection since we create a constract function which will call open_connection everytime we create new object 

?>