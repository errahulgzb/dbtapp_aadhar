<?php  
error_reporting(1);
ini_set("error_reporting","E_ALL");
ini_set("error_display",true);
//// This is function ////////////
/*
$client = new SoapClient('https://nach.npci.org.in/CMAadhaar/AadhaarStatusService/AadhaarStatusService.wsdl',array('trace' => 1,'use' => SOAP_LITERAL));

print_r($client);exit;

$data = soapify(array('arg0' =>
  array(
    'aadhaarNumber' => '929925880342',
    'mobileNumber' => '9912112097',
    'requestNumber' => 'DBTM000001',
	'requestedDateTimeStamp' => '2016-04-28 17:08:25.064'
  )));
$result = $client->getAadhaarStatus(new SoapParam($data, 'Data'));
echo '<pre>';
print_r($result);
echo '</pre>';

*/

$data = array('arg0' =>
  array(
    'aadhaarNumber' => '929925880342',
    'mobileNumber' => '9912112097',
    'requestNumber' => 'DBTM000001',
	'requestedDateTimeStamp' => '2016-04-28 17:08:25.064'
  ));

			$options = array(
                'soap_version'=>SOAP_1_2,
                'exceptions'=>true,
                'trace'=>1,
                'cache_wsdl'=>WSDL_CACHE_NONE
                ); 
  $client = new SoapClient("https://nach.npci.org.in/CMAadhaar/AadhaarStatusService/AadhaarStatusService.wsdl",$options);
  //$client = new SoapClient("http://192.168.100.132/dbtappstate/application/dbtappservice/wsdlgenerator.php?wsdl",$options);
//echo "aaa";exit;
  try{
	  echo "ssss";exit;
  } catch(SoapFault $client){
		echo '<pre dir="ltr">';
		print_r($client->getMessage());
		echo '</pre>';
	}
?>