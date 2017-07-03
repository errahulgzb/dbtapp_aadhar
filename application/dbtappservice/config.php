<?php 
GLOBAL $db;
	try {
		$db = new PDO('mysql:host=localhost;dbname=dbtappnew', "root", "password");
			//echo "connected";exit;
	} catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
	}
?>