<?php  
error_reporting(1);
ini_set("error_reporting","E_ALL");
ini_set("error_display",true);
//https://nach.npci.org.in/CMAadhaar/AadhaarStatusService/AadhaarStatusService.wsdl
//$url = 'https://secure.softwarekey.com/solo/webservices/XmlCustomerService.asmx?WSDL';
$options = array(
                'soap_version'=>SOAP_1_1,
                'exceptions'=>true,
                'trace'=>1,
                'cache_wsdl'=>WSDL_CACHE_NONE
                );
$data = array('arg0' =>
  array(
    'aadhaarNumber' => '929925880342',
    'mobileNumber' => '9912112097',
    'requestNumber' => 'DBTM000001',
	'requestedDateTimeStamp' => '2016-04-28 17:08:25.064'
  ));
//print_r($data);

$method = 'getAadhaarStatus';

$client = new SoapClient(array('location' => 'https://nach.npci.org.in/CMAadhaar/AadhaarStatusService', 
           'uri' => 'https://nach.npci.org.in/CMAadhaar/AadhaarStatusService/AadhaarStatusService.wsdl', $options));

$result = $client->$method($data);
echo '<pre>';
	print_r($result);
	echo '</pre>';


?>

