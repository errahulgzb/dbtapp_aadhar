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






// if($client->fault)
// {echo '<pre>Some fault…!!!</pre>';    }
// else
// {    
  // $err = $client->getError();
	// if($err) {  echo '<h2>Error</h2><pre>' . $err . '</pre>';}
	// else
     // {
	// echo '<pre>';
	// print_r($result);
	// echo '</pre>';
	// }
// }
die;
//##################################################### Params #################

			$options = array(
               // 'soap_version'=>SOAP_1_1,
                //'exceptions'=>true,
                //'trace'=>1,
                'cache_wsdl'=>WSDL_CACHE_NONE,
                ); 
//die("hewhd");
//$client = new SoapClient("http://180.151.3.101/dbtapp_aadhar/application/dbtappservice/AadhaarStatus_npcinet.wsdl",$options);

$client = new SoapClient("https://nach.npci.org.in/CMAadhaar/AadhaarStatusService/AadhaarStatusService.wsdl");

  try{
echo "<pre>";
var_dump($client);
echo "</pre>";
//die("connection");
//$data = $client->getFunction();
		//var_dump($data);
//die;
	  
		
		
		// echo "<pre>"; print_r($response);exit;
		// echo $response;exit;
  //} catch(SoapFault $client){
  } catch(Exception $e){
//die("abhishek");
		//echo $e->getMessage();die("not executing");
		$data = $client->getFunction();
		var_dump($data);
		// echo '<pre dir="ltr">';
		 //print_r($client->getMessage());
		 //echo '</pre>';
	}
	
  
?>