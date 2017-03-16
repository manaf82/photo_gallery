<?php
require_once("../includes/database.php");
require_once("../includes/user.php");

$User = new User(); // define new instance for user class 


$user = User::find_by_id(1); //using static modifier / defeine $found_user variable to recieve the resault of method find_by_id(user number);
//echo $user->full_name();
// echo $found_user['username']; // print out the value of username  
// echo "<hr />";

// ****************************** First way *******************
// $returned_object=User::instantiation($found_user); // here we make the instantiation function public I can call it from idex file (take the record as an array and give it to me back as an object)
// echo $User->full_name($returned_object); // take the new object and send it to full_name function and give me back the (first_name and the last_nama) as an attribures ! 
// here I will use another way to define instantiation function as a private and call it from inside the full_name function 

//********************** second way **********************
echo User::full_name($user);


// if(isset($database)) { // checking if the database in the config file is set by using the (isset) function to return boolean value if true or false 
	// echo "true";
// } else {
// echo "false";
// }
// echo "<br />";
// echo $database->Mysql_prep("It'S Not Working!"); // here we call the method (Mysql_prep) to prepare and make sure that the txt send to the query is valid and suitable

 // $sql = "Insert into users(id,username,password,first_name,last_name) Values (1,'manaf','abdullah','Munaf','Albayati')"; // the sql statemnet to insert value in my Database 
 // $resault = $database->query($sql);  // use the object of database of class Mysql_Database to refer the method query to pass the sql statment to pass it to the query function and the resault save it in $readdir
 // // after we insert in the database we need to view the inserted table by select statemnet by same sql variable
 // $sql = "select * from users where id = 1";
 // $resault= $database->query($sql);
 // $found_user = $database->fetch_array($resault);
 // echo $found_user['username'];

?>