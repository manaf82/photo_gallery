<?php
require_once("../../includes/functions.php");
require_once("../../includes/user.php");
//if(!$session->is_logged_in()) { redirect_to("login.php"); }
?>

<?php
 $user = new User();
 //*************************************Create *************************************
// $user->username = "Michel";
// $user->password = "M12345";
// $user->first_name = "Marcos";
// $user->last_name = "Karmos";
// $user->create_user();  // call the method to create new user 


//**************************************** UPDATE ************************
 // $user=User::find_by_id(2);
 // $user->password = "wx2763";
 // $user->save();   // here call the function to update the user password and since the ID fro this user it's avaliable so it'll choose the update method 
 
 //****************************************DELETE *******************************************
 // $user->User::find_by_id(2);
 // $user->delete_user();   // call the function of delete user

?>