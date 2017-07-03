<?php
/*
if (!isset($_SERVER['PHP_AUTH_USER'])) {
header('WWW-Authenticate: Basic realm="My Realm"');
header('HTTP/1.0 401 Unauthorized');
echo 'Please put your username and password for access this file.';
exit;
} else {
if($_SERVER['PHP_AUTH_USER']=='admin' && $_SERVER['PHP_AUTH_PW']=='DBTPortal!123$'){
}else{
header('WWW-Authenticate: Basic realm="My Realm"');
header('HTTP/1.0 401 Unauthorized');
echo 'wrong username and password for access this file.';
exit;
}
}
*/
/*
if (!isset($_SERVER['PHP_AUTH_USER'])) {
header('WWW-Authenticate: Basic realm="My Realm"');
header('HTTP/1.0 401 Unauthorized');
echo 'Please put your username and password for access this file.';
exit;
} else {
if(($_SERVER['PHP_AUTH_USER']=='admin' ||$_SERVER['PHP_AUTH_USER']=='pravinsomra') && ($_SERVER['PHP_AUTH_PW']=='DBTPortal!123$' || $_SERVER['PHP_AUTH_PW']=='dbt3')){
}else{
header('WWW-Authenticate: Basic realm="My Realm"');
header('HTTP/1.0 401 Unauthorized');
echo 'wrong username and password for access this file.';
exit;
}
}



//$con = mysql_connect("10.247.47.38:3306","dbtuser","dbtuser"); 
if(!$con)
{
	//die("could not connect".mysql_error());
}*/
//mysql_select_db($con,"dbt") or ;
//die;
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();