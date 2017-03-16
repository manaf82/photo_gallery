<?php
// Database Constant : constant can only be defined one time ,so it's a good habbit to check 
// whether it's defined befor or not .
// I use the constant to connect the Database 
defined('DB_SERVER') ? null : define("DB_SERVER","localhost");
defined('DB_USER') ? null : define("DB_USER","gallery");
defined('DB_PASS') ? null : define("DB_PASS","manaf123");
defined('DB_NAME') ? null : define("DB_NAME","photo_gallery");

?>