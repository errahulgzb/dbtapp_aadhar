<?php
require 'library/securimage/securimage.php';
$vercode = $_GET['vercode'];
$img = new Securimage();
$valid = $img->check($vercode);
if($valid == "true"){
	echo true;
}
else{
	echo false;
}
?>