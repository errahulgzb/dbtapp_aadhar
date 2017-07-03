<?php  
error_reporting(1);
ini_set("error_reporting","E_ALL");
ini_set("error_display",true);
require_once('lib/nusoap.php');  // new soap locactiom
//create soap client pass location of the coursecreation.php

//Aadhar_Status_internet
//Aadhar_Status_internetWSDL
//AadhaarStatus_npcinetWSDL_1

//$client=new nusoap_client('http://180.151.3.101/dbtapp_aadhar/application/dbtappservice/Aadhar_Status_internetWSDL.wsdl', true);

$client=new nusoap_client('https://nach.npci.org.in/CMAadhaar/AadhaarStatusService/AadhaarStatusService.wsdl', true);
//$client=new nusoap_client('https://103.14.161.34/CMAadhaar/AadhaarStatusService/AadhaarStatusService.wsdl', true);
$result=$client->call('getAadhaarStatus', array('aadhaarNumber'=>'123456789012', 'mobileNumber'=>'1234567890', 'requestNumber'=>'ABCD000001', 'requestedDateTimeStamp'=>'2015-03-10 14:47:47.741')); // client calling the method with passing paramert
if($client->fault)
{echo '<pre>Some fault…!!!</pre>';    }
else
{    
  $err = $client->getError();
	if($err) {  echo '<h2>Error</h2><pre>' . $err . '</pre>';}
	else
     {
	echo '<pre>';
	print_r($result);
	echo '</pre>';
	}
}


  
?>