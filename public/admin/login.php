<?php
//require_once("../../includes/config.php");
require_once("../../includes/functions.php");
require_once("../../includes/database.php");
require_once("../../includes/user.php");
require_once("../../includes/session.php");

if($session->is_logged_in()) {  // if the user is already logged in and has cookie and id 
	redirect_to("index.php");    // directly go to the index.php page ( inside the admin control panel)
}

// if not (the user not logged in before and don't have any ID 
// create a new session 
if(isset($_POST['submit'])) { // here we check if the user submitted the button to log in (name = submit)
	$username = trim($_POST['username']);   // we take the username by post from the textbox and trime it and assign it to username variable 
	$password = trim($_POST['password']); // we take the password from the text and trime it to and assign it to password variable
	
//check the database to see if username/password exist 

	 $found_user= User::authenticate($username,$password);

    if($found_user) {
	 $session->log_in($found_user);
	 echo " there is no resault";
	 redirect_to("index.php");
	} else {  // username/password was not found in database
	 $message = "Username/password combination incorrect.";
	 }
 } else { // form has not been submitted.
 $username = "";
 $password = "";
 }

?>
<html>
<head>
<title >Photo Gallery</title>
<link href="../stylesheets/main.css" media="all" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="header">
<h1>Photo Gallery</h1>
</div>
<div id="main">
<h2>Staff Login</h2>
<?php echo output_message($message); ?>
<form action="login.php" method="post">
<table>
<tr>
<td>Username: </td>
<td>
<input type="text" name="username" maxlength="30" placeholder="User Name" value="<?php echo htmlentities($username); ?>" />
</td>
</tr>
<tr>
<td>Password: </td>
<td>
<input type="password" name="password" maxlength="30" placeholder="Password" value="<?php echo htmlentities($password); ?>" />
</td>
</tr>
<tr>
<td colspan="2">
<input type="submit" name="submit" value="login" />
</td>
</tr>
</table>
</form>
</div>
<div id="footer">Copyright <?php echo date("Y",time()); ?>, Munaf Albayati</div>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>
