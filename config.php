<?php 

$localdbinfo = './demol_database_info.php';
$onlinedbinfo = './../demol_database_info.php';

if (file_exists($localdbinfo)) {
  include $localdbinfo;
} else {
  include $onlinedbinfo;
}

/* DATABASE SETTINGS */
// Database hostname, don't change this unless your hostname is different
define('db_host',$servername);
// Database username
define('db_user',$username);
// Database password
define('db_pass',$password);
// Database name
define('db_name',$database);

?>