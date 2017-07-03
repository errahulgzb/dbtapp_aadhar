<?php  
error_reporting(1);
ini_set("error_reporting","E_ALL");
ini_set("error_display",true);
require_once('lib/nusoap.php');  // new soap locactiom
//create soap client pass location of the coursecreation.php
$options = array(
               'soap_version'=>SOAP_1_1,
                'exceptions'=>true,
                'trace'=>1,
                'cache_wsdl'=>WSDL_CACHE_NONE,
                ); 

$client = new nusoap_client("https://103.14.161.34/CMAadhaar/AadhaarStatusService/AadhaarStatusService.wsdl",true);
$err = $client->getError();
if($err){ 
    echo $err; 
} 
$params = array('aadhaarNumber'=>'123456789012','mobileNumber'=>'1234567890','requestNumber'=>'ABCD000001','requestedDateTimeStamp'=>'2015-03-10 14:47:47.741');
rue);
$result=$client->call('getAadhaarStatus', $params); // client calling the method with 
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