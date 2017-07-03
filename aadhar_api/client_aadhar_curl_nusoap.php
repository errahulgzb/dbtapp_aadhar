<?php  
error_reporting(1);
set_time_limit(0);

ini_set("error_reporting","E_ALL");
ini_set("error_display",true);
require_once('lib/nusoap.php');  // new soap locactiom
//include_once 'aadharStatusRequest.class.php';


$client = new nusoap_client("http://192.168.100.132/dbtapp_aadhar/wsdl/AadhaarStatus_npcinet.wsdl",true);

if($err){
    echo "error=".$err; 
} 

$request_param = array('arg0'=>array(
    "aadhaarNumber" => '390494144253',
    "mobileNumber" => '9899666906',
    "requestNumber" => 'SBIF000001',
    "requestedDateTimeStamp" => '2016-04-28 17:08:25.064'
));

//echo "<pre>";print_r($request_param);die;

$result=$client->call('getAadhaarStatus', $request_param);
if($client->fault)
{die("sdjchsu");
	echo '<pre>Some fault…!!!</pre>';    
} else{    
  $err = $client->getError();
	if($err) { 
			//die("sdjchsu12");
		echo '<h2>Error</h2><pre>' . wordwrap($err,100,"\n") . '</pre>';}
	else
     {
		echo '<pre>';
		print_r($result);
		echo '</pre>';
	}
}
?>