<?php
// ************************************** Session Class *********************
// session class is going to maintain on the web server as a file, and what we going to do is get the cookie set automatically by the browser in that file we can sort things like 
// user satate (usefull to know whether or not logged in , this person I already have his ID and I marked them by session file) so every sesion they make we just check the session file 
// and not need to goback to the database 
// this class session we create is to manage logg-in users and out 
// keep in mind that it's not a profissional to store DB-related object in session file 
//cookies use to store the user state without cookies web page not recognize if multiple request come from the same user


class Session {  // creating session class 
		private $logged_in=false; // whether someone is logged in (flag) - private inside the class (only the session check of some one logged in or not)
		public $user_id;    // flag for the user ID and it will be public to ask about it from out the class 
// 1 - increating the session constractor function 

		function __construct() {
			session_start();  // what is doing is start the session by figer out the cookie for this ID and if it's not find(visit first time) create new one 
			$this->checked_loggin(); // at the begining check the status of logg-in 
		}
		
// 2- private method to check if the user logged in before or not 		
		private function checked_loggin () {
			if(isset($_SESSION['user_id'])) { // ask the session if the user ID is set 
				$this->user_id = $_SESSION['user']; // assign this user ID to the variable user_id
				$this->logged_in = true; // make the flag for logg-in true 
			} else {
				unset($this->user_id); // if it's faild to define that _SESSION['user_id'] then unset that value 
				$this->logged_in = false; // and make logg-in to false 
			}
			
		}
// 3- a public function to check if the person logged in or not 
		
		public function is_logged_in() {
			return $this->logged_in;
		}
		
// 4- public function take a user ID as an argument and check the database if the password is match and if it's match mark the user as logged_in
		public function log_in($user) {
		if($user) {
		$this->user_id = $_SESSION['user_id'] = $this->user_id;
		$this->logged_in = true;
		}
		}
// 5- we need the users to logged them out as well so we will need another function 
		public function logout() { 
		unset($_SESSION['user_id']); // un set the user session by the user Id 
		unset($this->user_id); //unset the user ID 
	$this->logged_in = false; // and flag the logged_in as false 
}


















}	 


$session = new Session(); // like the database class we need our session class to be exist right a way 


?>
