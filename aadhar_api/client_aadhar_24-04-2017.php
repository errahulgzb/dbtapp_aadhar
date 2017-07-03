<?php
libxml_disable_entity_loader(false);
ini_set("soap.wsdl_cache_enabled", "0");
 include_once 'aadharStatusRequest.class.php';
 include_once 'xmlParams.class.php';

 ///$objGetaadhar = new AadhaarStatus('929925880342','9912112097','DBTM000001','2016-04-28 17:08:25.064');
 // $objGetaadhar->aadhaarNumber = '929925880342';
 // $objGetaadhar->mobileNumber = '9912112097';
 // $objGetaadhar->requestNumber = 'DBTM000001';
 // $objGetaadhar->requestedDateTimeStamp = '2016-04-28 17:08:25.064';
 //$objGetaadhar->xmlParams = new xmlParams();
//$objGetaadhar->aadhaarNumber = '929925880342';
//echo "<pre>";print_r($objGetaadhar);echo "</pre>";

$opts = array(
    'ssl' => array('ciphers'=>'RC4-SHA', 'verify_peer'=>false, 'verify_peer_name'=>false)
);
// SOAP 1.2 client
$soap_options = array ('encoding' => 'UTF-8', 'verifypeer' => false, 'verifyhost' => false, 'soap_version' => SOAP_1_2, 'trace' => 1, 'exceptions' => 1, "connection_timeout" => 180, 'stream_context' => stream_context_create($opts),'cache_wsdl'=>WSDL_CACHE_NONE);




try
{
			// $soap_options = array(
                // 'soap_version'=>SOAP_1_0,
                // 'exceptions'=>true,
                // 'trace'=>1,
				// 'stream_context'=>$opts,
                // 'cache_wsdl'=>WSDL_CACHE_NONE,);
//$wsdl   = "https://nach.npci.org.in/CMAadhaar/AadhaarStatusService/AadhaarStatusService.wsdl";
$wsdl   = "https://103.14.161.34/CMAadhaar/AadhaarStatusService/AadhaarStatusService.wsdl";
$client = new SoapClient($wsdl, $soap_options);
echo "aaaa";exit;
$request_param = array('arg0'=>array(
    "aadhaarNumber" => '929925880342',
    "mobileNumber" => '9912112097',
    "requestNumber" => 'DBTM000001',
    "requestedDateTimeStamp" => '2016-04-28 17:08:25.064'
));
    $responce_param = $client->getAadhaarStatus($request_param);
} 
catch (Exception $e) 
{ 
    echo "<h2>Exception Error!</h2>"; 
    echo $e->getMessage(); 
}