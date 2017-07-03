<?php  
error_reporting(1);
ini_set("error_reporting","E_ALL");
ini_set("error_display",true);
//// This is function ////////////
function soapify(array $data)
{
        foreach ($data as &$value) {
                if (is_array($value)) {
                        $value = soapify($value);
                }
        }

        return new SoapVar($data, SOAP_ENC_OBJECT);
}
//// End of function ////////////



$client = new SoapClient(
    null,
    array(
        'location' => 'https://nach.npci.org.in/CMAadhaar/AadhaarStatusService', 
           'uri' => 'https://nach.npci.org.in/CMAadhaar/AadhaarStatusService/AadhaarStatusService.wsdl',
        'trace' => 1,
        'use' => SOAP_LITERAL,
    )
);

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





?>

